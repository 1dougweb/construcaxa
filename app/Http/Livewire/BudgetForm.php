<?php

namespace App\Http\Livewire;

use App\Models\ProjectBudget;
use App\Models\Client;
use App\Models\Product;
use App\Models\Service;
use App\Models\LaborType;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BudgetForm extends Component
{
    use WithFileUploads;

    public $budget = null;
    public $client_id;
    public $version = 1;
    public $discount = 0;
    public $status = 'pending';
    public $notes;
    public $photos = [];
    public $tempPhotos = [];
    public $showDeleteModal = false;
    public $photoToDelete = null;
    
    public $items = [];
    public $itemIndex = 0;
    
    protected $listeners = [];
    
    public $clients;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'version' => 'required|integer|min:1',
        'discount' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,under_review,approved,rejected,cancelled',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.item_type' => 'required|in:product,service,labor',
        'items.*.product_id' => 'nullable|required_if:items.*.item_type,product|exists:products,id',
        'items.*.service_id' => 'nullable|required_if:items.*.item_type,service|exists:services,id',
        'items.*.labor_type_id' => 'nullable|required_if:items.*.item_type,labor|exists:labor_types,id',
        'items.*.description' => 'required|string|max:255',
        'items.*.quantity' => 'nullable|numeric|min:0',
        'items.*.hours' => 'nullable|numeric|min:0',
        'items.*.overtime_hours' => 'nullable|numeric|min:0',
        'items.*.unit_price' => 'required|numeric|min:0',
        'tempPhotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
    ];

    public function mount($budget = null)
    {
        $this->clients = Client::active()->orderBy('name')->get();
        // Não carregar todos os produtos/serviços/labor types - serão buscados via AJAX
        
        if ($budget) {
            $this->budget = $budget;
            $this->client_id = $budget->client_id;
            $this->version = $budget->version;
            $this->discount = $budget->discount;
            $this->status = $budget->status;
            $this->notes = $budget->notes;
            $this->photos = $budget->photos ?? [];
            
            foreach ($budget->items as $item) {
                $this->items[] = [
                    'item_type' => $item->item_type,
                    'product_id' => $item->product_id,
                    'service_id' => $item->service_id,
                    'labor_type_id' => $item->labor_type_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'hours' => $item->hours,
                    'overtime_hours' => $item->overtime_hours,
                    'unit_price' => $item->unit_price,
                    'product_search' => '',
                    'service_search' => '',
                    'labor_search' => '',
                    'product_search_results' => [],
                    'service_search_results' => [],
                    'labor_search_results' => [],
                ];
            }
            $this->itemIndex = count($this->items);
        } else {
            // Inicializar com pelo menos um item vazio
            $this->addItem('product');
        }
    }


    public function addItem($type)
    {
        $item = [
            'item_type' => $type,
            'product_id' => null,
            'service_id' => null,
            'labor_type_id' => null,
            'description' => '',
            'quantity' => $type !== 'labor' ? 1 : null,
            'hours' => $type === 'labor' ? 1 : null,
            'overtime_hours' => null,
            'unit_price' => 0,
            'product_search' => '',
            'service_search' => '',
            'labor_search' => '',
        ];
        
        $this->items[] = $item;
        $this->itemIndex++;
        
        // Forçar atualização da view
        $this->dispatch('item-added');
    }
    
    public function updatedItems($value, $key)
    {
        // Apenas processar campos de busca - retornar cedo se não for campo de busca
        if (!str_contains($key, '_search')) {
            return;
        }
        
        $parts = explode('.', $key);
        if (count($parts) < 2) {
            return;
        }
        
        $index = (int) $parts[1];
        if (!isset($this->items[$index])) {
            return;
        }
        
        // Usar dispatch para garantir que a view seja atualizada
        if (str_contains($key, 'product_search')) {
            $this->searchProduct($index);
            $this->dispatch('product-search-updated', $index);
        } elseif (str_contains($key, 'service_search')) {
            $this->searchService($index);
            $this->dispatch('service-search-updated', $index);
        } elseif (str_contains($key, 'labor_search')) {
            $this->searchLabor($index);
            $this->dispatch('labor-search-updated', $index);
        }
    }
    
    private function searchProduct($index)
    {
        $search = trim($this->items[$index]['product_search'] ?? '');
        
        if (strlen($search) < 2) {
            $this->items[$index]['product_search_results'] = [];
            $this->items = $this->items; // Forçar atualização do array
            return;
        }
        
        // Busca otimizada com select apenas dos campos necessários e cache
        $results = Product::select('id', 'name', 'sku', 'price', 'photos')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get();
        
        $this->items[$index]['product_search_results'] = $results->map(function($product) {
            $photos = $product->photos ?? [];
            $firstPhoto = null;
            if (is_array($photos) && count($photos) > 0) {
                $firstPhoto = $photos[0];
            }
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price ?? 0,
                'photo' => $firstPhoto,
            ];
        })->toArray();
        
        // Forçar atualização do array para o Livewire detectar a mudança
        $this->items = $this->items;
    }
    
    private function searchService($index)
    {
        $search = trim($this->items[$index]['service_search'] ?? '');
        
        if (strlen($search) < 2) {
            $this->items[$index]['service_search_results'] = [];
            $this->items = $this->items; // Forçar atualização do array
            return;
        }
        
        $results = Service::select('id', 'name', 'default_price', 'unit_type')
            ->where('name', 'like', "%{$search}%")
            ->where('is_active', true)
            ->limit(5)
            ->get();
        
        $this->items[$index]['service_search_results'] = $results->map(function($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'default_price' => $service->default_price ?? 0,
                'unit_type' => $service->unit_type ?? 'hour',
            ];
        })->toArray();
        
        // Forçar atualização do array
        $this->items = $this->items;
    }
    
    private function searchLabor($index)
    {
        $search = trim($this->items[$index]['labor_search'] ?? '');
        
        if (strlen($search) < 2) {
            $this->items[$index]['labor_search_results'] = [];
            $this->items = $this->items; // Forçar atualização do array
            return;
        }
        
        $results = LaborType::select('id', 'name', 'hourly_rate', 'overtime_rate')
            ->where('name', 'like', "%{$search}%")
            ->where('is_active', true)
            ->limit(5)
            ->get();
        
        $this->items[$index]['labor_search_results'] = $results->map(function($labor) {
            return [
                'id' => $labor->id,
                'name' => $labor->name,
                'hourly_rate' => $labor->hourly_rate ?? 0,
                'overtime_rate' => $labor->overtime_rate ?? 0,
            ];
        })->toArray();
        
        // Forçar atualização do array
        $this->items = $this->items;
    }

    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }
    
    public function selectProduct($index, $productId)
    {
        if (!isset($this->items[$index])) {
            return;
        }
        
        $product = Product::find($productId);
        if ($product) {
            $this->items[$index]['product_id'] = $product->id;
            $this->items[$index]['description'] = $product->name;
            $this->items[$index]['quantity'] = 1;
            $this->items[$index]['unit_price'] = $product->price ?? 0;
            $this->items[$index]['product_search'] = '';
            $this->items[$index]['product_search_results'] = [];
        }
    }
    
    public function selectService($index, $serviceId)
    {
        if (!isset($this->items[$index])) {
            return;
        }
        
        $service = Service::select('id', 'name', 'default_price')->find($serviceId);
        if ($service) {
            $this->items[$index]['service_id'] = $service->id;
            $this->items[$index]['description'] = $service->name;
            $this->items[$index]['unit_price'] = $service->default_price ?? 0;
            $this->items[$index]['service_search'] = '';
            $this->items[$index]['service_search_results'] = [];
            
            // Forçar atualização do array
            $this->items = $this->items;
        }
    }
    
    public function selectLabor($index, $laborId)
    {
        if (!isset($this->items[$index])) {
            return;
        }
        
        $labor = LaborType::select('id', 'name', 'hourly_rate')->find($laborId);
        if ($labor) {
            $this->items[$index]['labor_type_id'] = $labor->id;
            $this->items[$index]['description'] = $labor->name;
            $this->items[$index]['unit_price'] = $labor->hourly_rate ?? 0;
            $this->items[$index]['labor_search'] = '';
            $this->items[$index]['labor_search_results'] = [];
            
            // Forçar atualização do array
            $this->items = $this->items;
        }
    }

    public function confirmDeletePhoto($index)
    {
        $this->photoToDelete = $index;
        $this->showDeleteModal = true;
    }

    public function cancelDeletePhoto()
    {
        $this->showDeleteModal = false;
        $this->photoToDelete = null;
    }

    public function deletePhoto()
    {
        if ($this->photoToDelete !== null) {
            if ($this->photoToDelete < count($this->photos)) {
                // Existing photo
                $photoPath = $this->photos[$this->photoToDelete];
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                unset($this->photos[$this->photoToDelete]);
                $this->photos = array_values($this->photos);
            } else {
                // Temp photo
                $tempIndex = $this->photoToDelete - count($this->photos);
                unset($this->tempPhotos[$tempIndex]);
                $this->tempPhotos = array_values($this->tempPhotos);
            }
        }
        $this->cancelDeletePhoto();
    }

    public function save()
    {
        $this->validate();

        // Calculate subtotal and total
        $subtotal = collect($this->items)->sum(function ($item) {
            if ($item['item_type'] === 'labor') {
                $hours = ($item['hours'] ?? 0);
                $overtimeHours = ($item['overtime_hours'] ?? 0);
                return ($hours * ($item['unit_price'] ?? 0)) + ($overtimeHours * ($item['unit_price'] ?? 0) * 1.5);
            } else {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        });
        $total = $subtotal - ($this->discount ?? 0);

        DB::beginTransaction();
        try {
            // Store uploaded photos
            $uploadedPhotos = [];
            foreach ($this->tempPhotos as $tempPhoto) {
                $path = $tempPhoto->store('budget-photos', 'public');
                $uploadedPhotos[] = $path;
            }
            $allPhotos = array_merge($this->photos, $uploadedPhotos);

            $data = [
                'client_id' => $this->client_id,
                'project_id' => null,
                'version' => $this->version,
                'subtotal' => $subtotal,
                'discount' => $this->discount ?? 0,
                'total' => $total,
                'status' => $this->status,
                'notes' => $this->notes,
                'photos' => $allPhotos,
                'sent_at' => $this->status === 'under_review' ? now() : null,
                'approved_at' => $this->status === 'approved' ? now() : null,
                'approved_by' => $this->status === 'approved' ? auth()->id() : null,
            ];

            if ($this->budget) {
                $this->budget->update($data);
                $budget = $this->budget;
            } else {
                $budget = ProjectBudget::create($data);
            }

            // Delete old items
            if ($this->budget) {
                $budget->items()->delete();
            }

            // Create budget items
            foreach ($this->items as $itemData) {
                $itemTotal = 0;
                if ($itemData['item_type'] === 'labor') {
                    $hours = ($itemData['hours'] ?? 0);
                    $overtimeHours = ($itemData['overtime_hours'] ?? 0);
                    $itemTotal = ($hours * ($itemData['unit_price'] ?? 0)) + ($overtimeHours * ($itemData['unit_price'] ?? 0) * 1.5);
                } else {
                    $itemTotal = ($itemData['quantity'] ?? 0) * ($itemData['unit_price'] ?? 0);
                }
                
                $budget->items()->create([
                    'item_type' => $itemData['item_type'],
                    'product_id' => $itemData['product_id'] ?? null,
                    'service_id' => $itemData['service_id'] ?? null,
                    'labor_type_id' => $itemData['labor_type_id'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'] ?? 0,
                    'hours' => $itemData['hours'] ?? 0,
                    'overtime_hours' => $itemData['overtime_hours'] ?? 0,
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemTotal,
                ]);
            }

            // Create project automatically if budget is approved
            if ($this->status === 'approved' && !$this->budget) {
                $project = Project::createFromApprovedBudget($budget);
            }

            DB::commit();
            
            $message = $this->budget ? 'Orçamento atualizado com sucesso!' : 'Orçamento criado com sucesso!';
            session()->flash('success', $message);
            
            return redirect()->route('budgets.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar orçamento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.budget-form');
    }
}
