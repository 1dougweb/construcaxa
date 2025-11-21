<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $equipment->name }}
            </h2>
            <div class="flex items-center space-x-4">
                @can('edit products')
                <a href="{{ route('equipment.edit', $equipment) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar') }}
                </a>
                @endcan
                <a href="{{ route('equipment.history', $equipment) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Histórico') }}
                </a>
                <a href="{{ route('equipment.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                <!-- Informações Principais -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <!-- Fotos -->
                        @if($equipment->photos && count($equipment->photos) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Fotos</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($equipment->photos as $photo)
                                    <div class="aspect-w-1 aspect-h-1">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="{{ $equipment->name }}" 
                                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75"
                                             onclick="openImageModal('{{ asset('storage/' . $photo) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Informações Básicas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número de Série</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->serial_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                            @if($equipment->category)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->category->name }}</p>
                            </div>
                            @endif
                        </div>

                        @if($equipment->currentEmployee)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Funcionário Atual</label>
                            <div class="mt-1 flex items-center">
                                @if($equipment->currentEmployee->user && $equipment->currentEmployee->user->profile_photo)
                                    <img src="{{ asset('storage/' . $equipment->currentEmployee->user->profile_photo) }}" 
                                         alt="{{ $equipment->currentEmployee->name }}" 
                                         class="h-8 w-8 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-xs mr-3">
                                        {{ strtoupper(substr($equipment->currentEmployee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $equipment->currentEmployee->name }}</p>
                                    @if($equipment->currentEmployee->department)
                                    <p class="text-xs text-gray-500">{{ $equipment->currentEmployee->department }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($equipment->description)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->description }}</p>
                        </div>
                        @endif

                        @if($equipment->purchase_price || $equipment->purchase_date)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @if($equipment->purchase_price)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Preço de Compra</label>
                                <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($equipment->purchase_price, 2, ',', '.') }}</p>
                            </div>
                            @endif
                            @if($equipment->purchase_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Compra</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->purchase_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($equipment->notes)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->notes }}</p>
                        </div>
                        @endif

                        <!-- Movimentações Recentes -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Movimentações Recentes</h3>
                            @if($equipment->movements->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Funcionário</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Observações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($equipment->movements->take(5) as $movement)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                                    <td class="px-4 py-2">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $movement->type === 'loan' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                            {{ $movement->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $movement->employee->name }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $movement->notes ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($equipment->movements->count() > 5)
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('equipment.history', $equipment) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Ver histórico completo →
                                        </a>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Nenhuma movimentação registrada.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Ações Rápidas -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                            <div class="space-y-3">
                                @can('create service-orders')
                                    @if($equipment->isAvailable())
                                    <a href="{{ route('equipment-requests.create') }}?equipment={{ $equipment->id }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Solicitar Empréstimo
                                    </a>
                                    @elseif($equipment->isBorrowed())
                                    <a href="{{ route('equipment-requests.create') }}?equipment={{ $equipment->id }}&type=return" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Solicitar Devolução
                                    </a>
                                    @endif
                                @endcan
                                <a href="{{ route('equipment.history', $equipment) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ver Histórico Completo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Sistema -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cadastrado em</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $equipment->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $equipment->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imagens -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full p-4">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
    </div>

    @push('scripts')
    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
