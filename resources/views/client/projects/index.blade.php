<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Obras') }}
        </h2>
    </x-slot>

<div class="p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($projects as $project)
        <a href="{{ route('client.projects.show', $project) }}" class="block bg-white rounded-md shadow p-4 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-medium text-gray-900">{{ $project->name }}</div>
                    <div class="text-sm text-gray-500">Código: {{ $project->code }}</div>
                </div>
                <div class="text-sm text-gray-600">{{ $project->progress_percentage }}%</div>
            </div>
        </a>
        @empty
        <p class="text-sm text-gray-500">Nenhuma obra disponível.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $projects->links() }}</div>
</div>
</x-app-layout>


