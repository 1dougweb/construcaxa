<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class EquipmentRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentRequest::with(['employee', 'items.equipment', 'serviceOrder']);

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        $equipmentRequests = $query->latest()->paginate(10);

        return view('equipment-requests.index', compact('equipmentRequests'));
    }

    public function create()
    {
        return view('equipment-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:equipment_requests,number',
            'employee_id' => 'required|exists:employees,id',
            'service_order_id' => 'nullable|exists:service_orders,id',
            'type' => 'required|in:loan,return',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'expected_return_date' => 'nullable|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.equipment_id' => 'required|exists:equipment,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $equipmentRequest = EquipmentRequest::create([
                'number' => $validated['number'],
                'employee_id' => $validated['employee_id'],
                'service_order_id' => $validated['service_order_id'],
                'type' => $validated['type'],
                'purpose' => $validated['purpose'],
                'notes' => $validated['notes'],
                'expected_return_date' => $validated['expected_return_date'],
                'user_id' => auth()->id(),
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $equipment = Equipment::find($item['equipment_id']);
                
                // Validações específicas por tipo
                if ($validated['type'] === 'loan' && !$equipment->isAvailable()) {
                    throw ValidationException::withMessages([
                        'error' => "Equipamento {$equipment->name} não está disponível para empréstimo"
                    ]);
                } elseif ($validated['type'] === 'return' && !$equipment->isBorrowed()) {
                    throw ValidationException::withMessages([
                        'error' => "Equipamento {$equipment->name} não está emprestado"
                    ]);
                }

                $equipmentRequest->items()->create([
                    'equipment_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'condition_notes' => $item['condition_notes'],
                ]);
            }

            DB::commit();
            return redirect()->route('equipment-requests.index')
                ->with('success', 'Requisição de equipamento criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(EquipmentRequest $equipmentRequest)
    {
        $equipmentRequest->load(['employee', 'serviceOrder', 'items.equipment', 'user']);
        return view('equipment-requests.show', compact('equipmentRequest'));
    }

    public function edit(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'pending') {
            return back()->with('error', 'Apenas requisições pendentes podem ser editadas.');
        }

        $equipmentRequest->load('items.equipment');
        return view('equipment-requests.edit', compact('equipmentRequest'));
    }

    public function update(Request $request, EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'pending') {
            return back()->with('error', 'Apenas requisições pendentes podem ser editadas.');
        }

        $validated = $request->validate([
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'expected_return_date' => 'nullable|date|after:today',
        ]);

        $equipmentRequest->update($validated);

        return redirect()->route('equipment-requests.show', $equipmentRequest)
            ->with('success', 'Requisição atualizada com sucesso!');
    }

    public function destroy(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'pending') {
            return back()->with('error', 'Apenas requisições pendentes podem ser excluídas.');
        }

        $equipmentRequest->delete();

        return redirect()->route('equipment-requests.index')
            ->with('success', 'Requisição excluída com sucesso!');
    }

    public function approve(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'pending') {
            return back()->with('error', 'Apenas requisições pendentes podem ser aprovadas.');
        }

        DB::beginTransaction();
        try {
            $equipmentRequest->update(['status' => 'approved']);
            
            // Processar automaticamente se for empréstimo ou devolução
            if ($equipmentRequest->type === 'loan') {
                $equipmentRequest->processLoan();
            } elseif ($equipmentRequest->type === 'return') {
                $equipmentRequest->processReturn();
            }

            DB::commit();
            return back()->with('success', 'Requisição aprovada e processada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'pending') {
            return back()->with('error', 'Apenas requisições pendentes podem ser rejeitadas.');
        }

        $equipmentRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Requisição rejeitada com sucesso!');
    }

    public function generatePDF(EquipmentRequest $equipmentRequest)
    {
        $equipmentRequest->load(['items.equipment', 'employee.user', 'user', 'serviceOrder']);
        
        $pdf = PDF::loadView('equipment-requests.pdf', compact('equipmentRequest'));
        
        return $pdf->stream("requisicao-equipamento-{$equipmentRequest->number}.pdf");
    }

    public function complete(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->status !== 'approved') {
            return back()->with('error', 'Apenas requisições aprovadas podem ser completadas.');
        }

        DB::beginTransaction();
        try {
            if ($equipmentRequest->type === 'loan') {
                $equipmentRequest->processLoan();
            } elseif ($equipmentRequest->type === 'return') {
                $equipmentRequest->processReturn();
            }

            DB::commit();
            return back()->with('success', 'Requisição processada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}