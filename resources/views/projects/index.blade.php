<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Obras') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Obras</h1>
            @if(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin']))
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">Nova Obra</a>
            @endif
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($projects as $project)
            <li class="relative overflow-hidden rounded-md">
                @php
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status','done')->count();
                    $computedProgress = $totalTasks > 0 ? (int) round(($doneTasks / max(1,$totalTasks)) * 100) : (int) $project->progress_percentage;
                @endphp
                <a href="{{ route('projects.show', $project) }}" class="block px-4 py-4">
                    <div class="absolute inset-0 bg-white rounded-md" style="width: 100%;"></div>
                    <div class="absolute inset-y-0 left-0 bg-green-100 rounded-md" style="width: {{ $computedProgress }}%;"></div>
                    <div class="relative flex items-center justify-between">
                        <p class="text-sm font-medium text-green-700">{{ $project->name }} <span class="text-gray-500">({{ $project->code }})</span></p>
                        <p class="text-sm text-indigo-700">{{ $computedProgress }}% @if($totalTasks>0)· {{ $doneTasks }}/{{ $totalTasks }} tarefas @endif</p>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-6 text-sm text-gray-500">Nenhuma obra cadastrada.</li>
            @endforelse
        </ul>
        </div>
        <div class="mt-4">{{ $projects->links() }}</div>
    </div>
</x-app-layout>


