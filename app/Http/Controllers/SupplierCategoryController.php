<?php

namespace App\Http\Controllers;

use App\Models\SupplierCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierCategoryController extends Controller
{
    public function index()
    {
        return view('supplier-categories.index');
    }

    public function create()
    {
        return view('supplier-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        SupplierCategory::create($validated);

        session()->flash('success', 'Categoria de fornecedor criada com sucesso.');
        return redirect()->route('supplier-categories.index');
    }

    public function edit(SupplierCategory $supplierCategory)
    {
        return view('supplier-categories.edit', compact('supplierCategory'));
    }

    public function update(Request $request, SupplierCategory $supplierCategory)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $supplierCategory->update($validated);

        session()->flash('success', 'Categoria de fornecedor atualizada com sucesso.');
        return redirect()->route('supplier-categories.index');
    }

    public function destroy(SupplierCategory $supplierCategory)
    {
        if ($supplierCategory->suppliers()->exists()) {
            session()->flash('error', 'Não é possível excluir uma categoria que possui fornecedores.');
            return redirect()->route('supplier-categories.index');
        }

        $supplierCategory->delete();
        session()->flash('success', 'Categoria de fornecedor excluída com sucesso.');
        return redirect()->route('supplier-categories.index');
    }
}
