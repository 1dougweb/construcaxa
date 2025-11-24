<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Google Maps API</h3>
                            
                            <div class="mb-4">
                                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Chave da API do Google Maps
                                </label>
                                <input 
                                    type="text" 
                                    name="google_maps_api_key" 
                                    id="google_maps_api_key"
                                    value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="AIzaSyBNLrJhOMz6idD05pzfn5lhA-TAw-mAZCU"
                                >
                                @error('google_maps_api_key')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Chave necessária para exibir mapas no dashboard. 
                                    <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" 
                                       target="_blank" 
                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                        Obter chave da API
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-button-loading variant="primary" type="submit">
                                Salvar Configurações
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>





