<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Mail\NewEmployeeCredentials;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'cpf' => 'required|string|max:14|unique:employees,cpf',
            'rg' => 'nullable|string|max:20|unique:employees,rg',
            'cnpj' => 'nullable|string|max:18|unique:employees,cnpj',
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'document_file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Gerar senha aleatória
            $plainPassword = Str::random(12);

            // Criar usuário
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
            ]);

            // Atribuir papel de funcionário
            try {
                $user->assignRole('employee');
            } catch (\Throwable $e) {
                // ignora caso o papel não exista ainda
            }

            // Uploads
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('employees/photos', 'public');
            }

            // Criar funcionário
            Employee::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'department' => $validated['department'],
                'hire_date' => $validated['hire_date'],
                'birth_date' => $validated['birth_date'],
                'cpf' => $validated['cpf'],
                'rg' => $validated['rg'] ?? null,
                'cnpj' => $validated['cnpj'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'cellphone' => $validated['cellphone'] ?? null,
                'address' => $validated['address'],
                'profile_photo_path' => $profilePhotoPath,
                'emergency_contact' => $validated['emergency_contact'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            // Enviar email com as credenciais
            try {
                // Aplicar configurações de email definidas em /admin/email
                AdminController::applyEmailSettings();

                Mail::to($user->email)->send(new NewEmployeeCredentials($user, $plainPassword));
            } catch (\Throwable $e) {
                \Log::error('Erro ao enviar email de credenciais para funcionário: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                ]);
            }
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Funcionário cadastrado com sucesso!',
                    'redirect' => route('employees.index')
                ]);
            }
            
            return redirect()->route('employees.index')->with('success', 'Funcionário cadastrado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar funcionário: ' . $e->getMessage()
                ], 500);
            }
            throw ValidationException::withMessages(['error' => 'Erro ao cadastrar funcionário. ' . $e->getMessage()]);
        }
    }

    /**
     * Reenvia as credenciais de acesso para o funcionário, gerando uma nova senha.
     */
    public function resendCredentials(Employee $employee)
    {
        $user = $employee->user;

        DB::beginTransaction();
        try {
            // Gerar nova senha aleatória
            $plainPassword = Str::random(12);

            // Atualizar senha do usuário
            $user->password = Hash::make($plainPassword);
            $user->save();

            DB::commit();

            try {
                // Aplicar configurações de email definidas em /admin/email
                AdminController::applyEmailSettings();

                Mail::to($user->email)->send(new NewEmployeeCredentials($user, $plainPassword));
            } catch (\Throwable $e) {
                \Log::error('Erro ao reenviar email de credenciais para funcionário: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'employee_id' => $employee->id,
                ]);

                return redirect()
                    ->back()
                    ->with('error', 'Senha atualizada, mas ocorreu um erro ao reenviar as credenciais por email.');
            }

            return redirect()
                ->back()
                ->with('success', 'Credenciais reenviadas com sucesso para o email do funcionário.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Erro ao reenviar credenciais: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'attendances', 'deductions', 'documents']);
        return view('employees.show', compact('employee'));
    }

    public function storeDocument(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document');
        $path = $file->store('employees/documents', 'public');

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'name' => $validated['name'],
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Documento adicionado com sucesso!');
    }

    public function destroyDocument(Employee $employee, EmployeeDocument $document)
    {
        if ($document->employee_id !== $employee->id) {
            return redirect()->back()->with('error', 'Documento não pertence a este funcionário.');
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Documento excluído com sucesso!');
    }

    public function edit(Employee $employee)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->user->name,
                    'email' => $employee->user->email,
                    'position' => $employee->position,
                    'department' => $employee->department,
                    'hire_date' => $employee->hire_date?->format('Y-m-d'),
                    'birth_date' => $employee->birth_date?->format('Y-m-d'),
                    'cpf' => $employee->cpf,
                    'rg' => $employee->rg,
                    'cnpj' => $employee->cnpj,
                    'phone' => $employee->phone,
                    'cellphone' => $employee->cellphone,
                    'address' => $employee->address,
                    'emergency_contact' => $employee->emergency_contact,
                    'notes' => $employee->notes,
                    'profile_photo_path' => $employee->profile_photo_path,
                ]
            ]);
        }
        
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
                'position' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'hire_date' => 'required|date',
                'birth_date' => 'nullable|date',
                'cpf' => 'required|string|max:14|unique:employees,cpf,' . $employee->id . ',id',
                'rg' => 'nullable|string|max:20|unique:employees,rg,' . $employee->id . ',id',
                'cnpj' => 'nullable|string|max:18|unique:employees,cnpj,' . $employee->id . ',id',
                'phone' => 'nullable|string|max:20',
                'cellphone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'profile_photo' => 'nullable|image|max:2048',
                'emergency_contact' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();
            
            // Atualizar usuário
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Atualizar funcionário
            $data = [
                'position' => $validated['position'],
                'department' => $validated['department'],
                'hire_date' => $validated['hire_date'],
                'birth_date' => $validated['birth_date'] ?? null,
                'cpf' => $validated['cpf'],
                'rg' => $validated['rg'] ?? null,
                'cnpj' => $validated['cnpj'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'cellphone' => $validated['cellphone'] ?? null,
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ];

            if ($request->hasFile('profile_photo')) {
                // Deletar foto antiga se existir
                if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
                    Storage::disk('public')->delete($employee->profile_photo_path);
                }
                $data['profile_photo_path'] = $request->file('profile_photo')->store('employees/photos', 'public');
            } elseif ($request->input('remove_profile_photo')) {
                // Remover foto se o campo remove_profile_photo estiver presente
                if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
                    Storage::disk('public')->delete($employee->profile_photo_path);
                }
                $data['profile_photo_path'] = null;
            }

            $employee->update($data);

            DB::commit();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Funcionário atualizado com sucesso!',
                    'redirect' => route('employees.index')
                ]);
            }
            
            return redirect()->route('employees.index')->with('success', 'Funcionário atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar funcionário: ' . $e->getMessage(), [
                'employee_id' => $employee->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar funcionário: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Erro ao atualizar funcionário: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->serviceOrders()->exists()) {
            return back()->with('error', 'Não é possível excluir um funcionário que possui ordens de serviço.');
        }

        if ($employee->stockMovements()->exists()) {
            return back()->with('error', 'Não é possível excluir um funcionário que possui movimentações de estoque.');
        }

        DB::beginTransaction();
        try {
            $employee->user->delete(); // Isso também excluirá o funcionário devido à relação onDelete('cascade')
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao excluir funcionário. ' . $e->getMessage()]);
        }
    }

    public function storePhoto(Request $request, Employee $employee)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store("employees/photos/{$employee->id}", 'public');
        
        $photos = $employee->photos ?? [];
        $photos[] = $path;
        $employee->update(['photos' => $photos]);

        return redirect()->route('employees.show', $employee)->with('success', 'Foto adicionada com sucesso!');
    }

    public function destroyPhoto(Request $request, Employee $employee)
    {
        $request->validate([
            'photo_index' => 'required|integer|min:0',
        ]);

        $photos = $employee->photos ?? [];
        $index = $request->integer('photo_index');

        if (isset($photos[$index])) {
            $photoPath = $photos[$index];
            
            // Deletar arquivo do storage
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            // Remover do array
            unset($photos[$index]);
            $photos = array_values($photos); // Reindexar array

            $employee->update(['photos' => $photos]);

            return redirect()->route('employees.show', $employee)->with('success', 'Foto excluída com sucesso!');
        }

        return redirect()->route('employees.show', $employee)->with('error', 'Foto não encontrada.');
    }

    public function storeDeduction(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'attendance_id' => 'nullable|exists:attendances,id',
        ]);

        EmployeeDeduction::create([
            'employee_id' => $employee->id,
            'attendance_id' => $validated['attendance_id'] ?? null,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
        ]);

        // Retornar JSON para requisições AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Desconto adicionado com sucesso!'
            ]);
        }

        return redirect()->back()->with('success', 'Desconto adicionado com sucesso!');
    }

    public function destroyDeduction(Request $request, Employee $employee, EmployeeDeduction $deduction)
    {
        if ($deduction->employee_id !== $employee->id) {
            // Retornar JSON para requisições AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Desconto não pertence a este funcionário.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Desconto não pertence a este funcionário.');
        }

        $deduction->delete();

        // Retornar JSON para requisições AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Desconto excluído com sucesso!'
            ]);
        }

        return redirect()->back()->with('success', 'Desconto excluído com sucesso!');
    }

    public function storeEmployeeDocument(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document');
        $path = $file->store('employees/documents', 'public');

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'name' => $validated['name'],
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Documento adicionado com sucesso!');
    }
}