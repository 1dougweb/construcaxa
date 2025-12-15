<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;
use Illuminate\Support\Collection;

class StockMovementForm extends Component
{
    public $type = 'entrada';
    public $quantity;
    public $notes;
    public $search = '';
    public $selectedProduct = null;
    public $productId = null;
    public Collection $availableProducts;

    protected $rules = [
        'type' => 'required|in:entrada,saida',
        'quantity' => 'required|numeric|min:0.01',
        'notes' => 'nullable|string',
        'selectedProduct' => 'required|exists:products,id',
    ];

    public function mount($productId = null)
    {
        $this->availableProducts = collect();
        if ($productId) {
            $this->productId = $productId;
            $this->selectedProduct = $productId;
            $product = Product::find($productId);
            if ($product) {
                $this->search = $product->name;
            }
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->availableProducts = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->availableProducts = collect();
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = $productId;
        $this->productId = $productId;
        $product = Product::find($productId);
        if ($product) {
            $this->search = $product->name;
        }
        $this->availableProducts = collect();
    }

    public function loadProduct($productId)
    {
        $this->productId = $productId;
        $this->selectedProduct = $productId;
        $product = Product::find($productId);
        if ($product) {
            $this->search = $product->name;
        }
        $this->availableProducts = collect();
    }

    public function resetForm()
    {
        $this->reset(['type', 'quantity', 'notes', 'search', 'selectedProduct']);
        $this->productId = null;
        $this->availableProducts = collect();
    }

    public function save()
    {
        $this->validate();

        $product = Product::findOrFail($this->selectedProduct);

        if ($this->type === 'saida' && $product->stock < $this->quantity) {
            $this->addError('quantity', 'Quantidade maior que o estoque disponível');
            return;
        }

        $previousStock = $product->stock;
        $newStock = $this->type === 'entrada' 
            ? $previousStock + $this->quantity 
            : $previousStock - $this->quantity;

        StockMovement::create([
            'product_id' => $this->selectedProduct,
            'user_id' => auth()->id(),
            'type' => $this->type,
            'quantity' => $this->quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'notes' => $this->notes,
        ]);

        $product->update(['stock' => $newStock]);

        $this->dispatch('movementCreated');
        $this->dispatch('stock-movement-saved', ['message' => 'Movimentação de estoque registrada com sucesso!']);
        
        // Reset form but keep product if it was pre-selected
        $this->reset(['type', 'quantity', 'notes']);
        if (!$this->productId) {
            $this->reset(['search', 'selectedProduct']);
        } else {
            $product = Product::find($this->productId);
            if ($product) {
                $this->search = $product->name;
                $this->selectedProduct = $this->productId;
            }
        }
    }

    public function render()
    {
        return view('livewire.stock-movement-form');
    }
}
