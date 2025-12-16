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
    
    public $categories;
    public $suppliers;
    public $unitTypes;

    protected $listeners = [
        'edit-product' => 'loadProduct',
        'reset-product-form' => 'resetForm',
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
        // Reset completo de TODOS os campos para evitar contaminação entre produtos
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
        
        // Garantir que propriedades de dados sempre existam
        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;
        
        // Forçar reset do componente Livewire para limpar qualquer estado residual
        $this->reset();
        
        // Re-inicializar após reset para garantir que dados estejam disponíveis
        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;
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
        try {
            DB::beginTransaction();
            
            $validatedData = $this->validate();
            
            // Define a unidade padrão baseada no tipo selecionado
            $validatedData['unit_label'] = Product::UNIT_TYPES[$validatedData['measurement_unit']]['unit'];
            
            // Se estiver editando e stock/min_stock não foram informados, manter valores atuais
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
            
            // Upload da foto destacada
            // IMPORTANTE: Sempre trabalhar com o produto atual do banco, não com estado em memória
            if ($this->featured_photo) {
                // Nova foto foi enviada - salvar usando Storage do Laravel
                $filename = time() . '_' . uniqid() . '.' . $this->featured_photo->getClientOriginalExtension();
                $path = $this->featured_photo->storeAs('products', $filename, 'public');
                
                // Caminho relativo para salvar no banco (storage/products/filename.jpg)
                $photoPath = $path;
                
                // Se é atualização e havia foto antiga, deletar do storage
                if ($this->product && $this->product->id) {
                // Recarregar produto do banco para garantir dados atualizados
                $this->product->refresh();
                $currentPhotos = $this->product->photos ?? [];
                
                // Deletar foto antiga se existir e for diferente da nova
                if ($this->featured_photo_path && $this->featured_photo_path !== $photoPath) {
                    // Verificar se é caminho antigo (public/images) ou novo (storage)
                    if (strpos($this->featured_photo_path, 'images/products/') === 0) {
                        // Caminho antigo - deletar de public
                        $oldPath = public_path($this->featured_photo_path);
                        if (File::exists($oldPath)) {
                            File::delete($oldPath);
                        }
                    } else {
                        // Caminho novo - deletar do storage
                        Storage::disk('public')->delete($this->featured_photo_path);
                    }
                    // Remover do array também
                    $currentPhotos = array_filter($currentPhotos, function($photo) {
                        return $photo !== $this->featured_photo_path;
                    });
                }
                
                // Adicionar nova foto no início
                array_unshift($currentPhotos, $photoPath);
                $validatedData['photos'] = array_values(array_unique($currentPhotos));
            } else {
                // Novo produto
                $validatedData['photos'] = [$photoPath];
            }
        } elseif ($this->product && $this->product->id) {
            // Não há upload novo - preservar fotos existentes do banco
            $this->product->refresh();
            $currentPhotos = $this->product->photos ?? [];
            
            if ($this->featured_photo_path) {
                // Se há featured_photo_path definido, garantir que está no início
                $currentPhotos = array_filter($currentPhotos, function($photo) {
                return $photo !== $this->featured_photo_path;
            });
                array_unshift($currentPhotos, $this->featured_photo_path);
                $validatedData['photos'] = array_values($currentPhotos);
            } elseif (!empty($currentPhotos)) {
                // Preservar array existente como está
                $validatedData['photos'] = $currentPhotos;
            }
            // Se não há featured_photo_path e não há fotos, não definir photos (mantém vazio)
        }
        // Para novo produto sem foto, não definir photos (será null/vazio)
        
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
            
            DB::commit();
            
            if ($this->product && $this->product->id) {
                // Disparar evento de broadcast de forma síncrona
                // Usar try-catch para evitar erros se o WebSocket não estiver disponível
                try {
                    broadcast(new ProductChanged(
                        $this->product->id,
                        $action,
                        $message,
                        $productName
                    ));
                } catch (\Exception $e) {
                    // Log do erro mas não interromper o fluxo
                    \Log::warning('Erro ao fazer broadcast de ProductChanged: ' . $e->getMessage());
                }
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
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao salvar produto: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Erro ao salvar produto: ' . $e->getMessage());
            $this->js("
                (function() {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Erro ao salvar produto. Verifique os logs.', 'error', 5000);
                    }
                })();
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
