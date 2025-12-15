<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vistoria') }} #{{ $inspection->number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Conteúdo principal -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informações da Vistoria') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Número') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $inspection->number }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Data') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $inspection->inspection_date->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}:</span>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                        @if($inspection->status === 'completed') bg-green-100 text-green-800
                                        @elseif($inspection->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informações do Cliente') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nome') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $inspection->client->name ?? $inspection->client->trading_name }}</span>
                                </div>
                                @if($inspection->address)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Endereço') }}:</span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $inspection->address }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($inspection->description)
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Descrição') }}</h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $inspection->description }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Orçamento') }}</h3>
                            @if($inspection->budget)
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Orçamento #{{ $inspection->budget->id }} vinculado
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                Status: <span class="capitalize">{{ $inspection->budget->status_label }}</span>
                                            </p>
                                        </div>
                                        <form method="POST" action="{{ route('inspections.unlink-budget', $inspection) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                                                Desvincular
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form method="POST" action="{{ route('inspections.link-budget', $inspection) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="budget_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Vincular a um Orçamento
                                        </label>
                                        <select name="budget_id" id="budget_id" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                            <option value="">Selecione um orçamento</option>
                                            @foreach(\App\Models\ProjectBudget::where(function($query) use ($inspection) {
                                                $query->whereNull('inspection_id')
                                                      ->orWhere('inspection_id', $inspection->id);
                                            })->with('client')->latest()->get() as $budget)
                                                <option value="{{ $budget->id }}">
                                                    Orçamento #{{ $budget->id }} - {{ $budget->client->name ?? $budget->client->trading_name ?? 'N/A' }} - R$ {{ number_format($budget->total ?? 0, 2, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Vincular Orçamento
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Ambientes -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Ambientes') }}</h3>
                        <div class="space-y-6">
                            @foreach($inspection->environments as $environment)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $environment->name }}</h4>
                                    
                                    @if($environment->items->count() > 0)
                                        <div class="space-y-4">
                                            @foreach($environment->items as $item)
                                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <h5 class="font-medium text-gray-900 dark:text-gray-100">{{ $item->title }}</h5>
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            @if($item->quality_rating === 'excellent') bg-green-100 text-green-800
                                                            @elseif($item->quality_rating === 'good') bg-blue-100 text-blue-800
                                                            @elseif($item->quality_rating === 'regular') bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ $item->quality_label }}
                                                        </span>
                                                    </div>
                                                    
                                                    @if($item->observations)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $item->observations }}</p>
                                                    @endif

                                                    @if($item->photos->count() > 0)
                                                        <div class="grid grid-cols-4 gap-2 mt-2">
                                                            @foreach($item->photos as $photo)
                                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto" class="w-full h-24 object-cover rounded">
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum item cadastrado neste ambiente.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar de ações -->
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4 lg:p-6 space-y-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                Ações da Vistoria
                            </h3>

                            @if($inspection->status !== 'completed')
                                <a href="{{ route('inspections.edit', $inspection) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                    <i class="fi fi-rr-edit mr-2"></i>
                                    {{ __('Editar Vistoria') }}
                                </a>

                                <form method="POST"
                                      action="{{ route('inspections.complete', $inspection) }}"
                                      onsubmit="return confirm('Marcar esta vistoria como concluída e notificar o cliente?');">
                                    @csrf
                                    <button type="submit"
                                            class="w-full mt-2 inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 transition-colors">
                                        <i class="fi fi-rr-check-circle mr-2"></i>
                                        {{ __('Marcar como Concluída') }}
                                    </button>
                                </form>
                            @else
                                <div class="space-y-2">
                                    <div class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-md text-xs text-emerald-800 dark:text-emerald-200">
                                        <i class="fi fi-rr-badge-check mr-2"></i>
                                        {{ __('Vistoria concluída') }}
                                    </div>
                                    <form method="POST"
                                          action="{{ route('inspections.resend-email', $inspection) }}"
                                          onsubmit="return confirm('Reenviar o e-mail de vistoria concluída para o cliente?');">
                                        @csrf
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                                            <i class="fi fi-rr-envelope mr-2"></i>
                                            Reenviar e-mail da vistoria
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <a href="{{ route('inspections.pdf', $inspection) }}"
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                                <i class="fi fi-rr-file-pdf mr-2"></i>
                                {{ __('Gerar PDF') }}
                            </a>

                            <a href="{{ route('inspections.index') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <i class="fi fi-rr-arrow-left mr-2"></i>
                                {{ __('Voltar para Vistorias') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

