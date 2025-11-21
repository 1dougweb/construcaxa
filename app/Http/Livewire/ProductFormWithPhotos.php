<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductFormWithPhotos extends Component
{
    use WithFileUploads;

    public $product;
    public $name;
    public $sku;
    public $description;
    public $price;
    public $stock;
    public $min_stock;
    public $supplier_id;
    public $category_id;
    public $measurement_unit = 'unit';
    public $unit_label = 'un';
    public $photos = [];
    public $existingPhotos = [];
    public $categories;
    public $suppliers;

    protected function rules()
    {
        $skuRule = 'nullable|string|max:255';
        
        if ($this->product) {
            $skuRule .= '|unique:products,sku,' . $this->product->id;
        } else {
            $skuRule .= '|unique:products,sku';
        }
        
        return [
            'name' => 'required|string|max:255',
            'sku' => $skuRule,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'measurement_unit' => 'required|in:unit,weight,length',
            'unit_label' => 'required|string|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome do produto é obrigatório.',
        'sku.unique' => 'Este SKU já está em uso.',
        'price.required' => 'O preço é obrigatório.',
        'price.numeric' => 'O preço deve ser um número.',
        'price.min' => 'O preço deve ser maior ou igual a zero.',
        'stock.required' => 'O estoque é obrigatório.',
        'stock.numeric' => 'O estoque deve ser um número.',
        'stock.min' => 'O estoque deve ser maior ou igual a zero.',
        'min_stock.required' => 'O estoque mínimo é obrigatório.',
        'min_stock.numeric' => 'O estoque mínimo deve ser um número.',
        'min_stock.min' => 'O estoque mínimo deve ser maior ou igual a zero.',
        'category_id.required' => 'A categoria é obrigatória.',
        'category_id.exists' => 'A categoria selecionada não é válida.',
        'supplier_id.exists' => 'O fornecedor selecionado não é válido.',
        'measurement_unit.required' => 'A unidade de medida é obrigatória.',
        'measurement_unit.in' => 'A unidade de medida deve ser: unidade, peso ou metragem.',
        'unit_label.required' => 'O rótulo da unidade é obrigatório.',
        'photos.*.image' => 'Todos os arquivos devem ser imagens.',
        'photos.*.mimes' => 'As imagens devem ser dos tipos: jpeg, png, jpg, gif.',
        'photos.*.max' => 'Cada imagem deve ter no máximo 2MB.',
    ];

    public function mount($product = null)
    {
        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('name')->get();
        
        if ($product) {
            $this->product = $product;
            $this->name = $product->name;
            $this->sku = $product->sku;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->min_stock = $product->min_stock;
            $this->supplier_id = $product->supplier_id;
            $this->category_id = $product->category_id;
            $this->measurement_unit = $product->measurement_unit;
            $this->unit_label = $product->unit_label;
            $this->existingPhotos = $product->photos ?? [];
        }
    }

    public function updatedMeasurementUnit()
    {
        $unitTypes = Product::UNIT_TYPES;
        if (isset($unitTypes[$this->measurement_unit])) {
            $this->unit_label = $unitTypes[$this->measurement_unit]['unit'];
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
            if ($this->product) {
                $this->product->update(['photos' => $this->existingPhotos]);
            }
        }
    }

    public function generateSku()
    {
        if ($this->category_id && $this->name) {
            $category = Category::find($this->category_id);
            $categoryPrefix = strtoupper(substr($category->name, 0, 3));
            $namePrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $this->name), 0, 3));
            $randomNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            $this->sku = $categoryPrefix . $namePrefix . $randomNumber;
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
                    $path = $photo->store('products/photos', 'public');
                    $allPhotos[] = $path;
                }
            }

            $data = [
                'name' => $this->name,
                'sku' => $this->sku,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'min_stock' => $this->min_stock,
                'supplier_id' => $this->supplier_id,
                'category_id' => $this->category_id,
                'measurement_unit' => $this->measurement_unit,
                'unit_label' => $this->unit_label,
                'photos' => $allPhotos,
            ];

            if ($this->product) {
                $this->product->update($data);
                $successMessage = 'Produto atualizado com sucesso!';
                $redirectRoute = 'products.index';
            } else {
                Product::create($data);
                $successMessage = 'Produto cadastrado com sucesso!';
                $redirectRoute = 'products.index';
            }

            $this->notify($successMessage, 'success');
            
            return redirect()->route($redirectRoute);
                
        } catch (\Exception $e) {
            $this->notify('Erro ao salvar produto: ' . $e->getMessage(), 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.product-form-with-photos');
    }
}