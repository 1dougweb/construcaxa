<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurações do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Google Maps API</h3>
                            
                            <div class="mb-4">
                                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Chave da API do Google Maps
                                </label>
                                <input 
                                    type="text" 
                                    name="google_maps_api_key" 
                                    id="google_maps_api_key"
                                    value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="AIzaSyBNLrJhOMz6idD05pzfn5lhA-TAw-mAZCU"
                                >
                                @error('google_maps_api_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    Chave necessária para exibir mapas no dashboard. 
                                    <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" 
                                       target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-800">
                                        Obter chave da API
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Servidor de Licenças</h3>
                            
                            <div class="mb-4">
                                <label for="license_server_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL do Servidor de Licenças
                                </label>
                                <input 
                                    type="url" 
                                    name="license_server_url" 
                                    id="license_server_url"
                                    value="{{ old('license_server_url', $settings['license_server_url']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="https://license-server.example.com"
                                >
                                @error('license_server_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    URL do servidor de licenças para validação.
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="license_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    Chave da API do Servidor de Licenças
                                </label>
                                <input 
                                    type="password" 
                                    name="license_api_key" 
                                    id="license_api_key"
                                    value="{{ old('license_api_key', $settings['license_api_key']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Cole aqui a chave da API"
                                >
                                @error('license_api_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    Chave da API para autenticação no servidor de licenças. Esta chave é armazenada de forma segura no servidor.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>





