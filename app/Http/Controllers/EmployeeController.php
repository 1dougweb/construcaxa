<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'cpf' => 'required|string|max:14|unique:employees,cpf',
            'rg' => 'nullable|string|max:20|unique:employees,rg',
            'document_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'document_file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'expected_daily_hours' => 'nullable|numeric|min:0.25|max:24',
        ]);

        DB::beginTransaction();
        try {
            // Criar usuário
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
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

            $documentFilePath = null;
            if ($request->hasFile('document_file')) {
                $documentFilePath = $request->file('document_file')->store('employees/documents', 'public');
            }

            // Criar funcionário
            Employee::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'department' => $validated['department'],
                'hire_date' => $validated['hire_date'],
                'birth_date' => $validated['birth_date'],
                'cpf' => $validated['cpf'],
                'rg' => $validated['rg'],
                'document_id' => $validated['document_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'profile_photo_path' => $profilePhotoPath,
                'document_file' => $documentFilePath,
                'emergency_contact' => $validated['emergency_contact'],
                'notes' => $validated['notes'],
                'hourly_rate' => $validated['hourly_rate'],
                'monthly_salary' => $validated['monthly_salary'],
                'expected_daily_hours' => $validated['expected_daily_hours'] ?? 8,
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao cadastrar funcionário. ' . $e->getMessage()]);
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'attendances', 'deductions']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'cpf' => 'required|string|max:14|unique:employees,cpf,' . $employee->id . ',id',
            'rg' => 'nullable|string|max:20|unique:employees,rg,' . $employee->id . ',id',
            'document_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'document_file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'expected_daily_hours' => 'nullable|numeric|min:0.25|max:24',
        ]);

        DB::beginTransaction();
        try {
            // Debug: Log the validated data
            \Log::info('Employee Update - Validated Data:', $validated);
            
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
                'birth_date' => $validated['birth_date'],
                'cpf' => $validated['cpf'],
                'rg' => $validated['rg'],
                'document_id' => $validated['document_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'emergency_contact' => $validated['emergency_contact'],
                'notes' => $validated['notes'],
                'hourly_rate' => $validated['hourly_rate'],
                'monthly_salary' => $validated['monthly_salary'],
                'expected_daily_hours' => $validated['expected_daily_hours'] ?? 8,
            ];

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = $request->file('profile_photo')->store('employees/photos', 'public');
            }

            if ($request->hasFile('document_file')) {
                $data['document_file'] = $request->file('document_file')->store('employees/documents', 'public');
            }

            // Debug: Log the data being updated
            \Log::info('Employee Update - Data to update:', $data);

            $employee->update($data);
            
            // Debug: Log the updated employee
            \Log::info('Employee Update - After update:', $employee->fresh()->toArray());

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao atualizar funcionário. ' . $e->getMessage()]);
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

        return redirect()->back()->with('success', 'Desconto adicionado com sucesso!');
    }

    public function destroyDeduction(Employee $employee, EmployeeDeduction $deduction)
    {
        if ($deduction->employee_id !== $employee->id) {
            return redirect()->back()->with('error', 'Desconto não pertence a este funcionário.');
        }

        $deduction->delete();

        return redirect()->back()->with('success', 'Desconto excluído com sucesso!');
    }
}
