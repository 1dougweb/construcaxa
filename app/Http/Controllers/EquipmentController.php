<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['equipmentCategory', 'currentEmployee']);

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('equipment_category_id')) {
            $query->where('equipment_category_id', $request->equipment_category_id);
        }

        $equipment = $query->latest()->paginate(10);
        $equipmentCategories = EquipmentCategory::orderBy('name')->get();

        return view('equipment.index', compact('equipment', 'equipmentCategories'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number',
            'description' => 'nullable|string',
            'equipment_category_id' => 'nullable|exists:equipment_categories,id',
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
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('equipment', $filename, 'public');
                    $photos[] = $path;
                }
            }

            Equipment::create([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'description' => $validated['description'],
                'equipment_category_id' => $validated['equipment_category_id'] ?? null,
                'purchase_price' => $validated['purchase_price'],
                'purchase_date' => $validated['purchase_date'],
                'notes' => $validated['notes'],
                'photos' => $photos,
                'status' => 'available',
            ]);

            DB::commit();
            session()->flash('success', 'Equipamento cadastrado com sucesso!');
            return redirect()->route('equipment.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao cadastrar equipamento: ' . $e->getMessage());
            return back();
        }
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['equipmentCategory', 'currentEmployee', 'movements.employee', 'movements.user']);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number,' . $equipment->id,
            'description' => 'nullable|string',
            'equipment_category_id' => 'nullable|exists:equipment_categories,id',
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
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('equipment', $filename, 'public');
                    $photos[] = $path;
                }
            }

            $equipment->update([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'description' => $validated['description'],
                'equipment_category_id' => $validated['equipment_category_id'] ?? null,
                'purchase_price' => $validated['purchase_price'],
                'purchase_date' => $validated['purchase_date'],
                'notes' => $validated['notes'],
                'status' => $validated['status'],
                'photos' => $photos,
            ]);

            DB::commit();
            session()->flash('success', 'Equipamento atualizado com sucesso!');
            return redirect()->route('equipment.show', $equipment);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao atualizar equipamento: ' . $e->getMessage());
            return back();
        }
    }

    public function destroy(Equipment $equipment)
    {
        DB::beginTransaction();
        try {
            // Verificar se o equipamento está emprestado
            if ($equipment->isBorrowed()) {
                session()->flash('error', 'Não é possível excluir um equipamento que está emprestado.');
                return back();
            }

            // Deletar fotos do storage
            if ($equipment->photos) {
                foreach ($equipment->photos as $photo) {
                    // Verificar se é caminho antigo (public/images) ou novo (storage)
                    if (strpos($photo, 'images/equipment/') === 0) {
                        // Caminho antigo - deletar de public
                        $filePath = public_path($photo);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    } else {
                        // Caminho novo - deletar do storage
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            $equipment->delete();
            DB::commit();

            session()->flash('success', 'Equipamento excluído com sucesso!');
            return redirect()->route('equipment.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao excluir equipamento: ' . $e->getMessage());
            return back();
        }
    }

    public function deletePhoto(Equipment $equipment, $photoIndex)
    {
        $photos = $equipment->photos ?? [];
        
        if (isset($photos[$photoIndex])) {
            $photoPath = $photos[$photoIndex];
            
            // Deletar foto do storage
            // Verificar se é caminho antigo (public/images) ou novo (storage)
            if (strpos($photoPath, 'images/equipment/') === 0) {
                // Caminho antigo - deletar de public
                $filePath = public_path($photoPath);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            } else {
                // Caminho novo - deletar do storage
                Storage::disk('public')->delete($photoPath);
            }
            
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