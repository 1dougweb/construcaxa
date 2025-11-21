<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Categoria de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('service-categories.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-label for="name" value="{{ __('Nome') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="description" value="{{ __('Descrição') }}" />
                            <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="color" value="{{ __('Cor') }}" />
                            <div class="flex items-center space-x-4 mt-1">
                                <input type="color" 
                                       id="color" 
                                       name="color" 
                                       value="{{ old('color', '#6B7280') }}" 
                                       class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                <x-input id="color_text" 
                                         class="block w-full" 
                                         type="text" 
                                         name="color" 
                                         :value="old('color', '#6B7280')" 
                                         pattern="^#[0-9A-Fa-f]{6}$"
                                         placeholder="#6B7280" />
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Escolha uma cor para identificar esta categoria (formato hexadecimal: #RRGGBB)</p>
                            @error('color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Categoria ativa') }}</span>
                            </label>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('service-categories.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button>
                                {{ __('Criar') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sincronizar color picker com input de texto
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
        
        document.getElementById('color_text').addEventListener('input', function(e) {
            if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                document.getElementById('color').value = e.target.value;
            }
        });
    </script>
</x-app-layout>


