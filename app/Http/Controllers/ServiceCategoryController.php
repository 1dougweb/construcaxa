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

        try {
            ServiceCategory::create($validated);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria de serviço criada com sucesso!',
                    'redirect' => route('service-categories.index')
                ]);
            }
            
            return redirect()->route('service-categories.index')
                ->with('success', 'Categoria de serviço criada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar categoria de serviço: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Erro ao criar categoria de serviço: ' . $e->getMessage());
        }
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
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'serviceCategory' => [
                    'id' => $serviceCategory->id,
                    'name' => $serviceCategory->name,
                    'description' => $serviceCategory->description,
                    'color' => $serviceCategory->color,
                    'is_active' => $serviceCategory->is_active,
                ]
            ]);
        }
        
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

        try {
            $serviceCategory->update($validated);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria de serviço atualizada com sucesso!',
                    'redirect' => route('service-categories.index')
                ]);
            }
            
            return redirect()->route('service-categories.index')
                ->with('success', 'Categoria de serviço atualizada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar categoria de serviço: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Erro ao atualizar categoria de serviço: ' . $e->getMessage());
        }
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
