<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')
            ->orderBy('name')
            ->paginate(15);

        return view('service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('service-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ServiceCategory::create($validated);

        return redirect()->route('service-categories.index')
            ->with('success', 'Categoria de serviço criada com sucesso!');
    }

    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->load(['services' => function($query) {
            $query->latest()->with('budgetItems');
        }]);

        return view('service-categories.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')->ignore($serviceCategory->id),
            ],
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceCategory->update($validated);

        return redirect()->route('service-categories.index')
            ->with('success', 'Categoria de serviço atualizada com sucesso!');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->exists()) {
            return redirect()->route('service-categories.index')
                ->with('error', 'Não é possível excluir uma categoria que possui serviços.');
        }

        $serviceCategory->delete();

        return redirect()->route('service-categories.index')
            ->with('success', 'Categoria de serviço excluída com sucesso!');
    }
}
