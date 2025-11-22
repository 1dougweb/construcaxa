<?php

namespace App\Http\Livewire;

use App\Models\EquipmentCategory;
use Livewire\Component;
use Livewire\WithPagination;

class EquipmentCategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $categoryToDelete = null;

    protected $listeners = ['refresh' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = EquipmentCategory::find($categoryId);
    }

    public function cancelDelete()
    {
        $this->categoryToDelete = null;
    }

    public function delete()
    {
        if ($this->categoryToDelete) {
            try {
                $categoryName = $this->categoryToDelete->name;
                
                if ($this->categoryToDelete->equipments()->exists()) {
                    session()->flash('error', 'Não é possível excluir uma categoria que possui equipamentos.');
                } else {
                    $this->categoryToDelete->delete();
                    session()->flash('success', 'Categoria "' . $categoryName . '" excluída com sucesso!');
                    $this->resetPage();
                }
            } catch (\Exception $e) {
                session()->flash('error', 'Erro ao excluir categoria: ' . $e->getMessage());
            }
        }
        $this->categoryToDelete = null;
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $categories = EquipmentCategory::withCount('equipments')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.equipment-category-list', [
            'categories' => $categories
        ]);
    }
}
