<?php

namespace App\Http\Livewire;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class EquipmentForm extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'edit-equipment' => 'loadEquipment',
        'reset-equipment-form' => 'resetForm',
    ];

    public $equipment;
    public $name;
    public $serial_number;
    public $description;
    public $equipment_category_id;
    public $purchase_price;
    public $purchase_date;
    public $notes;
    public $status = 'available';
    public $featured_photo;
    public $featured_photo_path;
    public $showDeleteModal = false;
    public $equipmentCategories;

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
            'equipment_category_id' => 'nullable|exists:equipment_categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'featured_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome do equipamento é obrigatório.',
        'serial_number.required' => 'O número de série é obrigatório.',
        'serial_number.unique' => 'Este número de série já está em uso.',
        'equipment_category_id.exists' => 'A categoria de equipamento selecionada não é válida.',
        'purchase_price.numeric' => 'O preço deve ser um número.',
        'purchase_price.min' => 'O preço deve ser maior ou igual a zero.',
        'purchase_date.date' => 'A data de compra deve ser uma data válida.',
        'status.required' => 'O status é obrigatório.',
        'status.in' => 'O status deve ser: disponível, emprestado, manutenção ou aposentado.',
        'featured_photo.image' => 'O arquivo deve ser uma imagem válida.',
        'featured_photo.mimes' => 'A imagem deve ser nos formatos: JPEG, PNG, JPG, GIF, WEBP ou AVIF.',
        'featured_photo.max' => 'A imagem deve ter no máximo 2MB.',
    ];

    public function mount($equipment = null, $equipmentId = null)
    {
        $this->equipmentCategories = EquipmentCategory::orderBy('name')->get();
        
        // Se receber equipmentId, carregar o equipamento
        if ($equipmentId && !$equipment) {
            $equipment = Equipment::find($equipmentId);
        }
        
        if ($equipment) {
            $this->equipment = $equipment;
            $this->name = $equipment->name;
            $this->serial_number = $equipment->serial_number;
            $this->description = $equipment->description;
            $this->equipment_category_id = $equipment->equipment_category_id;
            $this->purchase_price = $equipment->purchase_price;
            $this->purchase_date = $equipment->purchase_date?->format('Y-m-d');
            $this->notes = $equipment->notes;
            $this->status = $equipment->status;
            
            // Carregar foto destacada se existir
            if ($equipment->photos && is_array($equipment->photos) && count($equipment->photos) > 0) {
                $this->featured_photo_path = $equipment->photos[0];
            }
        }
    }

    public function loadEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $this->mount($equipment);
        }
    }

    public function resetForm()
    {
        $this->equipment = null;
        $this->name = '';
        $this->serial_number = '';
        $this->description = '';
        $this->equipment_category_id = null;
        $this->purchase_price = null;
        $this->purchase_date = null;
        $this->notes = '';
        $this->status = 'available';
        $this->featured_photo = null;
        $this->featured_photo_path = null;
        $this->showDeleteModal = false;
    }

    public function updatedFeaturedPhoto()
    {
        $this->validate([
            'featured_photo' => 'image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ], [
            'featured_photo.image' => 'O arquivo deve ser uma imagem válida.',
            'featured_photo.mimes' => 'A imagem deve ser nos formatos: JPEG, PNG, JPG, GIF, WEBP ou AVIF.',
            'featured_photo.max' => 'A imagem deve ter no máximo 2MB.',
        ]);
        
        // Limpar foto antiga quando uma nova é carregada
        $this->featured_photo_path = null;
    }

    public function confirmDeletePhoto()
    {
        $this->showDeleteModal = true;
    }

    public function cancelDeletePhoto()
    {
        $this->showDeleteModal = false;
    }

    public function deletePhoto()
    {
        if ($this->featured_photo_path) {
            // Se é um equipamento existente, deletar do storage e atualizar
            if ($this->equipment) {
                // Verificar se é caminho antigo (public/images) ou novo (storage)
                if (strpos($this->featured_photo_path, 'images/equipment/') === 0) {
                    // Caminho antigo - deletar de public
                    $filePath = public_path($this->featured_photo_path);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                } else {
                    // Caminho novo - deletar do storage
                    Storage::disk('public')->delete($this->featured_photo_path);
                }
                
                // Remover do array de fotos
                $photos = $this->equipment->photos ?? [];
                $photos = array_filter($photos, function($photo) {
                    return $photo !== $this->featured_photo_path;
                });
                $this->equipment->update(['photos' => array_values($photos)]);
            }
        }
        
        $this->featured_photo_path = null;
        $this->featured_photo = null;
        $this->showDeleteModal = false;
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
        try {
            DB::beginTransaction();
            
            $this->validate();
            $validatedData = [
                'name' => $this->name,
                'serial_number' => $this->serial_number,
                'description' => $this->description,
                'equipment_category_id' => $this->equipment_category_id,
                'purchase_price' => $this->purchase_price,
                'purchase_date' => $this->purchase_date,
                'notes' => $this->notes,
                'status' => $this->status,
            ];

            // Upload da foto destacada - salvar usando Storage do Laravel
            if ($this->featured_photo) {
                $filename = time() . '_' . uniqid() . '.' . $this->featured_photo->getClientOriginalExtension();
                $photoPath = $this->featured_photo->storeAs('equipment', $filename, 'public');
                
                // Se já existe foto destacada, deletar a antiga
                if ($this->featured_photo_path) {
                    // Verificar se é caminho antigo (public/images) ou novo (storage)
                    if (strpos($this->featured_photo_path, 'images/equipment/') === 0) {
                        // Caminho antigo - deletar de public
                        $oldPath = public_path($this->featured_photo_path);
                        if (File::exists($oldPath)) {
                            File::delete($oldPath);
                        }
                    } else {
                        // Caminho novo - deletar do storage
                        Storage::disk('public')->delete($this->featured_photo_path);
                    }
                }
                
                // Adicionar foto ao array de fotos
                $photos = $this->equipment && $this->equipment->photos ? $this->equipment->photos : [];
                // Remove a foto antiga se existir
                $photos = array_filter($photos, function($photo) {
                    return $photo !== $this->featured_photo_path;
                });
                array_unshift($photos, $photoPath); // Adiciona no início
                $validatedData['photos'] = array_values($photos);
            } elseif ($this->equipment && $this->featured_photo_path) {
                // Se não há upload novo mas há foto destacada existente, manter o array de fotos
                $photos = $this->equipment->photos ?? [];
                // Garantir que a foto destacada está no início
                $photos = array_filter($photos, function($photo) {
                    return $photo !== $this->featured_photo_path;
                });
                array_unshift($photos, $this->featured_photo_path);
                $validatedData['photos'] = array_values($photos);
            }

            $message = '';
            if ($this->equipment) {
                $this->equipment->update($validatedData);
                $message = 'Equipamento atualizado com sucesso.';
                session()->flash('success', $message);
            } else {
                Equipment::create($validatedData);
                $message = 'Equipamento criado com sucesso.';
                session()->flash('success', $message);
            }
            
            DB::commit();

            // Executar JavaScript diretamente para garantir fechamento e notificação
            $escapedMessage = addslashes($message);
            $this->js("
                (function() {
                    if (typeof closeOffcanvas === 'function') {
                        closeOffcanvas('equipment-offcanvas');
                    }
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('{$escapedMessage}', 'success', 4000);
                    }
                    if (typeof window.Livewire !== 'undefined') {
                        window.Livewire.dispatch('refresh-equipment');
                    }
                })();
            ");

            // Emitir evento para outros listeners (fallback)
            $this->dispatch('equipment-saved', [
                'message' => $message,
                'type' => 'success'
            ]);

            $this->resetForm();
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao salvar equipamento: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Erro ao salvar equipamento: ' . $e->getMessage());
            $this->js("
                (function() {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Erro ao salvar equipamento. Verifique os logs.', 'error', 5000);
                    }
                })();
            ");
        }
    }

    public function render()
    {
        return view('livewire.equipment-form');
    }
}