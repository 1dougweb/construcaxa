<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ProjectBudget;
use App\Models\Inspection;
use App\Models\ProjectPhoto;

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

        $budgets = ProjectBudget::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $inspections = Inspection::where('client_id', $client->id)
            ->orderByDesc('inspection_date')
            ->limit(5)
            ->get();

        $photos = ProjectPhoto::whereHas('project', function ($q) use ($client) {
                $q->where('client_id', $client->id);
            })
            ->latest()
            ->limit(12)
            ->get();

        $stats = [
            'total_projects' => Project::where('client_id', $client->id)->count(),
            'active_projects' => Project::where('client_id', $client->id)->where('status', 'in_progress')->count(),
            'total_contracts' => Contract::where('client_id', $client->id)->count(),
            'active_contracts' => Contract::where('client_id', $client->id)->where('status', 'active')->count(),
            'total_budgets' => ProjectBudget::where('client_id', $client->id)->count(),
            'under_review_budgets' => ProjectBudget::where('client_id', $client->id)->where('status', 'under_review')->count(),
            'approved_budgets' => ProjectBudget::where('client_id', $client->id)->where('status', 'approved')->count(),
            'total_inspections' => Inspection::where('client_id', $client->id)->count(),
        ];

        return view('client.dashboard', compact('client', 'projects', 'contracts', 'budgets', 'inspections', 'photos', 'stats'));
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


