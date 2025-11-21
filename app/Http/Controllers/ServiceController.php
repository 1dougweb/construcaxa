<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderBy('name')->paginate(15);
        $categories = ServiceCategory::active()->orderBy('name')->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->orderBy('name')->get();
        $unitTypes = Service::getUnitTypes();

        return view('services.create', compact('categories', 'unitTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:service_categories,id',
            'unit_type' => 'required|in:hour,fixed,per_unit',
            'default_price' => 'required|numeric|min:0',
            'minimum_price' => 'nullable|numeric|min:0|lt:default_price',
            'maximum_price' => 'nullable|numeric|gt:default_price',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Serviço criado com sucesso!');
    }

    public function show(Service $service)
    {
        $service->load(['category', 'budgetItems.budget.project']);

        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::active()->orderBy('name')->get();
        $unitTypes = Service::getUnitTypes();

        return view('services.edit', compact('service', 'categories', 'unitTypes'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:service_categories,id',
            'unit_type' => 'required|in:hour,fixed,per_unit',
            'default_price' => 'required|numeric|min:0',
            'minimum_price' => 'nullable|numeric|min:0|lt:default_price',
            'maximum_price' => 'nullable|numeric|gt:default_price',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service)
    {
        if ($service->budgetItems()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Não é possível excluir um serviço que está sendo usado em orçamentos.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Serviço excluído com sucesso!');
    }

    /**
     * API endpoint for service search
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $services = Service::active()
            ->with('category')
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'category' => $service->category->name,
                    'unit_type' => $service->unit_type,
                    'unit_type_label' => $service->unit_type_label,
                    'default_price' => $service->default_price,
                    'formatted_price' => $service->formatted_price,
                ];
            });

        return response()->json($services);
    }
}
