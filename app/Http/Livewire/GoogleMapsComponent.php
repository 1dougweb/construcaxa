<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Services\GoogleMapsService;
use Livewire\Component;

class GoogleMapsComponent extends Component
{
    public $projects = [];
    public $apiKey = '';
    public $mapCenter = ['lat' => -14.2350, 'lng' => -51.9253]; // Centro do Brasil

    public function mount()
    {
        $googleMapsService = new GoogleMapsService();
        $this->apiKey = $googleMapsService->getApiKey();
        
        $this->loadProjects();
    }

    public function loadProjects()
    {
        // Carregar projetos em andamento com coordenadas
        $this->projects = Project::where('status', 'in_progress')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('client')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'code' => $project->code,
                    'os_number' => $project->os_number,
                    'address' => $project->address,
                    'latitude' => (float) $project->latitude,
                    'longitude' => (float) $project->longitude,
                    'status' => $project->status,
                    'client_name' => $project->client ? $project->client->name : 'N/A',
                    'progress_percentage' => $project->progress_percentage,
                    'url' => route('projects.show', $project->slug),
                ];
            })
            ->toArray();

        // Ajustar centro do mapa se houver projetos
        if (!empty($this->projects)) {
            $avgLat = collect($this->projects)->avg('latitude');
            $avgLng = collect($this->projects)->avg('longitude');
            $this->mapCenter = ['lat' => $avgLat, 'lng' => $avgLng];
        }
    }

    public function render()
    {
        return view('livewire.google-maps-component');
    }
}
