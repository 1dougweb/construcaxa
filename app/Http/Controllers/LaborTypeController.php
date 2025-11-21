<?php

namespace App\Http\Controllers;

use App\Models\LaborType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaborTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = LaborType::query();

        // Filter by skill level
        if ($request->filled('skill_level')) {
            $query->where('skill_level', $request->skill_level);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $laborTypes = $query->orderBy('name')->paginate(15);
        $skillLevels = LaborType::getSkillLevels();

        return view('labor-types.index', compact('laborTypes', 'skillLevels'));
    }

    public function create()
    {
        $skillLevels = LaborType::getSkillLevels();

        return view('labor-types.create', compact('skillLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'skill_level' => 'required|in:junior,senior,specialist',
            'hourly_rate' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LaborType::create($validated);

        return redirect()->route('labor-types.index')
            ->with('success', 'Tipo de mão de obra criado com sucesso!');
    }

    public function show(LaborType $laborType)
    {
        $laborType->load(['budgetItems.budget.project']);

        return view('labor-types.show', compact('laborType'));
    }

    public function edit(LaborType $laborType)
    {
        $skillLevels = LaborType::getSkillLevels();

        return view('labor-types.edit', compact('laborType', 'skillLevels'));
    }

    public function update(Request $request, LaborType $laborType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'skill_level' => 'required|in:junior,senior,specialist',
            'hourly_rate' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $laborType->update($validated);

        return redirect()->route('labor-types.index')
            ->with('success', 'Tipo de mão de obra atualizado com sucesso!');
    }

    public function destroy(LaborType $laborType)
    {
        if ($laborType->budgetItems()->exists()) {
            return redirect()->route('labor-types.index')
                ->with('error', 'Não é possível excluir um tipo de mão de obra que está sendo usado em orçamentos.');
        }

        $laborType->delete();

        return redirect()->route('labor-types.index')
            ->with('success', 'Tipo de mão de obra excluído com sucesso!');
    }

    /**
     * API endpoint for labor type search
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $laborTypes = LaborType::active()
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function ($laborType) {
                return [
                    'id' => $laborType->id,
                    'name' => $laborType->name,
                    'description' => $laborType->description,
                    'skill_level' => $laborType->skill_level,
                    'skill_level_label' => $laborType->skill_level_label,
                    'hourly_rate' => $laborType->hourly_rate,
                    'overtime_rate' => $laborType->overtime_rate,
                    'formatted_hourly_rate' => $laborType->formatted_hourly_rate,
                    'formatted_overtime_rate' => $laborType->formatted_overtime_rate,
                ];
            });

        return response()->json($laborTypes);
    }
}
