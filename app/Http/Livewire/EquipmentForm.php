<?php

namespace App\Http\Livewire;

use App\Models\Equipment;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EquipmentForm extends Component
{
    use WithFileUploads;

    public $equipment;
    public $name;
    public $serial_number;
    public $description;
    public $category_id;
    public $purchase_price;
    public $purchase_date;
    public $notes;
    public $status = 'available';
    public $photos = [];
    public $existingPhotos = [];
    public $categories;

    protected function rules()
    {
        $serialRule = 'required|string|max:255';
        
        if ($this->equipment) {
            $serialRule .= '|unique:equipment,serial_number,' . $this->equipment->id;
        } else {
            $serialRule .= '|unique:equipment,serial_number';
        }
        
        return [
            'name' => 'required|string|max:255',
            'serial_number' => $serialRule,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome do equipamento é obrigatório.',
        'serial_number.required' => 'O número de série é obrigatório.',
        'serial_number.unique' => 'Este número de série já está em uso.',
        'category_id.exists' => 'A categoria selecionada não é válida.',
        'purchase_price.numeric' => 'O preço deve ser um número.',
        'purchase_price.min' => 'O preço deve ser maior ou igual a zero.',
        'purchase_date.date' => 'A data de compra deve ser uma data válida.',
        'status.required' => 'O status é obrigatório.',
        'status.in' => 'O status deve ser: disponível, emprestado, manutenção ou aposentado.',
        'photos.*.image' => 'Todos os arquivos devem ser imagens.',
        'photos.*.mimes' => 'As imagens devem ser dos tipos: jpeg, png, jpg, gif.',
        'photos.*.max' => 'Cada imagem deve ter no máximo 2MB.',
    ];

    public function mount($equipment = null)
    {
        $this->categories = Category::orderBy('name')->get();
        
        if ($equipment) {
            $this->equipment = $equipment;
            $this->name = $equipment->name;
            $this->serial_number = $equipment->serial_number;
            $this->description = $equipment->description;
            $this->category_id = $equipment->category_id;
            $this->purchase_price = $equipment->purchase_price;
            $this->purchase_date = $equipment->purchase_date?->format('Y-m-d');
            $this->notes = $equipment->notes;
            $this->status = $equipment->status;
            $this->existingPhotos = $equipment->photos ?? [];
        }
    }

    public function updatedPhotos()
    {
        $this->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function removeExistingPhoto($index)
    {
        if (isset($this->existingPhotos[$index])) {
            // Deletar foto do storage
            Storage::disk('public')->delete($this->existingPhotos[$index]);
            
            // Remover do array
            unset($this->existingPhotos[$index]);
            $this->existingPhotos = array_values($this->existingPhotos);
            
            // Atualizar no banco se estiver editando
            if ($this->equipment) {
                $this->equipment->update(['photos' => $this->existingPhotos]);
            }
        }
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
        $this->validate();

        try {
            $allPhotos = $this->existingPhotos;
            
            // Upload das novas fotos
            if ($this->photos) {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('equipment/photos', 'public');
                    $allPhotos[] = $path;
                }
            }

            $data = [
                'name' => $this->name,
                'serial_number' => $this->serial_number,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'purchase_price' => $this->purchase_price,
                'purchase_date' => $this->purchase_date,
                'notes' => $this->notes,
                'status' => $this->status,
                'photos' => $allPhotos,
            ];

            if ($this->equipment) {
                $this->equipment->update($data);
                $successMessage = 'Equipamento atualizado com sucesso!';
                $redirectRoute = 'equipment.show';
                $routeParam = $this->equipment;
            } else {
                $equipment = Equipment::create($data);
                $successMessage = 'Equipamento cadastrado com sucesso!';
                $redirectRoute = 'equipment.show';
                $routeParam = $equipment;
            }

            $this->notify($successMessage, 'success');
            
            return redirect()->route($redirectRoute, $routeParam);
                
        } catch (\Exception $e) {
            $this->notify('Erro ao salvar equipamento: ' . $e->getMessage(), 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.equipment-form');
    }
}