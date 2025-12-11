<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\CNPJService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    protected CNPJService $cnpjService;

    public function __construct(CNPJService $cnpjService)
    {
        $this->cnpjService = $cnpjService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $clients = $query->withCount(['projects', 'contracts', 'budgets'])
            ->latest()
            ->paginate(15);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Limpar CPF/CNPJ antes da validação
        $requestData = $request->all();
        if ($request->type === 'individual' && isset($requestData['cpf'])) {
            $requestData['cpf'] = preg_replace('/[^0-9]/', '', $requestData['cpf']);
        } elseif ($request->type === 'company' && isset($requestData['cnpj'])) {
            $requestData['cnpj'] = preg_replace('/[^0-9]/', '', $requestData['cnpj']);
        }
        
        // Limpar CEP e telefone antes da validação
        if (isset($requestData['zip_code'])) {
            $requestData['zip_code'] = preg_replace('/[^0-9]/', '', $requestData['zip_code']);
        }
        if (isset($requestData['phone'])) {
            $requestData['phone'] = preg_replace('/[^0-9]/', '', $requestData['phone']);
        }
        
        // Criar novo request com dados limpos
        $request->merge($requestData);
        
        $validated = $this->validateClient($request);

        // Garantir que campos não usados sejam null
        if ($validated['type'] === 'individual') {
            $validated['cnpj'] = null;
            $validated['trading_name'] = null;
        } else {
            $validated['cpf'] = null;
        }

        try {
            Client::create($validated);
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente criado com sucesso!',
                    'redirect' => route('clients.index')
                ]);
            }
            return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
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
                    'message' => 'Erro ao criar cliente: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['contracts', 'projects', 'budgets', 'user']);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'client' => [
                    'id' => $client->id,
                    'type' => $client->type,
                    'cpf' => $client->formatted_cpf,
                    'cnpj' => $client->formatted_cnpj,
                    'name' => $client->name,
                    'trading_name' => $client->trading_name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'address' => $client->address,
                    'address_number' => $client->address_number,
                    'address_complement' => $client->address_complement,
                    'neighborhood' => $client->neighborhood,
                    'city' => $client->city,
                    'state' => $client->state,
                    'zip_code' => $client->zip_code,
                    'notes' => $client->notes,
                    'is_active' => $client->is_active,
                ]
            ]);
        }
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Limpar CPF/CNPJ antes da validação
        $requestData = $request->all();
        if ($request->type === 'individual' && isset($requestData['cpf'])) {
            $requestData['cpf'] = preg_replace('/[^0-9]/', '', $requestData['cpf']);
        } elseif ($request->type === 'company' && isset($requestData['cnpj'])) {
            $requestData['cnpj'] = preg_replace('/[^0-9]/', '', $requestData['cnpj']);
        }
        
        // Limpar CEP e telefone antes da validação
        if (isset($requestData['zip_code'])) {
            $requestData['zip_code'] = preg_replace('/[^0-9]/', '', $requestData['zip_code']);
        }
        if (isset($requestData['phone'])) {
            $requestData['phone'] = preg_replace('/[^0-9]/', '', $requestData['phone']);
        }
        
        // Criar novo request com dados limpos
        $request->merge($requestData);
        
        $validated = $this->validateClient($request, $client);

        // Garantir que campos não usados sejam null
        if ($validated['type'] === 'individual') {
            $validated['cnpj'] = null;
            $validated['trading_name'] = null;
        } else {
            $validated['cpf'] = null;
        }

        try {
            $client->update($validated);
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente atualizado com sucesso!',
                    'redirect' => route('clients.index')
                ]);
            }
            return redirect()->route('clients.show', $client)->with('success', 'Cliente atualizado com sucesso!');
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
                    'message' => 'Erro ao atualizar cliente: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->projects()->exists() || $client->contracts()->exists() || $client->budgets()->exists()) {
            return back()->with('error', 'Não é possível excluir um cliente que possui projetos, contratos ou orçamentos vinculados.');
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * Buscar dados do CNPJ via API
     */
    public function fetchCnpj(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|min:14|max:18',
        ]);

        try {
            $cnpj = preg_replace('/[^0-9]/', '', $request->cnpj);
            
            if (strlen($cnpj) !== 14) {
                return response()->json([
                    'success' => false,
                    'message' => 'CNPJ deve ter 14 dígitos'
                ], 400);
            }

            $data = $this->cnpjService->fetch($cnpj);

            // Separar endereço em partes
            $addressParts = explode(', ', $data['address'] ?? '');
            $address = $addressParts[0] ?? '';
            $neighborhood = '';
            
            if (count($addressParts) > 1) {
                $neighborhood = $addressParts[count($addressParts) - 1];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $data['company_name'],
                    'trading_name' => $data['trading_name'],
                    'address' => $address,
                    'neighborhood' => $neighborhood,
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip_code' => $data['zip_code'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar CNPJ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validação de cliente
     */
    protected function validateClient(Request $request, ?Client $client = null): array
    {
        $rules = [
            'type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email' . ($client ? ",{$client->id}" : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'address_complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($request->type === 'individual') {
            $rules['cpf'] = 'required|string|size:11|unique:clients,cpf' . ($client ? ",{$client->id}" : '');
        } else {
            $rules['cnpj'] = 'required|string|size:14|unique:clients,cnpj' . ($client ? ",{$client->id}" : '');
            $rules['trading_name'] = 'nullable|string|max:255';
        }

        try {
            return $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                throw $e;
            }
            throw $e;
        }
    }
}
