<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisição de Equipamento #' . $equipmentRequest->number) }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('equipment-requests.pdf', $equipmentRequest) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('PDF') }}
                </a>
                @can('edit service-orders')
                    @if($equipmentRequest->status === 'pending')
                        <a href="{{ route('equipment-requests.edit', $equipmentRequest) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Editar') }}
                        </a>
                    @endif
                @endcan
                <a href="{{ route('equipment-requests.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informações da Requisição -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Requisição</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#{{ $equipmentRequest->number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $equipmentRequest->type === 'loan' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' }}">
                                    {{ $equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($equipmentRequest->status)
                                        @case('pending') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 @break
                                        @case('approved') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 @break
                                        @case('rejected') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 @break
                                        @case('completed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 @break
                                    @endswitch">
                                    @switch($equipmentRequest->status)
                                        @case('pending') Pendente @break
                                        @case('approved') Aprovado @break
                                        @case('rejected') Rejeitado @break
                                        @case('completed') Concluído @break
                                    @endswitch
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Criação</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $equipmentRequest->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @if($equipmentRequest->serviceOrder)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordem de Serviço</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#{{ $equipmentRequest->serviceOrder->number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $equipmentRequest->serviceOrder->client_name }}</p>
                            </div>
                            @endif
                        </div>

                        @if($equipmentRequest->expected_return_date)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Prevista de Devolução</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $equipmentRequest->expected_return_date->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($equipmentRequest->purpose)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Finalidade</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $equipmentRequest->purpose }}</p>
                        </div>
                        @endif

                        @if($equipmentRequest->notes)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $equipmentRequest->notes }}</p>
                        </div>
                        @endif

                        <!-- Equipamentos -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Equipamentos</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Equipamento</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Série</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Qtd</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($equipmentRequest->items as $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-4 py-2">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->equipment->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->equipment->category->name ?? 'Sem categoria' }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $item->equipment->serial_number }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $item->condition_notes ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações e Status -->
                <div class="space-y-6">
                    <!-- Equipamentos -->
                    @if($equipmentRequest->items->count() > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Equipamentos</h3>
                            <div class="space-y-4">
                                @foreach($equipmentRequest->items as $item)
                                    @php
                                        // Buscar última requisição de empréstimo completada para este equipamento
                                        $lastLoanItem = $item->equipment->getLastLoan();
                                    @endphp
                                    <div class="flex items-start">
                                        @if($item->equipment->photos && count($item->equipment->photos) > 0)
                                            <img src="{{ asset('storage/' . $item->equipment->photos[0]) }}" 
                                                 alt="{{ $item->equipment->name }}" 
                                                 class="h-16 w-16 rounded-lg object-cover mr-3 flex-shrink-0">
                                        @else
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-3 flex-shrink-0">
                                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $item->equipment->name }}</div>
                                            @if($lastLoanItem && $lastLoanItem->equipmentRequest && $lastLoanItem->equipmentRequest->employee)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Último uso: 
                                                    <a href="{{ route('employees.show', $lastLoanItem->equipmentRequest->employee) }}" 
                                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                        {{ $lastLoanItem->equipmentRequest->employee->name }}
                                                    </a>
                                                </div>
                                                @if($lastLoanItem->equipmentRequest->created_at)
                                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $lastLoanItem->equipmentRequest->created_at->format('d/m/Y') }}
                                                </div>
                                                @endif
                                            @elseif($item->equipment->currentEmployee)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Em uso: 
                                                    <a href="{{ route('employees.show', $item->equipment->currentEmployee) }}" 
                                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                        {{ $item->equipment->currentEmployee->name }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Nenhum uso registrado
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @can('edit service-orders')
                        @if($equipmentRequest->status === 'pending')
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações</h3>
                                <div class="space-y-3">
                                    <form method="POST" action="{{ route('equipment-requests.approve', $equipmentRequest) }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja aprovar esta requisição?')">
                                            Aprovar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('equipment-requests.reject', $equipmentRequest) }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja rejeitar esta requisição?')">
                                            Rejeitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @elseif($equipmentRequest->status === 'approved')
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Processar</h3>
                                <form method="POST" action="{{ route('equipment-requests.complete', $equipmentRequest) }}" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                            onclick="return confirm('Tem certeza que deseja processar esta requisição?')">
                                        Processar {{ $equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endcan

                    <!-- Informações do Usuário que Criou -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Criado por</h3>
                            <div class="flex items-center">
                                @if($equipmentRequest->user->profile_photo)
                                    <img src="{{ asset('storage/' . $equipmentRequest->user->profile_photo) }}" 
                                         alt="{{ $equipmentRequest->user->name }}" 
                                         class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($equipmentRequest->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $equipmentRequest->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $equipmentRequest->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
