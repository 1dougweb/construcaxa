<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $search = '';
    public $perPage = 10;
    public $supplierToDelete = null;
    public $categoryFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    protected $listeners = ['refresh' => '$refresh'];

    public function confirmDelete($supplierId)
    {
        $this->supplierToDelete = Supplier::find($supplierId);
    }

    public function cancelDelete()
    {
        $this->supplierToDelete = null;
    }

    public function delete()
    {
        if ($this->supplierToDelete) {
            if ($this->supplierToDelete->products()->exists()) {
                session()->flash('error', 'Não é possível excluir um fornecedor que possui produtos vinculados.');
            } else {
                try {
                    $this->supplierToDelete->delete();
                    session()->flash('success', 'Fornecedor excluído com sucesso!');
                } catch (\Exception $e) {
                    session()->flash('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
                }
            }
            $this->supplierToDelete = null;
        }
    }

    public function doSearch()
    {
        $this->search = $this->searchTerm;
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->search = '';
        $this->categoryFilter = '';
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = $this->searchTerm ?: $this->search;
        
        $suppliers = Supplier::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('trading_name', 'like', '%' . $search . '%')
                        ->orWhere('cnpj', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('supplier_category_id', $this->categoryFilter);
            })
            ->orderBy('company_name')
            ->paginate($this->perPage);

        $categories = \App\Models\SupplierCategory::orderBy('name')->get();

        return view('livewire.supplier-list', [
            'suppliers' => $suppliers,
            'categories' => $categories,
        ]);
    }
}
