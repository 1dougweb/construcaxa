<?php

namespace App\Http\Livewire;

use App\Models\TechnicalInspection;
use App\Models\InspectionEnvironment;
use App\Models\InspectionElement;
use App\Models\InspectionEnvironmentTemplate;
use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnicalInspectionForm extends Component
{
    use WithFileUploads;

    // Navegação de etapas
    public $currentStep = 1;
    public $totalSteps = 5;
    public $stepHistory = [];

    // Etapa 1: Seleção de Ambientes
    public $availableEnvironments = [
        'Sala',
        'Cozinha',
        'Quarto',
        'Banheiro',
        'Área Externa',
        'Fachada',
        'Infraestrutura',
        'Instalações',
        'Garagem',
        'Varanda',
    ];
    public $selectedEnvironments = [];
    public $customEnvironmentName = '';

    // Etapa 2: Informações Gerais
    public $technicalInspection = null;
    public $number;
    public $inspection_date;
    public $address;
    public $unit_area;
    public $furniture_status;
    public $map_image;
    public $coordinates = ['lat' => null, 'lng' => null];
    public $responsible_name;
    public $involved_parties;
    public $client_id;
    public $project_id;

    // Etapa 3: Repeater de Ambientes
    public $environments = [];

    // Etapa 4: Repeater Interno de Elementos
    public $currentEnvironmentIndex = null;

    // Dados auxiliares
    public $clients = [];
    public $projects = [];
    
    // Mapa
    public $mapInitialized = false;

    public function rules()
    {
        $rules = [];

        // Validação por etapa
        switch ($this->currentStep) {
            case 1:
                $rules['selectedEnvironments'] = 'required|array|min:1';
                break;
                
            case 2:
                $rules['inspection_date'] = 'required|date';
                $rules['address'] = 'required|string|max:500';
                $rules['responsible_name'] = 'required|string|max:255';
                $rules['unit_area'] = 'nullable|numeric|min:0';
                $rules['coordinates'] = 'nullable|array';
                $rules['client_id'] = 'nullable|exists:clients,id';
                $rules['project_id'] = 'nullable|exists:projects,id';
                break;
                
            case 3:
                $rules['environments'] = 'required|array|min:1';
                $rules['environments.*.name'] = 'required|string|max:255';
                $rules['environments.*.photos'] = 'nullable|array';
                $rules['environments.*.videos'] = 'nullable|array';
                break;
                
            case 4:
                $rules['environments.*.elements'] = 'required|array|min:1';
                $rules['environments.*.elements.*.name'] = 'required|string|max:255';
                $rules['environments.*.elements.*.condition_status'] = 'required|in:poor,fair,good,very_good,excellent';
                break;
                
            case 5:
                // Etapa 5 não precisa de validação, apenas revisão
                // Retorna array vazio
                break;
        }

        return $rules;
    }

    protected $messages = [
        'selectedEnvironments.required' => 'Selecione pelo menos um ambiente.',
        'selectedEnvironments.min' => 'Selecione pelo menos um ambiente.',
        'inspection_date.required' => 'A data da vistoria é obrigatória.',
        'inspection_date.date' => 'A data da vistoria deve ser uma data válida.',
        'address.required' => 'O endereço é obrigatório.',
        'address.max' => 'O endereço não pode ter mais de 500 caracteres.',
        'responsible_name.required' => 'O nome do responsável é obrigatório.',
        'responsible_name.max' => 'O nome do responsável não pode ter mais de 255 caracteres.',
        'unit_area.numeric' => 'A metragem deve ser um número.',
        'unit_area.min' => 'A metragem deve ser maior ou igual a zero.',
        'environments.required' => 'Adicione pelo menos um ambiente.',
        'environments.min' => 'Adicione pelo menos um ambiente.',
        'environments.*.name.required' => 'O nome do ambiente é obrigatório.',
        'environments.*.elements.required' => 'Adicione pelo menos um elemento ao ambiente.',
        'environments.*.elements.min' => 'Adicione pelo menos um elemento ao ambiente.',
        'environments.*.elements.*.name.required' => 'O nome do elemento é obrigatório.',
        'environments.*.elements.*.condition_status.required' => 'Selecione a condição do elemento.',
        'environments.*.elements.*.condition_status.in' => 'A condição selecionada é inválida.',
    ];

    public function mount($technicalInspection = null)
    {
        $this->clients = Client::orderBy('name')->get();
        $this->projects = Project::orderBy('name')->get();

        if ($technicalInspection) {
            $this->technicalInspection = $technicalInspection;
            $this->loadInspectionData($technicalInspection);
        } else {
            $this->number = TechnicalInspection::generateNumber();
            $this->inspection_date = now()->format('Y-m-d');
            $this->responsible_name = auth()->user()->name ?? '';
        }
    }

    protected function loadInspectionData($inspection)
    {
        $this->number = $inspection->number;
        $this->inspection_date = $inspection->inspection_date->format('Y-m-d');
        $this->address = $inspection->address;
        $this->unit_area = $inspection->unit_area;
        $this->furniture_status = $inspection->furniture_status;
        $this->coordinates = $inspection->coordinates ?? ['lat' => null, 'lng' => null];
        $this->responsible_name = $inspection->responsible_name;
        $this->involved_parties = $inspection->involved_parties;
        $this->client_id = $inspection->client_id;
        $this->project_id = $inspection->project_id;

        // Carregar ambientes
        $inspection->load(['environments.elements']);
        $this->selectedEnvironments = $inspection->environments->pluck('name')->toArray();

        foreach ($inspection->environments as $env) {
            $this->environments[] = [
                'name' => $env->name,
                'technical_notes' => $env->technical_notes ?? '',
                'photos' => $env->photos ?? [],
                'videos' => $env->videos ?? [],
                'measurements' => $env->measurements ?? '',
                'google_drive_link' => $env->google_drive_link ?? '',
                'qr_code_path' => $env->qr_code_path ?? '',
                'elements' => $env->elements->map(function ($elem) {
                    return [
                        'name' => $elem->name,
                        'technical_notes' => $elem->technical_notes ?? '',
                        'condition_status' => $elem->condition_status,
                        'photos' => $elem->photos ?? [],
                        'measurements' => $elem->measurements ?? '',
                        'defects_identified' => $elem->defects_identified ?? '',
                        'probable_causes' => $elem->probable_causes ?? '',
                    ];
                })->toArray(),
            ];
        }
    }

    // Navegação entre etapas
    public function goToStep($step)
    {
        if ($step < 1 || $step > $this->totalSteps) {
            return;
        }

        // Validar etapa atual antes de avançar (exceto na etapa 5)
        if ($step > $this->currentStep && $this->currentStep !== 5) {
            try {
                $rules = $this->rules();
                if (!empty($rules)) {
                    $this->validate($rules);
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->dispatch('validation-error', ['errors' => $e->errors()]);
                return;
            }
        }

        // Salvar histórico
        if (!in_array($this->currentStep, $this->stepHistory)) {
            $this->stepHistory[] = $this->currentStep;
        }

        $this->currentStep = $step;
        
        // Disparar evento para inicializar mapa se necessário
        $this->dispatch('step-changed', $step);
    }

    public function goBack()
    {
        if (count($this->stepHistory) > 0) {
            $previousStep = array_pop($this->stepHistory);
            $this->currentStep = $previousStep;
        } elseif ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function nextStep()
    {
        $this->goToStep($this->currentStep + 1);
    }

    // Etapa 1: Seleção de Ambientes
    public function selectEnvironment($name)
    {
        if (!in_array($name, $this->selectedEnvironments)) {
            $this->selectedEnvironments[] = $name;
        } else {
            // Se já está selecionado, remover
            $this->selectedEnvironments = array_values(array_filter($this->selectedEnvironments, function($env) use ($name) {
                return $env !== $name;
            }));
        }
    }

    public function removeSelectedEnvironment($index)
    {
        unset($this->selectedEnvironments[$index]);
        $this->selectedEnvironments = array_values($this->selectedEnvironments);
    }

    public function addCustomEnvironment()
    {
        if (!empty($this->customEnvironmentName) && !in_array($this->customEnvironmentName, $this->selectedEnvironments)) {
            $this->selectedEnvironments[] = $this->customEnvironmentName;
            $this->customEnvironmentName = '';
        }
    }

    // Etapa 3: Repeater de Ambientes
    public function initializeEnvironments()
    {
        if (empty($this->environments)) {
            foreach ($this->selectedEnvironments as $envName) {
                $this->environments[] = [
                    'name' => $envName,
                    'technical_notes' => '',
                    'photos' => [],
                    'videos' => [],
                    'measurements' => '',
                    'google_drive_link' => '',
                    'qr_code_path' => '',
                    'elements' => [],
                ];
            }
        }
    }

    public function removeEnvironment($index)
    {
        unset($this->environments[$index]);
        $this->environments = array_values($this->environments);
    }

    // Etapa 4: Repeater Interno de Elementos
    public function addElement($environmentIndex)
    {
        if (!isset($this->environments[$environmentIndex]['elements'])) {
            $this->environments[$environmentIndex]['elements'] = [];
        }

        $this->environments[$environmentIndex]['elements'][] = [
            'name' => '',
            'technical_notes' => '',
            'condition_status' => 'good',
            'photos' => [],
            'measurements' => '',
            'defects_identified' => '',
            'probable_causes' => '',
        ];
    }

    public function removeElement($environmentIndex, $elementIndex)
    {
        unset($this->environments[$environmentIndex]['elements'][$elementIndex]);
        $this->environments[$environmentIndex]['elements'] = array_values(
            $this->environments[$environmentIndex]['elements']
        );
    }

    // Propriedades temporárias para upload
    public $tempPhotos = [];
    public $uploadContext = null;

    // Upload de fotos múltiplas - método simplificado
    // O upload será feito via input file direto do Livewire
    public function updatedTempPhotos()
    {
        if ($this->uploadContext && count($this->tempPhotos) > 0) {
            $context = $this->uploadContext;
            
            // Validar todas as fotos
            $this->validate([
                'tempPhotos.*' => 'image|max:5120',
            ], [], [
                'tempPhotos.*' => 'foto',
            ]);
            
            foreach ($this->tempPhotos as $photo) {
                try {
                    if ($context['type'] === 'environment') {
                        $path = $photo->store("inspections/temp/environments/{$context['index']}", 'public');
                        if (!isset($this->environments[$context['index']]['photos'])) {
                            $this->environments[$context['index']]['photos'] = [];
                        }
                        $this->environments[$context['index']]['photos'][] = $path;
                    } else {
                        $envIndex = $context['envIndex'];
                        $elemIndex = $context['elemIndex'];
                        $path = $photo->store("inspections/temp/elements/{$envIndex}/{$elemIndex}", 'public');
                        if (!isset($this->environments[$envIndex]['elements'][$elemIndex]['photos'])) {
                            $this->environments[$envIndex]['elements'][$elemIndex]['photos'] = [];
                        }
                        $this->environments[$envIndex]['elements'][$elemIndex]['photos'][] = $path;
                    }
                } catch (\Exception $e) {
                    $this->dispatch('upload-error', ['error' => $e->getMessage()]);
                }
            }
            
            $this->tempPhotos = [];
            $this->uploadContext = null;
            $this->dispatch('photos-uploaded');
        }
    }

    public function removePhoto($environmentIndex, $photoIndex, $elementIndex = null)
    {
        if ($elementIndex !== null) {
            // Remover foto de elemento
            if (isset($this->environments[$environmentIndex]['elements'][$elementIndex]['photos'][$photoIndex])) {
                $photoPath = $this->environments[$environmentIndex]['elements'][$elementIndex]['photos'][$photoIndex];
                Storage::disk('public')->delete($photoPath);
                unset($this->environments[$environmentIndex]['elements'][$elementIndex]['photos'][$photoIndex]);
                $this->environments[$environmentIndex]['elements'][$elementIndex]['photos'] = array_values(
                    $this->environments[$environmentIndex]['elements'][$elementIndex]['photos']
                );
            }
        } else {
            // Remover foto de ambiente
            if (isset($this->environments[$environmentIndex]['photos'][$photoIndex])) {
                $photoPath = $this->environments[$environmentIndex]['photos'][$photoIndex];
                Storage::disk('public')->delete($photoPath);
                unset($this->environments[$environmentIndex]['photos'][$photoIndex]);
                $this->environments[$environmentIndex]['photos'] = array_values(
                    $this->environments[$environmentIndex]['photos']
                );
            }
        }
    }

    // Google Maps
    public function searchAddress($address = null)
    {
        if (empty($address)) {
            return;
        }

        $mapsService = app(\App\Services\GoogleMapsService::class);
        $result = $mapsService->geocodeAddress($address);

        if ($result) {
            $this->coordinates = [
                'lat' => $result['latitude'],
                'lng' => $result['longitude'],
            ];
            $this->address = $result['formatted_address'] ?? $address;
            $this->dispatch('map-center', [
                'lat' => $result['latitude'],
                'lng' => $result['longitude'],
            ]);
        }
    }

    public function updateCoordinates($lat, $lng)
    {
        $this->coordinates = [
            'lat' => $lat,
            'lng' => $lng,
        ];
    }

    public function captureMapScreenshot()
    {
        $this->dispatch('capture-map-screenshot');
    }

    public function saveMapScreenshot($imageData)
    {
        // Decodificar base64 e salvar
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);
        
        $filename = 'inspections/maps/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $imageData);
        
        $this->map_image = $filename;
    }

    // Geração de QR Code
    public function generateQRCode($environmentIndex)
    {
        if (empty($this->environments[$environmentIndex]['google_drive_link'])) {
            return;
        }

        $link = $this->environments[$environmentIndex]['google_drive_link'];

        // Por enquanto, apenas salvar o link. A geração do QR code será feita via biblioteca externa
        // TODO: Instalar simplesoftwareio/simple-qrcode e implementar geração
        $this->environments[$environmentIndex]['qr_code_path'] = 'pending';
    }

    // Salvar vistoria
    public function save()
    {
        // Validar todas as etapas antes de salvar
        $allRules = [];
        
        // Coletar regras de todas as etapas
        for ($step = 1; $step <= 4; $step++) {
            $this->currentStep = $step;
            $stepRules = $this->rules();
            if (!empty($stepRules)) {
                $allRules = array_merge($allRules, $stepRules);
            }
        }
        
        // Restaurar etapa atual
        $this->currentStep = 5;
        
        // Validar se houver regras
        if (!empty($allRules)) {
            $this->validate($allRules);
        }

        DB::beginTransaction();
        try {
            if ($this->technicalInspection) {
                $inspection = $this->technicalInspection;
                $inspection->update([
                    'inspection_date' => $this->inspection_date,
                    'address' => $this->address,
                    'unit_area' => $this->unit_area,
                    'furniture_status' => $this->furniture_status,
                    'coordinates' => $this->coordinates,
                    'responsible_name' => $this->responsible_name,
                    'involved_parties' => $this->involved_parties,
                    'client_id' => $this->client_id,
                    'project_id' => $this->project_id,
                    'status' => 'completed',
                ]);

                // Processar mapa
                if ($this->map_image) {
                    if (is_string($this->map_image)) {
                        // Se já é um path, apenas atualizar
                        $inspection->update(['map_image_path' => $this->map_image]);
                    } else {
                        // Se é um arquivo upload, fazer store
                        /** @var \Illuminate\Http\UploadedFile $mapImage */
                        $mapImage = $this->map_image;
                        if (method_exists($mapImage, 'store')) {
                            $mapPath = $mapImage->store("inspections/{$inspection->id}", 'public');
                            $inspection->update(['map_image_path' => $mapPath]);
                        }
                    }
                }
            } else {
                $inspection = TechnicalInspection::create([
                    'number' => $this->number,
                    'inspection_date' => $this->inspection_date,
                    'address' => $this->address,
                    'unit_area' => $this->unit_area,
                    'furniture_status' => $this->furniture_status,
                    'coordinates' => $this->coordinates,
                    'responsible_name' => $this->responsible_name,
                    'involved_parties' => $this->involved_parties,
                    'client_id' => $this->client_id,
                    'project_id' => $this->project_id,
                    'user_id' => auth()->id(),
                    'status' => 'completed',
                ]);

                // Processar mapa
                if ($this->map_image) {
                    if (is_string($this->map_image)) {
                        // Se já é um path, apenas atualizar
                        $inspection->update(['map_image_path' => $this->map_image]);
                    } else {
                        // Se é um arquivo upload, fazer store
                        /** @var \Illuminate\Http\UploadedFile $mapImage */
                        $mapImage = $this->map_image;
                        if (method_exists($mapImage, 'store')) {
                            $mapPath = $mapImage->store("inspections/{$inspection->id}", 'public');
                            $inspection->update(['map_image_path' => $mapPath]);
                        }
                    }
                }
            }

            // Limpar ambientes existentes
            $inspection->environments()->delete();

            // Salvar ambientes e elementos
            $totalPhotos = 0;
            foreach ($this->environments as $envIndex => $envData) {
                // Mover fotos de temp para localização final
                $finalPhotos = [];
                if (isset($envData['photos'])) {
                    foreach ($envData['photos'] as $photoPath) {
                        if (str_contains($photoPath, 'temp/')) {
                            $newPath = str_replace('temp/', "{$inspection->id}/", $photoPath);
                            if (Storage::disk('public')->exists($photoPath)) {
                                Storage::disk('public')->move($photoPath, $newPath);
                                $finalPhotos[] = $newPath;
                                $totalPhotos++;
                            }
                        } else {
                            $finalPhotos[] = $photoPath;
                            $totalPhotos++;
                        }
                    }
                }

                $environment = InspectionEnvironment::create([
                    'technical_inspection_id' => $inspection->id,
                    'name' => $envData['name'],
                    'technical_notes' => $envData['technical_notes'] ?? '',
                    'photos' => $finalPhotos,
                    'videos' => $envData['videos'] ?? [],
                    'measurements' => $envData['measurements'] ?? '',
                    'google_drive_link' => $envData['google_drive_link'] ?? '',
                    'qr_code_path' => $envData['qr_code_path'] ?? null,
                    'sort_order' => $envIndex,
                ]);

                // Salvar elementos
                if (isset($envData['elements'])) {
                    foreach ($envData['elements'] as $elemIndex => $elemData) {
                        $finalElementPhotos = [];
                        if (isset($elemData['photos'])) {
                            foreach ($elemData['photos'] as $photoPath) {
                                if (str_contains($photoPath, 'temp/')) {
                                    $newPath = str_replace('temp/', "{$inspection->id}/", $photoPath);
                                    if (Storage::disk('public')->exists($photoPath)) {
                                        Storage::disk('public')->move($photoPath, $newPath);
                                        $finalElementPhotos[] = $newPath;
                                        $totalPhotos++;
                                    }
                                } else {
                                    $finalElementPhotos[] = $photoPath;
                                    $totalPhotos++;
                                }
                            }
                        }

                        InspectionElement::create([
                            'inspection_environment_id' => $environment->id,
                            'name' => $elemData['name'],
                            'technical_notes' => $elemData['technical_notes'] ?? '',
                            'condition_status' => $elemData['condition_status'],
                            'photos' => $finalElementPhotos,
                            'measurements' => $elemData['measurements'] ?? '',
                            'defects_identified' => $elemData['defects_identified'] ?? '',
                            'probable_causes' => $elemData['probable_causes'] ?? '',
                            'sort_order' => $elemIndex,
                        ]);
                    }
                }
            }

            // Atualizar contador de fotos
            $inspection->update(['total_photos_count' => $totalPhotos]);

            DB::commit();

            session()->flash('success', 'Vistoria técnica salva com sucesso!');
            return redirect()->route('technical-inspections.show', $inspection);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar vistoria: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        // Salvar como rascunho
        try {
            if ($this->technicalInspection) {
                $inspection = $this->technicalInspection;
                $inspection->update([
                    'inspection_date' => $this->inspection_date ?? now(),
                    'address' => $this->address ?? '',
                    'responsible_name' => $this->responsible_name ?? auth()->user()->name,
                    'status' => 'draft',
                ]);
            } else {
                $inspection = TechnicalInspection::create([
                    'number' => $this->number ?? TechnicalInspection::generateNumber(),
                    'inspection_date' => $this->inspection_date ?? now(),
                    'address' => $this->address ?? '',
                    'responsible_name' => $this->responsible_name ?? auth()->user()->name,
                    'user_id' => auth()->id(),
                    'status' => 'draft',
                ]);
            }

            session()->flash('success', 'Rascunho salvo com sucesso!');
            $this->technicalInspection = $inspection;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar rascunho: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Inicializar ambientes quando entrar na etapa 3
        if ($this->currentStep === 3) {
            $this->initializeEnvironments();
        }

        return view('livewire.technical-inspection-form');
    }
}
