<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Client;
use App\Models\Contract;

class ProjectController extends Controller
{
    public function dashboard()
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        if (!$client) {
            abort(403, 'Cliente não encontrado.');
        }

        $projects = Project::where('client_id', $client->id)
            ->with(['budgets', 'updates' => function($query) {
                $query->latest()->limit(5);
            }])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $contracts = Contract::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $stats = [
            'total_projects' => Project::where('client_id', $client->id)->count(),
            'active_projects' => Project::where('client_id', $client->id)->where('status', 'in_progress')->count(),
            'total_contracts' => Contract::where('client_id', $client->id)->count(),
            'active_contracts' => Contract::where('client_id', $client->id)->where('status', 'active')->count(),
        ];

        return view('client.dashboard', compact('client', 'projects', 'contracts', 'stats'));
    }

    public function index()
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        if (!$client) {
            abort(403, 'Cliente não encontrado.');
        }

        $projects = Project::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate(12);
        
        $contracts = Contract::where('client_id', $client->id)->get();
        
        return view('client.projects.index', compact('projects', 'contracts', 'client'));
    }

    public function show(Project $project)
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        if (!$client || $project->client_id !== $client->id) {
            abort(403);
        }
        
        $project->load(['updates.user', 'photos.user', 'budgets']);
        $contracts = Contract::where('client_id', $client->id)
            ->where('project_id', $project->id)
            ->get();
        
        return view('client.projects.show', compact('project', 'contracts', 'client'));
    }
}


