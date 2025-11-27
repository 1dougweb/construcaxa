<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vistoria #{{ $inspection->number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-indigo-600 text-white px-6 py-4">
                    <h1 class="text-2xl font-bold">Vistoria #{{ $inspection->number }}</h1>
                    <p class="text-indigo-100 mt-1">Cliente: {{ $inspection->client->name ?? $inspection->client->trading_name }}</p>
                    <p class="text-indigo-100">Data: {{ $inspection->inspection_date->format('d/m/Y') }}</p>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    @if($inspection->address)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Endereço</h2>
                            <p class="text-gray-700">{{ $inspection->address }}</p>
                            @if($inspection->latitude && $inspection->longitude)
                                <div id="map" class="mt-4 w-full h-64 rounded-lg border border-gray-300"></div>
                            @endif
                        </div>
                    @endif

                    @if($inspection->description)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Descrição</h2>
                            <p class="text-gray-700">{{ $inspection->description }}</p>
                        </div>
                    @endif

                    <!-- Ambientes -->
                    @foreach($inspection->environments as $environment)
                        <div class="mb-8 border border-gray-200 rounded-lg p-4">
                            <h2 class="text-xl font-semibold mb-4">{{ $environment->name }}</h2>
                            
                            @foreach($environment->items as $item)
                                <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0">
                                    <h3 class="text-lg font-medium mb-3">{{ $item->title }}</h3>
                                    
                                    <!-- Sub-items -->
                                    @if($item->subItems->count() > 0)
                                        <div class="space-y-3 mb-4">
                                            @foreach($item->subItems as $subItem)
                                                <div class="bg-gray-50 p-3 rounded-lg">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <h4 class="font-medium text-gray-900">{{ $subItem->title }}</h4>
                                                        <span class="px-2 py-1 text-xs rounded {{ 
                                                            $subItem->quality_rating === 'excellent' ? 'bg-green-100 text-green-800' : 
                                                            ($subItem->quality_rating === 'very_good' ? 'bg-blue-100 text-blue-800' : 
                                                            ($subItem->quality_rating === 'good' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'))
                                                        }}">
                                                            {{ $subItem->quality_label }}
                                                        </span>
                                                    </div>
                                                    @if($subItem->description)
                                                        <p class="text-sm text-gray-600 mb-1">{{ $subItem->description }}</p>
                                                    @endif
                                                    @if($subItem->observations)
                                                        <p class="text-sm text-gray-700 italic">{{ $subItem->observations }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Fotos -->
                                    @if($item->photos->count() > 0)
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                            @foreach($item->photos as $photo)
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto" class="w-full h-32 object-cover rounded">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <!-- Formulário de Solicitação -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold mb-4">Fazer uma Solicitação</h2>
                        <form method="POST" action="{{ route('inspections.public.request', $inspection->public_token) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Solicitação</label>
                                <select name="request_type" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                    <option value="alter_quality">Alterar Qualidade</option>
                                    <option value="add_observation">Adicionar Observação</option>
                                    <option value="request_change">Solicitar Alteração</option>
                                    <option value="other">Outro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                                <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Enviar Solicitação
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($inspection->latitude && $inspection->longitude)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>
        <script>
            function initMap() {
                const location = { lat: {{ $inspection->latitude }}, lng: {{ $inspection->longitude }} };
                const map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: location,
                });
                new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }
        </script>
    @endif
</body>
</html>


