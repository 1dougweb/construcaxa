<?php

namespace App\Http\Livewire;

use App\Models\Inspection;
use App\Models\InspectionEnvironment;
use App\Models\InspectionEnvironmentItem;
use App\Models\InspectionEnvironmentTemplate;
use App\Models\InspectionItemPhoto;
use App\Models\InspectionItemSubItem;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InspectionForm extends Component
{
    use WithFileUploads;

    // Dados básicos
    public $inspection;
    public $client_id;
    public $inspection_date;
    public $address;
    public $latitude;
    public $longitude;
    public $description;
    public $notes;

    // Multistep - Nova estrutura
    public $currentStep = 1;
    public $templates = [];
    public $selectedEnvironments = []; // IDs dos templates selecionados no Step 1
    public $environments = []; // Ambientes selecionados com dados
    
    // Step 2 - Repeaters e Sub-repeaters
    public $environmentItems = []; // [envIndex => [items]]
    public $itemPhotos = []; // [item_key => [photos]]
    public $subItems = []; // [item_key => [sub_items]]
    
    // Upload temporário
    public $tempPhotos = []; // [item_key => [temp files]]

    // Step 3 - Google Maps e QR
    public $qrCodeUrl = null;

    // Clientes
    public $clients = [];
    public $clientSearch = '';
    public $clientSearchResults = [];
    public $selectedClient = null;

    protected function rules()
    {
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'inspection_date' => 'required|date',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        // Validação Step 1
        if ($this->currentStep === 1) {
            $rules['selectedEnvironments'] = 'required|array|min:1';
        }

        // Validação Step 2 - Sub-repeaters
        if ($this->currentStep === 2) {
            foreach ($this->environments as $envIndex => $env) {
                if (isset($this->environmentItems[$envIndex])) {
                    foreach ($this->environmentItems[$envIndex] as $itemIndex => $item) {
                        $itemKey = "{$envIndex}_{$itemIndex}";
                        if (isset($this->subItems[$itemKey])) {
                            foreach ($this->subItems[$itemKey] as $subIndex => $subItem) {
                                $rules["subItems.{$itemKey}.{$subIndex}.title"] = 'required|string|max:255';
                                $rules["subItems.{$itemKey}.{$subIndex}.quality_rating"] = 'required|in:poor,good,very_good,excellent';
                            }
                        }
                    }
                }
            }
        }

        return $rules;
    }

    public function mount($inspection = null)
    {
        $this->inspection = $inspection;
        $this->templates = InspectionEnvironmentTemplate::active()->orderBy('name')->get();
        $this->clients = Client::active()->orderBy('name')->get();
        
        if ($inspection) {
            $this->client_id = $inspection->client_id;
            $this->inspection_date = $inspection->inspection_date->format('Y-m-d');
            $this->address = $inspection->address;
            $this->latitude = $inspection->latitude;
            $this->longitude = $inspection->longitude;
            $this->description = $inspection->description;
            $this->notes = $inspection->notes;
            
            $inspection->load(['environments.template', 'environments.items.photos', 'environments.items.subItems']);
            
            foreach ($inspection->environments as $env) {
                $this->selectedEnvironments[] = $env->template_id;
                $this->environments[] = [
                    'id' => $env->id,
                    'template_id' => $env->template_id,
                    'name' => $env->name,
                ];
                
                $envIndex = count($this->environments) - 1;
                $this->environmentItems[$envIndex] = [];
                
                foreach ($env->items as $item) {
                    $itemIndex = count($this->environmentItems[$envIndex]);
                    $itemKey = "{$envIndex}_{$itemIndex}";
                    
                    $this->environmentItems[$envIndex][] = [
                        'id' => $item->id,
                        'title' => $item->title,
                    ];
                    
                    // Carregar fotos existentes
                    $this->itemPhotos[$itemKey] = [];
                    foreach ($item->photos as $photo) {
                        $this->itemPhotos[$itemKey][] = [
                            'id' => $photo->id,
                            'path' => $photo->photo_path,
                            'url' => asset('storage/' . $photo->photo_path),
                        ];
                    }
                    
                    // Carregar sub-items
                    $this->subItems[$itemKey] = [];
                    foreach ($item->subItems as $subItem) {
                        $this->subItems[$itemKey][] = [
                            'id' => $subItem->id,
                            'title' => $subItem->title,
                            'description' => $subItem->description,
                            'observations' => $subItem->observations,
                            'quality_rating' => $subItem->quality_rating,
                        ];
                    }
                }
            }
        } else {
            $this->inspection_date = now()->format('Y-m-d');
            // Garantir que selectedEnvironments seja um array vazio
            if (!is_array($this->selectedEnvironments)) {
                $this->selectedEnvironments = [];
            }
        }
    }

    public function updatedClientSearch()
    {
        if (strlen($this->clientSearch) >= 2) {
            $this->clientSearchResults = Client::where('name', 'like', '%' . $this->clientSearch . '%')
                ->orWhere('trading_name', 'like', '%' . $this->clientSearch . '%')
                ->orWhere('cpf', 'like', '%' . $this->clientSearch . '%')
                ->orWhere('cnpj', 'like', '%' . $this->clientSearch . '%')
                ->active()
                ->limit(10)
                ->get();
        } else {
            $this->clientSearchResults = [];
        }
    }

    public function selectClient($clientId)
    {
        $client = Client::find($clientId);
        if ($client) {
            $this->client_id = $client->id;
            $this->selectedClient = $client;
            $this->clientSearch = $client->name ?? $client->trading_name;
            $this->clientSearchResults = [];
        }
    }

    // Step 1 - Seleção de Ambientes
    public function toggleEnvironment($templateId)
    {
        if (!is_array($this->selectedEnvironments)) {
            $this->selectedEnvironments = [];
        }
        
        $index = array_search($templateId, $this->selectedEnvironments);
        if ($index !== false) {
            unset($this->selectedEnvironments[$index]);
            $this->selectedEnvironments = array_values($this->selectedEnvironments);
        } else {
            $this->selectedEnvironments[] = $templateId;
        }
        
        // Garantir que seja um array indexado numericamente
        $this->selectedEnvironments = array_values($this->selectedEnvironments);
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'client_id' => 'required|exists:clients,id',
                'inspection_date' => 'required|date',
                'selectedEnvironments' => 'required|array|min:1',
            ], [
                'client_id.required' => 'Por favor, selecione um cliente.',
                'client_id.exists' => 'O cliente selecionado é inválido.',
                'inspection_date.required' => 'Por favor, informe a data da vistoria.',
                'inspection_date.date' => 'A data informada é inválida.',
                'selectedEnvironments.required' => 'Por favor, selecione pelo menos um ambiente.',
                'selectedEnvironments.array' => 'Os ambientes selecionados são inválidos.',
                'selectedEnvironments.min' => 'Por favor, selecione pelo menos um ambiente.',
            ]);
            
            // Inicializar ambientes selecionados
            $this->environments = [];
            foreach ($this->selectedEnvironments as $templateId) {
                $template = InspectionEnvironmentTemplate::find($templateId);
                if ($template) {
                    $this->environments[] = [
                        'id' => null,
                        'template_id' => $templateId,
                        'name' => $template->name,
                    ];
                    $envIndex = count($this->environments) - 1;
                    $this->environmentItems[$envIndex] = [];
                    
                    // Criar repeater principal para este ambiente
                    $defaultTitle = $template->name; // Título pré-definido
                    $this->environmentItems[$envIndex][] = [
                        'id' => null,
                        'title' => $defaultTitle,
                    ];
                }
            }
        } elseif ($this->currentStep === 2) {
            // Validar sub-repeaters
            $this->validate();
        }
        
        if ($this->currentStep < 3) {
            $this->currentStep++;
            // Disparar evento para o JavaScript quando chegar no Step 3
            if ($this->currentStep === 3) {
                $this->dispatch('stepChanged', step: 3);
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('stepChanged', step: $this->currentStep);
        }
    }

    // Step 2 - Gerenciar Sub-repeaters
    public function addSubItem($envIndex, $itemIndex)
    {
        $itemKey = "{$envIndex}_{$itemIndex}";
        if (!isset($this->subItems[$itemKey])) {
            $this->subItems[$itemKey] = [];
        }
        
        $this->subItems[$itemKey][] = [
            'id' => null,
            'title' => '',
            'description' => '',
            'observations' => '',
            'quality_rating' => 'good',
        ];
    }

    public function removeSubItem($envIndex, $itemIndex, $subIndex)
    {
        $itemKey = "{$envIndex}_{$itemIndex}";
        if (isset($this->subItems[$itemKey][$subIndex])) {
            // Se tem ID, marcar para deletar
            if (isset($this->subItems[$itemKey][$subIndex]['id'])) {
                InspectionItemSubItem::find($this->subItems[$itemKey][$subIndex]['id'])->delete();
            }
            unset($this->subItems[$itemKey][$subIndex]);
            $this->subItems[$itemKey] = array_values($this->subItems[$itemKey]);
        }
    }

    public function updatedTempPhotos($value, $path)
    {
        if (preg_match('/tempPhotos\.([^\.]+)/', $path, $matches)) {
            $itemKey = $matches[1];
            if (isset($this->tempPhotos[$itemKey])) {
                foreach ($this->tempPhotos[$itemKey] as $index => $photo) {
                    if ($photo) {
                        try {
                            if (!$photo->isValid()) {
                                $this->addError("tempPhotos.{$itemKey}.{$index}", 'Esta imagem é inválida.');
                                unset($this->tempPhotos[$itemKey][$index]);
                                continue;
                            }
                            
                            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                            if (!in_array($photo->getMimeType(), $allowedMimes)) {
                                $this->addError("tempPhotos.{$itemKey}.{$index}", 'Formato não permitido.');
                                unset($this->tempPhotos[$itemKey][$index]);
                                continue;
                            }
                            
                            if ($photo->getSize() > 5 * 1024 * 1024) {
                                $this->addError("tempPhotos.{$itemKey}.{$index}", 'Imagem deve ter no máximo 5MB.');
                                unset($this->tempPhotos[$itemKey][$index]);
                                continue;
                            }
                        } catch (\Exception $e) {
                            $this->addError("tempPhotos.{$itemKey}.{$index}", 'Erro: ' . $e->getMessage());
                            unset($this->tempPhotos[$itemKey][$index]);
                        }
                    }
                }
                if (isset($this->tempPhotos[$itemKey])) {
                    $this->tempPhotos[$itemKey] = array_values($this->tempPhotos[$itemKey]);
                }
            }
        }
    }

    public function removePhoto($itemKey, $photoIndex, $isTemp = true)
    {
        if ($isTemp && isset($this->tempPhotos[$itemKey][$photoIndex])) {
            $photo = $this->tempPhotos[$itemKey][$photoIndex];
            try {
                if (is_object($photo) && method_exists($photo, 'getRealPath')) {
                    $realPath = $photo->getRealPath();
                    if ($realPath && file_exists($realPath)) {
                        @unlink($realPath);
                    }
                }
            } catch (\Exception $e) {
                // Ignorar erros
            }
            unset($this->tempPhotos[$itemKey][$photoIndex]);
            $this->tempPhotos[$itemKey] = array_values($this->tempPhotos[$itemKey]);
        } elseif (!$isTemp && isset($this->itemPhotos[$itemKey][$photoIndex])) {
            $photo = $this->itemPhotos[$itemKey][$photoIndex];
            if (isset($photo['id'])) {
                InspectionItemPhoto::find($photo['id'])->delete();
            }
            unset($this->itemPhotos[$itemKey][$photoIndex]);
            $this->itemPhotos[$itemKey] = array_values($this->itemPhotos[$itemKey]);
        }
    }

    // Step 3 - Google Maps
    public function updatedAddress()
    {
        // Será preenchido via JavaScript com Google Maps API
    }

    public function generateQrCode()
    {
        if (!$this->inspection || !$this->inspection->public_token) {
            // Será gerado no save
            return;
        }
        
        $url = $this->inspection->public_url;
        $this->qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url);
    }

    public function saveDraft()
    {
        DB::beginTransaction();
        try {
            $inspectionData = [
                'client_id' => $this->client_id,
                'number' => $this->inspection ? $this->inspection->number : Inspection::generateNumber(),
                'inspection_date' => $this->inspection_date ?? now()->format('Y-m-d'),
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'description' => $this->description,
                'notes' => $this->notes,
                'user_id' => auth()->id(),
                'status' => 'draft',
            ];

            if ($this->inspection) {
                $this->inspection->update($inspectionData);
                $inspection = $this->inspection;
            } else {
                $inspection = Inspection::create($inspectionData);
            }

            $this->saveEnvironmentsAndItems($inspection, false);

            DB::commit();
            
            session()->flash('message', 'Rascunho salvo com sucesso!');
            return redirect()->route('inspections.edit', $inspection->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar rascunho: ' . $e->getMessage());
        }
    }

    protected function saveEnvironmentsAndItems($inspection, $validate = true)
    {
        // Deletar ambientes removidos
        if ($this->inspection) {
            $existingEnvironmentIds = collect($this->environments)->pluck('id')->filter()->toArray();
            InspectionEnvironment::where('inspection_id', $inspection->id)
                ->whereNotIn('id', $existingEnvironmentIds)
                ->delete();
        }

        // Salvar ambientes e itens
        foreach ($this->environments as $envIndex => $env) {
            // Se tem ID, buscar e atualizar, senão criar novo
            if (isset($env['id']) && $env['id']) {
                $environment = InspectionEnvironment::find($env['id']);
                if ($environment) {
                    $environment->update([
                        'template_id' => $env['template_id'],
                        'name' => $env['name'],
                        'sort_order' => $envIndex,
                    ]);
                } else {
                    $environment = InspectionEnvironment::create([
                        'inspection_id' => $inspection->id,
                        'template_id' => $env['template_id'],
                        'name' => $env['name'],
                        'sort_order' => $envIndex,
                    ]);
                }
            } else {
                $environment = InspectionEnvironment::create([
                    'inspection_id' => $inspection->id,
                    'template_id' => $env['template_id'],
                    'name' => $env['name'],
                    'sort_order' => $envIndex,
                ]);
            }

            // Salvar itens do ambiente (repeaters principais)
            if (isset($this->environmentItems[$envIndex])) {
                // Deletar itens removidos
                if ($environment->id) {
                    $existingItemIds = collect($this->environmentItems[$envIndex])->pluck('id')->filter()->toArray();
                    InspectionEnvironmentItem::where('inspection_environment_id', $environment->id)
                        ->whereNotIn('id', $existingItemIds)
                        ->delete();
                }

                foreach ($this->environmentItems[$envIndex] as $itemIndex => $item) {
                    $itemKey = "{$envIndex}_{$itemIndex}";
                    
                    // Se tem ID, buscar e atualizar, senão criar novo
                    if (isset($item['id']) && $item['id']) {
                        $environmentItem = InspectionEnvironmentItem::find($item['id']);
                        if ($environmentItem) {
                            $environmentItem->update([
                                'title' => $item['title'],
                                'sort_order' => $itemIndex,
                            ]);
                        } else {
                            $environmentItem = InspectionEnvironmentItem::create([
                                'inspection_environment_id' => $environment->id,
                                'title' => $item['title'],
                                'sort_order' => $itemIndex,
                            ]);
                        }
                    } else {
                        $environmentItem = InspectionEnvironmentItem::create([
                            'inspection_environment_id' => $environment->id,
                            'title' => $item['title'],
                            'sort_order' => $itemIndex,
                        ]);
                    }

                    // Salvar fotos temporárias
                    if (isset($this->tempPhotos[$itemKey]) && is_array($this->tempPhotos[$itemKey])) {
                        $existingPhotosCount = InspectionItemPhoto::where('inspection_environment_item_id', $environmentItem->id)->count();
                        
                        foreach ($this->tempPhotos[$itemKey] as $photoIndex => $photo) {
                            if ($photo && $photo->isValid()) {
                                try {
                                    $path = $photo->store('inspections', 'public');
                                    InspectionItemPhoto::create([
                                        'inspection_environment_item_id' => $environmentItem->id,
                                        'photo_path' => $path,
                                        'sort_order' => $existingPhotosCount + $photoIndex,
                                    ]);
                                } catch (\Exception $e) {
                                    \Log::error('Erro ao salvar foto: ' . $e->getMessage());
                                }
                            }
                        }
                        unset($this->tempPhotos[$itemKey]);
                    }

                    // Salvar sub-items
                    if (isset($this->subItems[$itemKey])) {
                        // Deletar sub-items removidos
                        if ($environmentItem->id) {
                            $existingSubItemIds = collect($this->subItems[$itemKey])->pluck('id')->filter()->toArray();
                            InspectionItemSubItem::where('inspection_environment_item_id', $environmentItem->id)
                                ->whereNotIn('id', $existingSubItemIds)
                                ->delete();
                        }

                        foreach ($this->subItems[$itemKey] as $subIndex => $subItem) {
                            if ($validate && empty($subItem['title'])) {
                                continue;
                            }
                            
                            // Se tem ID, buscar e atualizar, senão criar novo
                            if (isset($subItem['id']) && $subItem['id']) {
                                $subItemModel = InspectionItemSubItem::find($subItem['id']);
                                if ($subItemModel) {
                                    $subItemModel->update([
                                        'title' => $subItem['title'] ?? '',
                                        'description' => $subItem['description'] ?? '',
                                        'observations' => $subItem['observations'] ?? '',
                                        'quality_rating' => $subItem['quality_rating'] ?? 'good',
                                        'sort_order' => $subIndex,
                                    ]);
                                } else {
                                    InspectionItemSubItem::create([
                                        'inspection_environment_item_id' => $environmentItem->id,
                                        'title' => $subItem['title'] ?? '',
                                        'description' => $subItem['description'] ?? '',
                                        'observations' => $subItem['observations'] ?? '',
                                        'quality_rating' => $subItem['quality_rating'] ?? 'good',
                                        'sort_order' => $subIndex,
                                    ]);
                                }
                            } else {
                                InspectionItemSubItem::create([
                                    'inspection_environment_item_id' => $environmentItem->id,
                                    'title' => $subItem['title'] ?? '',
                                    'description' => $subItem['description'] ?? '',
                                    'observations' => $subItem['observations'] ?? '',
                                    'quality_rating' => $subItem['quality_rating'] ?? 'good',
                                    'sort_order' => $subIndex,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Gerar token público se não existir
            $publicToken = null;
            if ($this->inspection && $this->inspection->public_token) {
                $publicToken = $this->inspection->public_token;
            } else {
                $publicToken = bin2hex(random_bytes(32));
            }

            $inspectionData = [
                'client_id' => $this->client_id,
                'number' => $this->inspection ? $this->inspection->number : Inspection::generateNumber(),
                'inspection_date' => $this->inspection_date,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'description' => $this->description,
                'notes' => $this->notes,
                'user_id' => auth()->id(),
                'status' => 'in_progress',
                'public_token' => $publicToken,
            ];

            if ($this->inspection) {
                $this->inspection->update($inspectionData);
                $inspection = $this->inspection;
            } else {
                $inspection = Inspection::create($inspectionData);
            }

            // Salvar ambientes e itens
            $this->saveEnvironmentsAndItems($inspection, true);

            // Gerar QR code
            $this->generateQrCodeForInspection($inspection);

            DB::commit();
            
            session()->flash('message', $this->inspection ? 'Vistoria atualizada com sucesso!' : 'Vistoria criada com sucesso!');
            return redirect()->route('inspections.show', $inspection->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar vistoria: ' . $e->getMessage());
        }
    }

    protected function generateQrCodeForInspection($inspection)
    {
        $url = route('inspections.public', $inspection->public_token);
        
        // Usar API externa para gerar QR code
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
        
        // Baixar e salvar QR code
        try {
            $qrCodeContent = file_get_contents($qrCodeUrl);
            if ($qrCodeContent) {
                $qrCodePath = 'inspections/qrcodes/' . $inspection->number . '.png';
                Storage::disk('public')->put($qrCodePath, $qrCodeContent);
                $inspection->update(['qr_code_path' => $qrCodePath]);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar QR code: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inspection-form');
    }
}
