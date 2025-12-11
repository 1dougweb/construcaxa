<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Events\ProductChanged;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $min_stock;
    public $category_id;
    public $supplier_id;
    public $measurement_unit = 'unit';
    public $unit_label;
    public $featured_photo;
    public $featured_photo_path;
    public $showDeleteModal = false;
    
    public $categories;
    public $suppliers;
    public $unitTypes;

    protected $listeners = [
        'edit-product' => 'loadProduct',
        'reset-product-form' => 'resetForm',
    ];

    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:0',
        'min_stock' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'measurement_unit' => 'required|in:unit,weight,length',
        'featured_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
    ];

    protected $messages = [
        'featured_photo.image' => 'O arquivo deve ser uma imagem válida.',
        'featured_photo.mimes' => 'A imagem deve ser nos formatos: JPEG, PNG, JPG, GIF, WEBP ou AVIF.',
        'featured_photo.max' => 'A imagem deve ter no máximo 2MB.',
    ];

    public $productId = null;

    public function mount($product = null, $productId = null)
    {
        // Se receber productId, carregar o produto
        if ($productId && !$product) {
            $product = Product::find($productId);
        }
        
        $this->product = $product;
        
        if ($product) {
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->min_stock = $product->min_stock;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->measurement_unit = $product->measurement_unit;
            $this->unit_label = $product->unit_label;
            
            // Carregar foto destacada se existir
            if ($product->photos && is_array($product->photos) && count($product->photos) > 0) {
                $this->featured_photo_path = $product->photos[0];
            }
        } else {
            $this->unit_label = Product::UNIT_TYPES[$this->measurement_unit]['unit'];
        }

        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;
    }

    public function loadProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->mount($product);
        }
    }

    public function updatedMeasurementUnit($value)
    {
        // Define a unidade padrão baseada no tipo selecionado
        $this->unit_label = Product::UNIT_TYPES[$value]['unit'];
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
            // Se é um produto existente, deletar do storage e atualizar
            if ($this->product) {
                Storage::disk('public')->delete($this->featured_photo_path);
                
                // Remover do array de fotos
                $photos = $this->product->photos ?? [];
                $photos = array_filter($photos, function($photo) {
                    return $photo !== $this->featured_photo_path;
                });
                $this->product->update(['photos' => array_values($photos)]);
            }
            // Se é um upload temporário, apenas limpar
        }
        
        $this->featured_photo_path = null;
        $this->featured_photo = null;
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->price = 0;
        $this->stock = 0;
        $this->min_stock = 0;
        $this->category_id = null;
        $this->supplier_id = null;
        $this->measurement_unit = 'unit';
        $this->unit_label = Product::UNIT_TYPES['unit']['unit'];
        $this->featured_photo = null;
        $this->featured_photo_path = null;
        $this->product = null;
        $this->showDeleteModal = false;
    }

    private function generateSKU($name)
    {
        // Remove acentos e caracteres especiais
        $name = preg_replace('/[^A-Za-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $name));
        
        // Converte para maiúsculas e pega os primeiros 3 caracteres
        $prefix = strtoupper(substr($name, 0, 3));
        
        // Se não tiver 3 caracteres, completa com X
        $prefix = str_pad($prefix, 3, 'X');
        
        // Gera um número sequencial de 4 dígitos
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $sequence = $lastProduct ? str_pad(($lastProduct->id + 1), 4, '0', STR_PAD_LEFT) : '0001';
        
        // Combina para formar o SKU
        $sku = $prefix . $sequence;
        
        // Verifica se o SKU já existe e adiciona um sufixo se necessário
        $counter = 1;
        $originalSku = $sku;
        while (Product::where('sku', $sku)->exists()) {
            $sku = $originalSku . chr(64 + $counter); // Adiciona A, B, C, etc.
            $counter++;
        }
        
        return $sku;
    }

    public function save()
    {
        $validatedData = $this->validate();
        
        // Define a unidade padrão baseada no tipo selecionado
        $validatedData['unit_label'] = Product::UNIT_TYPES[$validatedData['measurement_unit']]['unit'];
        
        // Upload da foto destacada
        if ($this->featured_photo) {
            $photoPath = $this->featured_photo->store('products/photos', 'public');
            
            // Se já existe foto destacada, deletar a antiga
            if ($this->featured_photo_path) {
                Storage::disk('public')->delete($this->featured_photo_path);
            }
            
            // Adicionar foto ao array de fotos
            $photos = $this->product && $this->product->photos ? $this->product->photos : [];
            // Remove a foto antiga se existir
            $photos = array_filter($photos, function($photo) {
                return $photo !== $this->featured_photo_path;
            });
            array_unshift($photos, $photoPath); // Adiciona no início
            $validatedData['photos'] = array_values($photos);
        } elseif ($this->product && $this->featured_photo_path) {
            // Se não há upload novo mas há foto destacada existente, manter o array de fotos
            $photos = $this->product->photos ?? [];
            // Garantir que a foto destacada está no início
            $photos = array_filter($photos, function($photo) {
                return $photo !== $this->featured_photo_path;
            });
            array_unshift($photos, $this->featured_photo_path);
            $validatedData['photos'] = array_values($photos);
        }
        
        $message = '';
        $action = $this->product ? 'updated' : 'created';
        $productName = $this->product ? $this->product->name : $validatedData['name'];

        if ($this->product) {
            // Atualização
            $this->product->update($validatedData);
            $message = 'Produto atualizado com sucesso.';
            session()->flash('success', $message);
        } else {
            // Criação
            // Gera o SKU automaticamente apenas para novos produtos
            $validatedData['sku'] = $this->generateSKU($validatedData['name']);
            $this->product = Product::create($validatedData);
            $productName = $this->product->name;
            $message = 'Produto criado com sucesso.';
            session()->flash('success', $message);
        }
        
        if ($this->product && $this->product->id) {
            // Disparar evento de broadcast de forma síncrona
            broadcast(new ProductChanged(
                $this->product->id,
                $action,
                $message,
                $productName
            ))->toOthers();
        }

        // Atualizar lista em tempo real via Livewire
        $this->dispatch('refresh-products')->to(ProductList::class);

        // Executar JavaScript diretamente para garantir fechamento e notificação
        $escapedMessage = addslashes($message);
        $this->js("
            (function() {
                if (typeof closeOffcanvas === 'function') {
                    closeOffcanvas('product-offcanvas');
                }
                if (typeof window.showNotification === 'function') {
                    window.showNotification('{$escapedMessage}', 'success', 4000);
                }
                if (typeof window.Livewire !== 'undefined') {
                    window.Livewire.dispatch('refresh-products');
                }
            })();
        ");

        // Emitir evento para outros listeners (fallback)
        $this->dispatch('product-saved', [
            'message' => $message,
            'type' => 'success'
        ]);

        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
