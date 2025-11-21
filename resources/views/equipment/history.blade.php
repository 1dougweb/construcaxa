<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Histórico: ' . $equipment->name) }}
            </h2>
            <a href="{{ route('equipment.show', $equipment) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <!-- Informações do Equipamento -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            @if($equipment->photos && count($equipment->photos) > 0)
                                <img src="{{ asset('storage/' . $equipment->photos[0]) }}" 
                                     alt="{{ $equipment->name }}" 
                                     class="h-16 w-16 rounded-lg object-cover mr-4">
                            @else
                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $equipment->name }}</h3>
                                <p class="text-sm text-gray-600">Série: {{ $equipment->serial_number }}</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($equipment->status)
                                        @case('available') bg-green-100 text-green-800 @break
                                        @case('borrowed') bg-yellow-100 text-yellow-800 @break
                                        @case('maintenance') bg-red-100 text-red-800 @break
                                        @case('retired') bg-gray-100 text-gray-800 @break
                                    @endswitch">
                                    @switch($equipment->status)
                                        @case('available') Disponível @break
                                        @case('borrowed') Emprestado @break
                                        @case('maintenance') Manutenção @break
                                        @case('retired') Aposentado @break
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Histórico de Movimentações -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Movimentações</h3>
                        
                        @if($movements->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funcionário</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requisição</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condição</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observações</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($movements as $movement)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @switch($movement->type)
                                                            @case('loan') bg-yellow-100 text-yellow-800 @break
                                                            @case('return') bg-green-100 text-green-800 @break
                                                            @case('maintenance') bg-red-100 text-red-800 @break
                                                            @case('repair') bg-blue-100 text-blue-800 @break
                                                        @endswitch">
                                                        @switch($movement->type)
                                                            @case('loan') Empréstimo @break
                                                            @case('return') Devolução @break
                                                            @case('maintenance') Manutenção @break
                                                            @case('repair') Reparo @break
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @if($movement->employee->user && $movement->employee->user->profile_photo)
                                                            <img src="{{ asset('storage/' . $movement->employee->user->profile_photo) }}" 
                                                                 alt="{{ $movement->employee->name }}" 
                                                                 class="h-8 w-8 rounded-full object-cover mr-3">
                                                        @else
                                                            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-xs mr-3">
                                                                {{ strtoupper(substr($movement->employee->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $movement->employee->name }}</div>
                                                            @if($movement->employee->department)
                                                            <div class="text-sm text-gray-500">{{ $movement->employee->department }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($movement->equipmentRequest)
                                                        <a href="{{ route('equipment-requests.show', $movement->equipmentRequest) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900">
                                                            #{{ $movement->equipmentRequest->number }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    @if($movement->condition_before)
                                                        <div class="mb-1"><strong>Antes:</strong> {{ $movement->condition_before }}</div>
                                                    @endif
                                                    @if($movement->condition_after)
                                                        <div><strong>Depois:</strong> {{ $movement->condition_after }}</div>
                                                    @endif
                                                    @if(!$movement->condition_before && !$movement->condition_after)
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $movement->notes ?: '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $movement->user->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            <div class="mt-6">
                                {{ $movements->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma movimentação</h3>
                                <p class="mt-1 text-sm text-gray-500">Este equipamento ainda não possui histórico de movimentações.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
