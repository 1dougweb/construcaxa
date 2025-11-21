<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Serviços') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Gerenciar Serviços</h1>
            <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Serviço
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nome do serviço..." 
                           class="w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <div class="bg-white rounded-lg shadow-md border hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ $service->name }}</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $service->category->color }}"></span>
                                    <span class="text-sm text-gray-600">{{ $service->category->name }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($service->description)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($service->description, 100) }}</p>
                        @endif

                        <!-- Pricing Info -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <div class="text-sm text-gray-600 mb-1">Tipo de Cobrança</div>
                            <div class="font-medium text-indigo-600">{{ $service->unit_type_label }}</div>
                            <div class="text-lg font-bold text-gray-900 mt-1">{{ $service->formatted_price }}</div>
                            @if($service->minimum_price || $service->maximum_price)
                                <div class="text-xs text-gray-500 mt-1">
                                    @if($service->minimum_price)
                                        Mín: R$ {{ number_format($service->minimum_price, 2, ',', '.') }}
                                    @endif
                                    @if($service->minimum_price && $service->maximum_price) | @endif
                                    @if($service->maximum_price)
                                        Máx: R$ {{ number_format($service->maximum_price, 2, ',', '.') }}
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('services.edit', $service) }}" 
                               class="flex-1 text-center px-3 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Editar
                            </a>
                            <a href="{{ route('services.show', $service) }}" 
                               class="px-3 py-2 text-sm bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="bi bi-tools" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum serviço encontrado</h3>
                        <p class="text-gray-600 mb-4">Comece criando seu primeiro serviço.</p>
                        <a href="{{ route('services.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Criar Serviço
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($services->hasPages())
            <div class="mt-6">{{ $services->links() }}</div>
        @endif

        <!-- Quick Links -->
        <div class="mt-8 border-t pt-6">
            <div class="flex space-x-4">
                <a href="{{ route('service-categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="bi bi-folder mr-2"></i>
                    Gerenciar Categorias
                </a>
                <a href="{{ route('labor-types.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    <i class="bi bi-people mr-2"></i>
                    Tipos de Mão de Obra
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
