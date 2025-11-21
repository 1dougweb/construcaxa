<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Requisição de Equipamento #' . $equipmentRequest->number) }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('equipment-requests.pdf', $equipmentRequest) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('PDF') }}
                </a>
                @can('edit service-orders')
                    @if($equipmentRequest->status === 'pending')
                        <a href="{{ route('equipment-requests.edit', $equipmentRequest) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Editar') }}
                        </a>
                    @endif
                @endcan
                <a href="{{ route('equipment-requests.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Requisição</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $equipmentRequest->number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $equipmentRequest->type === 'loan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($equipmentRequest->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('approved') bg-blue-100 text-blue-800 @break
                                        @case('rejected') bg-red-100 text-red-800 @break
                                        @case('completed') bg-green-100 text-green-800 @break
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
                                <label class="block text-sm font-medium text-gray-700">Data de Criação</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentRequest->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Funcionário</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipmentRequest->employee->name }}</p>
                                @if($equipmentRequest->employee->department)
                                <p class="text-xs text-gray-500">{{ $equipmentRequest->employee->department }}</p>
                                @endif
                            </div>
                            @if($equipmentRequest->serviceOrder)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ordem de Serviço</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $equipmentRequest->serviceOrder->number }}</p>
                                <p class="text-xs text-gray-500">{{ $equipmentRequest->serviceOrder->client_name }}</p>
                            </div>
                            @endif
                        </div>

                        @if($equipmentRequest->expected_return_date)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Data Prevista de Devolução</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipmentRequest->expected_return_date->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($equipmentRequest->purpose)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Finalidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipmentRequest->purpose }}</p>
                        </div>
                        @endif

                        @if($equipmentRequest->notes)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipmentRequest->notes }}</p>
                        </div>
                        @endif

                        <!-- Equipamentos -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Equipamentos</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Equipamento</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Série</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qtd</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($equipmentRequest->items as $item)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    <div class="flex items-center">
                                                        @if($item->equipment->photos && count($item->equipment->photos) > 0)
                                                            <img src="{{ asset('storage/' . $item->equipment->photos[0]) }}" 
                                                                 alt="{{ $item->equipment->name }}" 
                                                                 class="h-10 w-10 rounded-lg object-cover mr-3">
                                                        @else
                                                            <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->equipment->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $item->equipment->category->name ?? 'Sem categoria' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $item->equipment->serial_number }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-500">{{ $item->condition_notes ?: '-' }}</td>
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
                    @can('edit service-orders')
                        @if($equipmentRequest->status === 'pending')
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                                <div class="space-y-3">
                                    <form method="POST" action="{{ route('equipment-requests.approve', $equipmentRequest) }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja aprovar esta requisição?')">
                                            Aprovar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('equipment-requests.reject', $equipmentRequest) }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja rejeitar esta requisição?')">
                                            Rejeitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @elseif($equipmentRequest->status === 'approved')
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Processar</h3>
                                <form method="POST" action="{{ route('equipment-requests.complete', $equipmentRequest) }}" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            onclick="return confirm('Tem certeza que deseja processar esta requisição?')">
                                        Processar {{ $equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endcan

                    <!-- Informações do Usuário -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Criado por</h3>
                            <div class="flex items-center">
                                @if($equipmentRequest->user->profile_photo)
                                    <img src="{{ asset('storage/' . $equipmentRequest->user->profile_photo) }}" 
                                         alt="{{ $equipmentRequest->user->name }}" 
                                         class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($equipmentRequest->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $equipmentRequest->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $equipmentRequest->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
