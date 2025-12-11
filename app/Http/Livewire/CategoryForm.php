<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryForm extends Component
{
    public $category;
    public $name;
    public $description;
    public $sku_prefix;
    public $parent_id;

    protected function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'sku_prefix' => 'required|size:3|alpha_num',
            'parent_id' => 'nullable|exists:categories,id',
        ];

        // Adicionar validação unique para sku_prefix
        if ($this->category) {
            $rules['sku_prefix'] = 'required|size:3|alpha_num|unique:categories,sku_prefix,' . $this->category->id;
        } else {
            $rules['sku_prefix'] = 'required|size:3|alpha_num|unique:categories,sku_prefix';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'O nome da categoria é obrigatório.',
        'sku_prefix.required' => 'O prefixo SKU é obrigatório.',
        'sku_prefix.size' => 'O prefixo SKU deve ter exatamente 3 caracteres.',
        'sku_prefix.alpha_num' => 'O prefixo SKU deve conter apenas letras e números.',
        'sku_prefix.unique' => 'Este prefixo SKU já está em uso.',
    ];

    public function mount($category = null, $categoryId = null)
    {
        // Se receber categoryId, carregar a categoria
        if ($categoryId && !$category) {
            $category = Category::find($categoryId);
        }
        
        if ($category) {
            $this->category = $category;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->sku_prefix = $category->sku_prefix;
            $this->parent_id = $category->parent_id;
        }
    }

    public function loadCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->mount($category);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedSkuPrefix($value)
    {
        // Converter para maiúsculas automaticamente
        $this->sku_prefix = strtoupper($value);
    }

    public function save()
    {
        // Garantir que o SKU está em maiúsculas
        $this->sku_prefix = strtoupper($this->sku_prefix);
        
        $validatedData = $this->validate();

        if ($this->category) {
            // Atualização
            $this->category->update($validatedData);
            session()->flash('success', 'Categoria atualizada com sucesso.');
        } else {
            // Criação
            Category::create($validatedData);
            session()->flash('success', 'Categoria criada com sucesso.');
        }

        // Emitir evento para fechar o offcanvas e atualizar a lista
        $this->dispatch('categorySaved');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->sku_prefix = '';
        $this->parent_id = null;
        $this->category = null;
    }

    public function render()
    {
        $categories = Category::where('id', '!=', $this->category?->id)
            ->orderBy('name')
            ->get();

        return view('livewire.category-form', [
            'categories' => $categories,
        ]);
    }
}


