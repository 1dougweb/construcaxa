<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Events\ProductChanged;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product;
    public $name;
    public $description;
    public $price;
    public $cost_price;
    public $sale_price;
    public $stock;
    public $min_stock;
    public $category_id;
    public $supplier_id;
    public $measurement_unit = 'unit';
    public $unit_label;
    public $featured_photo;
    public $featured_photo_path;
    public $showDeleteModal = false;
    public $stockToAdd = null;
    
    // Media Picker Variables
    public $showMediaPicker = false;
    public $mediaPickerTarget = null;
    
    public $categories;
    public $suppliers;
    public $unitTypes;

    protected $listeners = [
        'edit-product' => 'loadProduct',
        'reset-product-form' => 'resetForm',
        'media-selected' => 'handleMediaSelected',
        'open-media-picker' => 'openMediaPicker',
    ];

    protected function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'measurement_unit' => 'required|in:unit,weight,length',
            'featured_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ];
        
        // Stock e min_stock são obrigatórios apenas na criação
        if (!$this->product || !$this->product->id) {
            $rules['stock'] = 'required|numeric|min:0';
            $rules['min_stock'] = 'required|numeric|min:0';
        } else {
            // Na edição, são opcionais (mantém o valor atual se não informado)
            $rules['stock'] = 'nullable|numeric|min:0';
            $rules['min_stock'] = 'nullable|numeric|min:0';
        }
        
        return $rules;
    }

    protected $messages = [
        'featured_photo.image' => 'O arquivo deve ser uma imagem válida.',
        'featured_photo.mimes' => 'A imagem deve ser nos formatos: JPEG, PNG, JPG, GIF, WEBP ou AVIF.',
        'featured_photo.max' => 'A imagem deve ter no máximo 2MB.',
    ];

    public $productId = null;

    public function mount($product = null, $productId = null)
    {
        // SEMPRE resetar estado de fotos primeiro para evitar contaminação
        $this->featured_photo = null;
        $this->featured_photo_path = null;
        $this->showDeleteModal = false;
        
        // Se receber productId, carregar o produto
        if ($productId && !$product) {
            $product = Product::find($productId);
        }
        
        $this->product = $product;
        
        if ($product) {
            // Recarregar produto do banco para garantir dados atualizados
            $product->refresh();
            
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->cost_price = $product->cost_price;
            $this->sale_price = $product->sale_price;
            $this->stock = $product->stock;
            $this->min_stock = $product->min_stock;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->measurement_unit = $product->measurement_unit;
            $this->unit_label = $product->unit_label;
            
            // Carregar foto destacada APENAS se existir no produto atual
            if ($product->photos && is_array($product->photos) && count($product->photos) > 0) {
                $this->featured_photo_path = $product->photos[0];
            } else {
                // Garantir que está null se não há fotos
                $this->featured_photo_path = null;
            }
        } else {
            // Reset completo para novo produto
            $this->name = '';
            $this->description = '';
            $this->price = null;
            $this->cost_price = null;
            $this->sale_price = null;
            $this->stock = 0;
            $this->min_stock = 0;
            $this->category_id = null;
            $this->supplier_id = null;
            $this->measurement_unit = 'unit';
            $this->unit_label = Product::UNIT_TYPES[$this->measurement_unit]['unit'];
            $this->featured_photo_path = null;
        }

        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;
    }

    public function loadProduct($id)
    {
        // SEMPRE resetar estado completamente antes de carregar novo produto
        $this->resetForm();
        
        $product = Product::find($id);
        if ($product) {
            // Usar mount para garantir reset completo
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
        // Isso garante que apenas a nova foto será salva
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
                // Verificar se é caminho antigo (public/images) ou novo (storage)
                if (strpos($this->featured_photo_path, 'images/products/') === 0) {
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
        // Reset manual de todos os campos de formulário
        $this->name = '';
        $this->description = '';
        $this->price = null;
        $this->cost_price = null;
        $this->sale_price = null;
        $this->stock = 0;
        $this->min_stock = 0;
        $this->category_id = null;
        $this->supplier_id = null;
        $this->measurement_unit = 'unit';
        $this->unit_label = Product::UNIT_TYPES['unit']['unit'];
        $this->featured_photo = null;
        $this->featured_photo_path = null;
        $this->product = null;
        $this->productId = null;
        $this->showDeleteModal = false;
        $this->showMediaPicker = false;
        $this->mediaPickerTarget = null;
        $this->stockToAdd = null;

        // Recarregar coleções (NÃO chamar $this->reset() pois destruiria estas coleções)
        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;

        // Limpar erros de validação
        $this->resetValidation();
    }

    public function openMediaPicker($targetModel)
    {
        $this->mediaPickerTarget = $targetModel;
        $this->showMediaPicker = true;
    }

    public function handleMediaSelected($path, $url = null, $targetModel = null)
    {
        $target = $targetModel ?? $this->mediaPickerTarget;

        if ($target === 'featured_photo' || $this->mediaPickerTarget === 'featured_photo') {
            $this->featured_photo_path = $path;
            $this->featured_photo = null; // Limpar upload temporário se existia
            $this->showMediaPicker = false;
        }
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
        // 1. Validar primeiro fora da transação
        $validatedData = $this->validate();

        try {
            DB::beginTransaction();
            
            // Processamento de imagem
            if ($this->featured_photo) {
                $filename = time() . '_' . uniqid() . '.' . $this->featured_photo->getClientOriginalExtension();
                $path = $this->featured_photo->storeAs('images/products', $filename, ['disk' => 'real_public']);
                $photoPath = $path;
                
                if ($this->product && $this->product->id) {
                    $this->product->refresh();
                    $currentPhotos = $this->product->photos ?? [];
                    if ($this->featured_photo_path && $this->featured_photo_path !== $photoPath) {
                        if (str_starts_with($this->featured_photo_path, 'images/products/')) {
                            $oldPath = public_path($this->featured_photo_path);
                            if (File::exists($oldPath)) {
                                File::delete($oldPath);
                            }
                        } else {
                            Storage::disk('public')->delete($this->featured_photo_path);
                        }
                        $currentPhotos = array_filter($currentPhotos, function($photo) {
                            return $photo !== $this->featured_photo_path;
                        });
                    }
                    array_unshift($currentPhotos, $photoPath);
                    $validatedData['photos'] = array_values(array_unique($currentPhotos));
                } else {
                    $validatedData['photos'] = [$photoPath];
                }
            } elseif ($this->product && $this->product->id) {
                $this->product->refresh();
                $currentPhotos = $this->product->photos ?? [];
                if ($this->featured_photo_path) {
                    $currentPhotos = array_filter($currentPhotos, function($photo) {
                        return $photo !== $this->featured_photo_path;
                    });
                    array_unshift($currentPhotos, $this->featured_photo_path);
                    $validatedData['photos'] = array_values($currentPhotos);
                } elseif (!empty($currentPhotos)) {
                    $validatedData['photos'] = $currentPhotos;
                }
            } elseif (!$this->product && $this->featured_photo_path) {
                $validatedData['photos'] = [$this->featured_photo_path];
            }

            // Normalização de campos numéricos
            if ($this->product && $this->product->id) {
                if (!isset($validatedData['stock']) || $validatedData['stock'] === null) {
                    $this->product->refresh();
                    $validatedData['stock'] = $this->product->stock;
                }
                if (!isset($validatedData['min_stock']) || $validatedData['min_stock'] === null) {
                    $this->product->refresh();
                    $validatedData['min_stock'] = $this->product->min_stock;
                }
            }

            $validatedData['unit_label'] = \App\Models\Product::UNIT_TYPES[$validatedData['measurement_unit']]['unit'];

            $action = $this->product ? 'updated' : 'created';
            
            if ($this->product) {
                $this->product->update($validatedData);
                $productName = $this->product->name;
            } else {
                $validatedData['sku'] = $this->generateSKU($validatedData['name']);
                $this->product = \App\Models\Product::create($validatedData);
                $productName = $this->product->name;
            }

            DB::commit();

            try {
                broadcast(new \App\Events\ProductChanged($this->product->id, $action, '', $productName));
            } catch (\Exception $e) {
                \Log::warning('Erro de broadcast: ' . $e->getMessage());
            }

            $successMsg = ($action === 'updated') 
                ? "Produto atualizado com sucesso!" 
                : "Produto cadastrado com sucesso!";

            \Log::info("Produto salvo: " . $productName);

            // Garantir que a UI receba a notificação e feche o offcanvas via JS puro
            $this->js("
                if (window.showNotification) {
                    window.showNotification('{$successMsg}', 'success');
                } else {
                    alert('{$successMsg}');
                }
                
                if (typeof window.closeOffcanvas === 'function') {
                    window.closeOffcanvas('product-offcanvas');
                }
            ");

            // Atualizar listas
            $this->dispatch('refresh-products');
            
            // Limpar form
            $this->resetForm();

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            \Log::error('Erro ao salvar produto: ' . $e->getMessage());
            
            $errorMsg = 'Erro ao salvar. Verifique os logs.';
            $this->js("
                if (window.showNotification) {
                    window.showNotification('{$errorMsg}', 'error');
                } else {
                    alert('{$errorMsg}');
                }
            ");
        }
    }

    public function addStock()
    {
        if (!$this->product || !$this->product->id) {
            session()->flash('error', 'É necessário salvar o produto antes de adicionar estoque.');
            return;
        }

        $this->validate([
            'stockToAdd' => 'required|numeric|min:0.01',
        ], [
            'stockToAdd.required' => 'A quantidade é obrigatória.',
            'stockToAdd.numeric' => 'A quantidade deve ser um número.',
            'stockToAdd.min' => 'A quantidade deve ser maior que zero.',
        ]);

        DB::beginTransaction();
        try {
            // Recarregar produto do banco para garantir dados atualizados
            $this->product->refresh();
            $previousStock = $this->product->stock;
            
            // Incrementar estoque
            $this->product->increment('stock', $this->stockToAdd);
            
            // Criar registro de movimentação
            StockMovement::create([
                'product_id' => $this->product->id,
                'user_id' => auth()->id(),
                'type' => 'entrada',
                'quantity' => $this->stockToAdd,
                'previous_stock' => $previousStock,
                'new_stock' => $this->product->stock,
                'notes' => 'Alimentação de estoque via formulário',
            ]);
            
            // Atualizar estoque no formulário
            $this->stock = $this->product->stock;
            
            DB::commit();
            
            $message = "Estoque adicionado com sucesso! Novo estoque: {$this->product->stock} {$this->product->unit_label}";
            session()->flash('success', $message);
            
            // Limpar campo
            $this->stockToAdd = null;
            
            // Atualizar estoque no formulário
            $this->stock = $this->product->stock;
            
            // Disparar evento para atualizar lista
            $this->dispatch('refresh-products')->to(ProductList::class);
            
            // Mostrar notificação
            $escapedMessage = addslashes($message);
            $this->js("
                (function() {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('{$escapedMessage}', 'success', 4000);
                    }
                })();
            ");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao adicionar estoque: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $errorMessage = 'Erro ao adicionar estoque: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            $this->js("
                (function() {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('{$errorMessage}', 'error', 5000);
                    }
                })();
            ");
        }
    }

    public function render()
    {
        // Garantir que propriedades de dados sempre estejam inicializadas
        if (!isset($this->categories) || $this->categories === null) {
            $this->categories = Category::orderBy('name')->get();
        }
        if (!isset($this->suppliers) || $this->suppliers === null) {
            $this->suppliers = Supplier::orderBy('company_name')->get();
        }
        if (!isset($this->unitTypes) || $this->unitTypes === null) {
            $this->unitTypes = Product::UNIT_TYPES;
        }
        
        return view('livewire.product-form');
    }
}
