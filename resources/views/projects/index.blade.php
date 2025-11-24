<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Obras') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Obras</h1>
            @if(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin']))
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">Nova Obra</a>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md border border-gray-200 dark:border-gray-700">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($projects as $project)
            <li class="relative overflow-hidden rounded-md">
                @php
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status','done')->count();
                    $computedProgress = $totalTasks > 0 ? (int) round(($doneTasks / max(1,$totalTasks)) * 100) : (int) $project->progress_percentage;
                @endphp
                <a href="{{ route('projects.show', $project) }}" class="block px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="absolute inset-0 bg-white dark:bg-gray-800 rounded-md" style="width: 100%;"></div>
                    <div class="absolute inset-y-0 left-0 bg-green-100 dark:bg-green-900/30 rounded-md" style="width: {{ $computedProgress }}%;"></div>
                    <div class="relative flex items-center justify-between">
                        <p class="text-sm font-medium text-green-700 dark:text-green-400">{{ $project->name }} <span class="text-gray-500 dark:text-gray-400">({{ $project->code }})</span></p>
                        <p class="text-sm text-indigo-700 dark:text-indigo-400">{{ $computedProgress }}% @if($totalTasks>0)· {{ $doneTasks }}/{{ $totalTasks }} tarefas @endif</p>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-6 text-sm text-gray-500 dark:text-gray-400">Nenhuma obra cadastrada.</li>
            @endforelse
        </ul>
        </div>
        <div class="mt-4">{{ $projects->links() }}</div>
    </div>
</x-app-layout>


