<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['category', 'currentEmployee']);

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $equipment = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('equipment.index', compact('equipment', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('equipment.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $photos = [];
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('equipment/photos', 'public');
                    $photos[] = $path;
                }
            }

            Equipment::create([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'purchase_price' => $validated['purchase_price'],
                'purchase_date' => $validated['purchase_date'],
                'notes' => $validated['notes'],
                'photos' => $photos,
                'status' => 'available',
            ]);

            DB::commit();
            return redirect()->route('equipment.index')
                ->with('success', 'Equipamento cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar equipamento: ' . $e->getMessage());
        }
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'currentEmployee', 'movements.employee', 'movements.user']);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $categories = Category::orderBy('name')->get();
        return view('equipment.edit', compact('equipment', 'categories'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number,' . $equipment->id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $photos = $equipment->photos ?? [];
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('equipment/photos', 'public');
                    $photos[] = $path;
                }
            }

            $equipment->update([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'purchase_price' => $validated['purchase_price'],
                'purchase_date' => $validated['purchase_date'],
                'notes' => $validated['notes'],
                'status' => $validated['status'],
                'photos' => $photos,
            ]);

            DB::commit();
            return redirect()->route('equipment.show', $equipment)
                ->with('success', 'Equipamento atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar equipamento: ' . $e->getMessage());
        }
    }

    public function destroy(Equipment $equipment)
    {
        DB::beginTransaction();
        try {
            // Verificar se o equipamento está emprestado
            if ($equipment->isBorrowed()) {
                return back()->with('error', 'Não é possível excluir um equipamento que está emprestado.');
            }

            // Deletar fotos do storage
            if ($equipment->photos) {
                foreach ($equipment->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            $equipment->delete();
            DB::commit();

            return redirect()->route('equipment.index')
                ->with('success', 'Equipamento excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir equipamento: ' . $e->getMessage());
        }
    }

    public function deletePhoto(Equipment $equipment, $photoIndex)
    {
        $photos = $equipment->photos ?? [];
        
        if (isset($photos[$photoIndex])) {
            // Deletar foto do storage
            Storage::disk('public')->delete($photos[$photoIndex]);
            
            // Remover foto do array
            unset($photos[$photoIndex]);
            $photos = array_values($photos); // Reindexar array
            
            $equipment->update(['photos' => $photos]);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function history(Equipment $equipment)
    {
        $movements = $equipment->movements()
            ->with(['employee', 'user', 'equipmentRequest'])
            ->latest()
            ->paginate(10);

        return view('equipment.history', compact('equipment', 'movements'));
    }
}