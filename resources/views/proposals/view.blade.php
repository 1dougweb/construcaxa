<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proposta de Trabalho - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Proposta de Trabalho</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Olá, {{ $proposal->employee->user->name }}</p>
                    </div>

                    <div class="mb-6">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $proposal->status_color }}">
                            {{ $proposal->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor da Hora</h3>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">
                                R$ {{ number_format($proposal->hourly_rate, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total</h3>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">
                                R$ {{ number_format($proposal->total_amount, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Contrato</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->contract_type_label }}</p>
                        </div>
                        @if($proposal->project)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Obra</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->project->name }}</p>
                            </div>
                        @endif
                        @if($proposal->contract_type === 'fixed_days')
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dias</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->days }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Período</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                    {{ $proposal->start_date ? $proposal->start_date->format('d/m/Y') : 'N/A' }} 
                                    até 
                                    {{ $proposal->end_date ? $proposal->end_date->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($proposal->observations)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Observações</h3>
                            <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $proposal->observations }}
                            </p>
                        </div>
                    @endif

                    @if($proposal->items->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Itens da Proposta</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Item</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($proposal->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $item->item_type_label }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $item->item_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->quantity, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($proposal->isPending())
                        <div class="mt-6 flex justify-center gap-4">
                            <form action="{{ route('proposals.accept', $proposal->token) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                    Aceitar Proposta
                                </button>
                            </form>
                            <form action="{{ route('proposals.reject', $proposal->token) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                    Rejeitar Proposta
                                </button>
                            </form>
                        </div>
                    @elseif($proposal->isAccepted())
                        <div class="mt-6 text-center">
                            <p class="text-green-600 dark:text-green-400 font-semibold">Esta proposta foi aceita em {{ $proposal->accepted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @elseif($proposal->isRejected())
                        <div class="mt-6 text-center">
                            <p class="text-red-600 dark:text-red-400 font-semibold">Esta proposta foi rejeitada em {{ $proposal->rejected_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @elseif($proposal->isExpired())
                        <div class="mt-6 text-center">
                            <p class="text-gray-600 dark:text-gray-400 font-semibold">Esta proposta expirou em {{ $proposal->expires_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Esta proposta expira em {{ $proposal->expires_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

