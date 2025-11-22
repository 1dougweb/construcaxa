<?php

namespace App\Http\Livewire;

use App\Models\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\ServiceOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EquipmentRequestForm extends Component
{
    public $equipmentRequest;
    public $number;
    public $employee_id;
    public $service_order_id;
    public $type = 'loan';
    public $purpose;
    public $notes;
    public $expected_return_date;
    public $selectedEquipment = [];
    public $employees;
    public $serviceOrders;
    public $search = '';
    public $searchResults = [];
    public $osSearch = '';
    public $osSearchResults = [];
    public $selectedServiceOrder = null;

    protected function rules()
    {
        $numberRule = 'required|string|max:20';
        
        if ($this->equipmentRequest) {
            $numberRule .= '|unique:equipment_requests,number,' . $this->equipmentRequest->id;
        } else {
            $numberRule .= '|unique:equipment_requests,number';
        }
        
        return [
            'number' => $numberRule,
            'employee_id' => 'required|exists:employees,id',
            'service_order_id' => 'nullable|exists:service_orders,id',
            'type' => 'required|in:loan,return',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'expected_return_date' => 'nullable|date|after:today',
            'selectedEquipment' => 'required|array|min:1',
            'selectedEquipment.*.id' => 'required|exists:equipment,id',
            'selectedEquipment.*.quantity' => 'required|integer|min:1',
            'selectedEquipment.*.condition_notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'number.required' => 'O número da requisição é obrigatório.',
        'number.unique' => 'Este número de requisição já está em uso.',
        'employee_id.required' => 'É necessário selecionar um funcionário.',
        'employee_id.exists' => 'O funcionário selecionado não é válido.',
        'service_order_id.exists' => 'A OS selecionada não é válida.',
        'type.required' => 'O tipo de requisição é obrigatório.',
        'type.in' => 'O tipo deve ser empréstimo ou devolução.',
        'selectedEquipment.required' => 'É necessário adicionar pelo menos um equipamento.',
        'selectedEquipment.min' => 'É necessário adicionar pelo menos um equipamento.',
        'selectedEquipment.*.id.required' => 'É necessário selecionar um equipamento.',
        'selectedEquipment.*.id.exists' => 'O equipamento selecionado não é válido.',
        'selectedEquipment.*.quantity.required' => 'A quantidade é obrigatória.',
        'selectedEquipment.*.quantity.integer' => 'A quantidade deve ser um número inteiro.',
        'selectedEquipment.*.quantity.min' => 'A quantidade deve ser maior que zero.',
    ];

    public function mount($equipmentRequest = null)
    {
        $this->employees = Employee::orderByName()->get();
        $this->serviceOrders = ServiceOrder::whereIn('status', ['pending', 'in_progress'])->orderBy('created_at', 'desc')->get();
        
        if ($equipmentRequest) {
            $this->equipmentRequest = $equipmentRequest;
            $this->number = $equipmentRequest->number;
            $this->employee_id = $equipmentRequest->employee_id;
            $this->service_order_id = $equipmentRequest->service_order_id;
            $this->type = $equipmentRequest->type;
            $this->purpose = $equipmentRequest->purpose;
            $this->notes = $equipmentRequest->notes;
            $this->expected_return_date = $equipmentRequest->expected_return_date?->format('Y-m-d');
            
            $equipmentRequest->load('serviceOrder');
            if ($equipmentRequest->serviceOrder) {
                $this->selectedServiceOrder = $equipmentRequest->serviceOrder;
            }
            
            $equipmentRequest->load('serviceOrder');
            if ($equipmentRequest->serviceOrder) {
                $this->selectedServiceOrder = $equipmentRequest->serviceOrder;
            }
            
            foreach ($equipmentRequest->items as $item) {
                $this->selectedEquipment[] = [
                    'id' => $item->equipment_id,
                    'name' => $item->equipment->name,
                    'serial_number' => $item->equipment->serial_number,
                    'quantity' => $item->quantity,
                    'condition_notes' => $item->condition_notes,
                ];
            }
        } else {
            $this->number = $this->generateRequestNumber();
        }
    }

    private function generateRequestNumber()
    {
        $lastNumber = EquipmentRequest::where('number', 'like', 'REQ-EQ%')->max('number');
        $nextNumber = $lastNumber ? (intval(substr($lastNumber, 6)) + 1) : 1;
        return 'REQ-EQ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function updatedSearch()
    {
        $this->searchResults = [];
        
        if (strlen($this->search) >= 2) {
            $query = Equipment::query();
            
            // Busca por nome ou número de série
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('serial_number', 'like', "%{$this->search}%");
            });
            
            // Filtrar por status baseado no tipo de requisição
            if ($this->type === 'loan') {
                $query->where('status', 'available');
            } elseif ($this->type === 'return') {
                $query->where('status', 'borrowed');
            }
            
            $this->searchResults = $query->with('category')->take(10)->get();
        }
    }

    public function updatedOsSearch()
    {
        if (strlen($this->osSearch) >= 2) {
            $this->osSearchResults = ServiceOrder::where(function($q) {
                $q->where('number', 'like', "%{$this->osSearch}%")
                  ->orWhere('client_name', 'like', "%{$this->osSearch}%");
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->take(5)
            ->get();
        } else {
            $this->osSearchResults = [];
        }
    }

    public function selectServiceOrder($serviceOrderId)
    {
        $serviceOrder = ServiceOrder::find($serviceOrderId);
        if ($serviceOrder) {
            $this->service_order_id = $serviceOrder->id;
            $this->selectedServiceOrder = $serviceOrder;
        }
        $this->osSearch = '';
        $this->osSearchResults = [];
    }

    public function clearServiceOrder()
    {
        $this->service_order_id = null;
        $this->selectedServiceOrder = null;
        $this->osSearch = '';
        $this->osSearchResults = [];
    }

    public function updatedType()
    {
        // Limpar equipamentos selecionados quando mudar o tipo
        $this->selectedEquipment = [];
        $this->search = '';
        $this->searchResults = [];
    }

    public function selectEquipment($equipmentId)
    {
        $equipment = Equipment::with('category')->find($equipmentId);
        
        if ($equipment) {
            $exists = collect($this->selectedEquipment)->contains('id', $equipmentId);
            
            if (!$exists) {
                $photo = null;
                if ($equipment->photos && count($equipment->photos) > 0) {
                    $photo = $equipment->photos[0];
                }
                
                $this->selectedEquipment[] = [
                    'id' => $equipment->id,
                    'name' => $equipment->name,
                    'serial_number' => $equipment->serial_number,
                    'quantity' => 1,
                    'condition_notes' => '',
                    'photo' => $photo,
                ];
            }
        }
        
        $this->search = '';
        $this->searchResults = [];
    }


    public function removeEquipment($index)
    {
        unset($this->selectedEquipment[$index]);
        $this->selectedEquipment = array_values($this->selectedEquipment);
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity > 0) {
            $this->selectedEquipment[$index]['quantity'] = $quantity;
        }
    }

    public function updateConditionNotes($index, $notes)
    {
        $this->selectedEquipment[$index]['condition_notes'] = $notes;
    }

    private function notify($message, $type = 'success')
    {
        $this->dispatch('notification', [
            'message' => $message,
            'type' => $type
        ]);
        
        session()->flash($type, $message);
    }

    public function save()
    {
        if (empty($this->number)) {
            $this->number = $this->generateRequestNumber();
        }
        
        $this->validate();

        DB::beginTransaction();
        try {
            if ($this->equipmentRequest) {
                $equipmentRequest = $this->equipmentRequest;
                $equipmentRequest->update([
                    'number' => $this->number,
                    'employee_id' => $this->employee_id,
                    'service_order_id' => $this->service_order_id,
                    'type' => $this->type,
                    'purpose' => $this->purpose,
                    'notes' => $this->notes,
                    'expected_return_date' => $this->expected_return_date,
                ]);
                
                // Limpar itens existentes para inserir os novos
                $equipmentRequest->items()->delete();
                
                $successMessage = 'Requisição de equipamento atualizada com sucesso!';
            } else {
                $equipmentRequest = EquipmentRequest::create([
                    'number' => $this->number,
                    'employee_id' => $this->employee_id,
                    'service_order_id' => $this->service_order_id,
                    'type' => $this->type,
                    'purpose' => $this->purpose,
                    'notes' => $this->notes,
                    'expected_return_date' => $this->expected_return_date,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);
                
                $successMessage = 'Requisição de equipamento criada com sucesso!';
            }

            // Criar os novos itens da requisição
            foreach ($this->selectedEquipment as $equipment) {
                $equipmentRequest->items()->create([
                    'equipment_id' => $equipment['id'],
                    'quantity' => $equipment['quantity'],
                    'condition_notes' => $equipment['condition_notes'],
                ]);
            }

            DB::commit();
            
            $this->notify($successMessage, 'success');
            
            return redirect()->route('equipment-requests.show', $equipmentRequest);
                
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify($e->getMessage(), 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.equipment-request-form');
    }
}