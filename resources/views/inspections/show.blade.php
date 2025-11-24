<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vistoria') }} #{{ $inspection->number }}
            </h2>
            <div class="flex space-x-2">
                @can('edit inspections')
                <a href="{{ route('inspections.edit', $inspection) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                @endcan
                <a href="{{ route('inspections.pdf', $inspection) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600" target="_blank">
                    <i class="bi bi-file-pdf mr-2"></i>
                    Gerar PDF
                </a>
                <a href="{{ route('inspections.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $inspection->number }}</h1>
                            <span class="px-3 py-1 text-sm rounded-full border {{ $inspection->status_color }}">
                                {{ $inspection->status_label }}
                            </span>
                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full">
                                v{{ $inspection->version }}
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">Cliente: <strong class="text-gray-900 dark:text-gray-100">{{ $inspection->client->name }}</strong></p>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Data da Vistoria</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $inspection->inspection_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Responsável</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $inspection->inspector->name ?? '-' }}
                        </div>
                    </div>
                    @if($inspection->approved_at)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Aprovada em</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $inspection->approved_at->format('d/m/Y H:i') }}
                        </div>
                        @if($inspection->approvedBy)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            por {{ $inspection->approvedBy->name }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Endereço -->
                @if($inspection->address)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Endereço</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300">{{ $inspection->address }}</p>
                    </div>
                </div>
                @endif

                <!-- Descrição -->
                @if($inspection->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Descrição</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $inspection->description }}</p>
                    </div>
                </div>
                @endif

                <!-- Fotos -->
                @if($inspection->photos && count($inspection->photos) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Fotos</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($inspection->photos as $photo)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $photo) }}" alt="Foto da vistoria" 
                                     class="w-full h-48 object-cover rounded-lg cursor-pointer border border-gray-200 dark:border-gray-700"
                                     onclick="window.open('{{ asset('storage/' . $photo) }}', '_blank')">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Observações -->
                @if($inspection->notes)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Observações</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $inspection->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Documento Assinado -->
                @if($inspection->signed_document_path)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Documento Assinado</h3>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <a href="{{ asset('storage/' . $inspection->signed_document_path) }}" 
                           target="_blank" 
                           class="inline-flex items-center text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                            <i class="bi bi-file-pdf mr-2"></i>
                            Visualizar documento assinado
                        </a>
                    </div>
                </div>
                @else
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Documento Assinado</h3>
                    <form action="{{ route('inspections.upload-signed', $inspection) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <input type="file" name="signed_document" accept=".pdf" required
                                   class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600">
                                Anexar Documento Assinado
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Orçamento Vinculado -->
                @if($inspection->budget)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Orçamento Vinculado</h3>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <a href="{{ route('budgets.edit', $inspection->budget) }}" 
                           class="text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                            Ver orçamento #{{ $inspection->budget->id }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- Ações -->
                @can('edit inspections')
                <div class="mt-6 flex space-x-2">
                    @if($inspection->status !== 'approved')
                    <form action="{{ route('inspections.approve', $inspection) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600"
                                onclick="return confirm('Deseja aprovar esta vistoria?')">
                            <i class="bi bi-check-circle mr-2"></i>
                            Aprovar Vistoria
                        </button>
                    </form>
                    @endif
                </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>



