<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Equipment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Contract;
use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json([
                    'results' => [],
                    'total' => 0
                ]);
            }

            $results = [];
            $limit = 10;

        // Buscar Produtos
        try {
            $products = Product::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->with(['category', 'supplier'])
                ->limit($limit)
                ->get();
            
            foreach ($products as $product) {
                try {
                    $photo = null;
                    if ($product->photos && is_array($product->photos) && count($product->photos) > 0) {
                        $photoPath = $product->photos[0];
                        $photo = '/' . ltrim($photoPath, '/');
                    }
                    
                    $results[] = [
                        'id' => $product->id,
                        'type' => 'product',
                        'title' => $product->name ?? 'Sem nome',
                        'subtitle' => $product->sku ? "SKU: {$product->sku}" : ($product->category ? $product->category->name : ''),
                        'description' => $product->description ?? '',
                        'photo' => $photo,
                        'url' => route('products.index'),
                        'meta' => [
                            'stock' => $product->stock ?? 0,
                            'price' => $product->price ?? 0,
                            'category' => $product->category ? $product->category->name : null,
                        ]
                    ];
                } catch (\Exception $e) {
                    Log::error('Erro ao mapear produto na pesquisa: ' . $e->getMessage() . ' - Product ID: ' . ($product->id ?? 'N/A'));
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao buscar produtos na pesquisa: ' . $e->getMessage() . ' - Query: ' . $query);
        }

        // Buscar Equipamentos
        try {
            $equipments = Equipment::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('serial_number', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->with(['equipmentCategory:id,name', 'currentEmployee:id,user_id'])
                ->limit($limit)
                ->get()
                ->map(function($equipment) {
                    return [
                        'id' => $equipment->id,
                        'type' => 'equipment',
                        'title' => $equipment->name,
                        'subtitle' => $equipment->serial_number ? "S/N: {$equipment->serial_number}" : ($equipment->equipmentCategory ? $equipment->equipmentCategory->name : ''),
                        'description' => $equipment->description,
                        'photo' => $equipment->first_photo_url,
                        'url' => route('equipment.show', $equipment),
                        'meta' => [
                            'status' => $equipment->status,
                            'category' => $equipment->equipmentCategory ? $equipment->equipmentCategory->name : null,
                        ]
                    ];
                });

            $results = array_merge($results, $equipments->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar equipamentos na pesquisa: ' . $e->getMessage());
        }

        // Buscar Clientes
        try {
            $clients = Client::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('cnpj', 'like', "%{$query}%")
                      ->orWhere('cpf', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get()
                ->map(function($client) {
                    return [
                        'id' => $client->id,
                        'type' => 'client',
                        'title' => $client->name,
                        'subtitle' => $client->email ?: ($client->cnpj ?: $client->cpf),
                        'description' => $client->address ?? '',
                        'photo' => null,
                        'url' => route('clients.show', $client),
                        'meta' => [
                            'email' => $client->email,
                            'phone' => $client->phone,
                        ]
                    ];
                });

            $results = array_merge($results, $clients->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar clientes na pesquisa: ' . $e->getMessage());
        }

        // Buscar Funcionários
        try {
            $employees = Employee::where(function($q) use ($query) {
                    $q->whereHas('user', function($userQuery) use ($query) {
                            $userQuery->where('name', 'like', "%{$query}%")
                                      ->orWhere('email', 'like', "%{$query}%");
                        })
                        ->orWhere('cpf', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->orWhere('cellphone', 'like', "%{$query}%");
                })
                ->with('user')
                ->limit($limit)
                ->get()
                ->map(function($employee) {
                    return [
                        'id' => $employee->id,
                        'type' => 'employee',
                        'title' => $employee->user?->name ?? 'Sem nome',
                        'subtitle' => $employee->user?->email ?? ($employee->cpf ?? ''),
                        'description' => $employee->position ?? '',
                        'photo' => ($employee->user && $employee->user->profile_photo) ? asset('storage/' . $employee->user->profile_photo) : null,
                        'url' => route('employees.show', $employee),
                        'meta' => [
                            'cpf' => $employee->cpf ?? null,
                            'position' => $employee->position ?? null,
                        ]
                    ];
                });

            $results = array_merge($results, $employees->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar funcionários na pesquisa: ' . $e->getMessage());
        }

        // Buscar Projetos
        try {
            $projects = Project::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->with('client')
                ->limit($limit)
                ->get()
                ->map(function($project) {
                    return [
                        'id' => $project->id,
                        'type' => 'project',
                        'title' => $project->name,
                        'subtitle' => $project->client ? $project->client->name : '',
                        'description' => $project->description,
                        'photo' => null,
                        'url' => route('projects.show', $project->slug ?? $project->id),
                        'meta' => [
                            'status' => $project->status,
                            'client' => $project->client ? $project->client->name : null,
                        ]
                    ];
                });

            $results = array_merge($results, $projects->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar projetos na pesquisa: ' . $e->getMessage());
        }

        // Buscar Documentos Financeiros (PDFs)
        // Contas a Pagar
        try {
            $accountPayables = AccountPayable::where(function($q) use ($query) {
                    $q->where('number', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->whereNotNull('document_file')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'document',
                        'title' => $item->description ?: "Conta a Pagar #{$item->number}",
                        'subtitle' => "Nº {$item->number}",
                        'description' => 'Conta a Pagar',
                        'photo' => null,
                        'document_type' => 'pdf',
                        'url' => route('financial.accounts-payable.show', $item),
                        'meta' => [
                            'type' => 'account_payable',
                            'amount' => $item->amount,
                        ]
                    ];
                });

            $results = array_merge($results, $accountPayables->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar contas a pagar na pesquisa: ' . $e->getMessage());
        }

        // Contas a Receber
        try {
            $accountReceivables = AccountReceivable::where(function($q) use ($query) {
                    $q->where('description', 'like', "%{$query}%");
                })
                ->whereNotNull('document_file')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'document',
                        'title' => $item->description ?: "Conta a Receber",
                        'subtitle' => "Valor: R$ " . number_format($item->amount, 2, ',', '.'),
                        'description' => 'Conta a Receber',
                        'photo' => null,
                        'document_type' => 'pdf',
                        'url' => route('financial.accounts-receivable.show', $item),
                        'meta' => [
                            'type' => 'account_receivable',
                            'amount' => $item->amount,
                        ]
                    ];
                });

            $results = array_merge($results, $accountReceivables->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar contas a receber na pesquisa: ' . $e->getMessage());
        }

        // Faturas
        try {
            $invoices = Invoice::where(function($q) use ($query) {
                    $q->where('number', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->whereNotNull('document_file')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'document',
                        'title' => $item->description ?: "Fatura #{$item->number}",
                        'subtitle' => "Nº {$item->number}",
                        'description' => 'Fatura',
                        'photo' => null,
                        'document_type' => 'pdf',
                        'url' => route('financial.invoices.show', $item),
                        'meta' => [
                            'type' => 'invoice',
                            'amount' => $item->amount,
                        ]
                    ];
                });

            $results = array_merge($results, $invoices->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar faturas na pesquisa: ' . $e->getMessage());
        }

        // Recibos
        try {
            $receipts = Receipt::where(function($q) use ($query) {
                    $q->where('number', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->whereNotNull('document_file')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'document',
                        'title' => $item->description ?: "Recibo #{$item->number}",
                        'subtitle' => "Nº {$item->number}",
                        'description' => 'Recibo',
                        'photo' => null,
                        'document_type' => 'pdf',
                        'url' => route('financial.receipts.show', $item),
                        'meta' => [
                            'type' => 'receipt',
                            'amount' => $item->amount,
                        ]
                    ];
                });

            $results = array_merge($results, $receipts->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar recibos na pesquisa: ' . $e->getMessage());
        }

        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $query
        ]);
        } catch (\Exception $e) {
            Log::error('Erro na pesquisa global: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'results' => [],
                'total' => 0,
                'error' => 'Erro ao realizar pesquisa',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
