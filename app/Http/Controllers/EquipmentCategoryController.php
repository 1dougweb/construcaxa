<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

class EquipmentCategoryController extends Controller
{
    public function index()
    {
        return view('equipment-categories.index');
    }

    public function create()
    {
        return view('equipment-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        EquipmentCategory::create($validated);

        session()->flash('success', 'Categoria de equipamento criada com sucesso.');
        return redirect()->route('equipment-categories.index');
    }

    public function edit(EquipmentCategory $equipmentCategory)
    {
        return view('equipment-categories.edit', compact('equipmentCategory'));
    }

    public function update(Request $request, EquipmentCategory $equipmentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $equipmentCategory->update($validated);

        session()->flash('success', 'Categoria de equipamento atualizada com sucesso.');
        return redirect()->route('equipment-categories.index');
    }

    public function destroy(EquipmentCategory $equipmentCategory)
    {
        if ($equipmentCategory->equipments()->exists()) {
            session()->flash('error', 'Não é possível excluir uma categoria que possui equipamentos.');
            return redirect()->route('equipment-categories.index');
        }

        $equipmentCategory->delete();
        session()->flash('success', 'Categoria de equipamento excluída com sucesso.');
        return redirect()->route('equipment-categories.index');
    }
}
