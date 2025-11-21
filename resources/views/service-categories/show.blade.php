<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Categoria') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('service-categories.edit', $serviceCategory) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('service-categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-block w-8 h-8 rounded-full border-2 border-gray-300" 
                                  style="background-color: {{ $serviceCategory->color }}"></span>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $serviceCategory->name }}</h1>
                        </div>
                        @if($serviceCategory->description)
                            <p class="text-gray-600">{{ $serviceCategory->description }}</p>
                        @endif
                    </div>
                    <div>
                        <span class="px-3 py-1 text-sm rounded-full {{ $serviceCategory->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $serviceCategory->is_active ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Cor</div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block w-6 h-6 rounded-full border border-gray-300" 
                                  style="background-color: {{ $serviceCategory->color }}"></span>
                            <span class="font-medium text-gray-900">{{ $serviceCategory->color }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Total de Serviços</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $serviceCategory->services->count() }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Criada em</div>
                        <div class="text-sm font-medium text-gray-900">{{ $serviceCategory->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Services List -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Serviços nesta Categoria</h3>
                    
                    @if($serviceCategory->services->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($serviceCategory->services as $service)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $service->name }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $service->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                    @if($service->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($service->description, 80) }}</p>
                                    @endif
                                    <div class="text-sm text-gray-500">
                                        <div>Tipo: {{ $service->unit_type_label }}</div>
                                        <div class="font-medium text-indigo-600">{{ $service->formatted_price }}</div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('services.edit', $service) }}" 
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="bi bi-tools text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Nenhum serviço cadastrado nesta categoria.</p>
                            <a href="{{ route('services.create', ['category_id' => $serviceCategory->id]) }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Criar Serviço
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

