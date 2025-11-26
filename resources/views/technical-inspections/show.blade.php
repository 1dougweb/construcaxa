<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vistoria Técnica #' . $technicalInspection->number) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informações Gerais -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Vistoria</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#{{ $technicalInspection->number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data da Vistoria</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->inspection_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $technicalInspection->status_color }}">
                                    {{ $technicalInspection->status_label }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total de Fotos</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->total_photos_count }}</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->address }}</p>
                        </div>

                        @if($technicalInspection->unit_area)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metragem</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($technicalInspection->unit_area, 2, ',', '.') }} m²</p>
                        </div>
                        @endif

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsável Técnico</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->responsible_name }}</p>
                        </div>

                        @if($technicalInspection->involved_parties)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intervenientes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->involved_parties }}</p>
                        </div>
                        @endif

                        @if($technicalInspection->map_image_path)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mapa</label>
                            <img src="{{ asset('storage/' . $technicalInspection->map_image_path) }}" alt="Mapa" class="max-w-full h-auto rounded-lg">
                        </div>
                        @endif

                        <!-- Ambientes -->
                        <div class="mt-8">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Ambientes Vistoriados</h4>
                            <div class="space-y-6">
                                @foreach($technicalInspection->environments as $environment)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $environment->name }}</h5>
                                        @if($environment->technical_notes)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $environment->technical_notes }}</p>
                                        @endif

                                        @if($environment->photos && count($environment->photos) > 0)
                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            @foreach($environment->photos as $photo)
                                                <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="w-full h-24 object-cover rounded">
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($environment->google_drive_link)
                                        <div class="mb-3">
                                            <a href="{{ $environment->google_drive_link }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                                                Link do Google Drive →
                                            </a>
                                        </div>
                                        @endif

                                        <!-- Elementos -->
                                        @if($environment->elements->count() > 0)
                                        <div class="mt-4">
                                            <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Elementos:</h6>
                                            <div class="space-y-2">
                                                @foreach($environment->elements as $element)
                                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded p-2">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $element->name }}</span>
                                                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $element->condition_color }}20; color: {{ $element->condition_color }}">
                                                                {{ $element->condition_label }}
                                                            </span>
                                                        </div>
                                                        @if($element->technical_notes)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $element->technical_notes }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Ações PDF -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações</h3>
                            <div class="space-y-3">
                                <!-- Visualizar PDF no navegador -->
                                <a href="{{ route('technical-inspections.view-pdf', $technicalInspection) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" 
                                   target="_blank">
                                    <i class="fi fi-rr-file-pdf mr-2"></i>
                                    {{ __('Visualizar PDF') }}
                                </a>
                                
                                <!-- Baixar PDF -->
                                <a href="{{ route('technical-inspections.pdf', $technicalInspection) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <i class="fi fi-rr-download mr-2"></i>
                                    {{ __('Baixar PDF') }}
                                </a>
                                
                                @can('edit service-orders')
                                <a href="{{ route('technical-inspections.edit', $technicalInspection) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <i class="fi fi-rr-edit mr-2"></i>
                                    {{ __('Editar') }}
                                </a>
                                @endcan
                                
                                <a href="{{ route('technical-inspections.index') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <i class="fi fi-rr-arrow-left mr-2"></i>
                                    {{ __('Voltar') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($technicalInspection->client)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cliente</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->client->name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($technicalInspection->project)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Projeto</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->project->name }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Criado por</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $technicalInspection->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $technicalInspection->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

