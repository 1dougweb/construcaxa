<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Obra') }}
        </h2>
    </x-slot>

<div class="p-4 max-w-4xl">
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Código</label>
                    <input name="code" value="{{ old('code') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="planned">Planejada</option>
                        <option value="in_progress">Em andamento</option>
                        <option value="paused">Pausada</option>
                        <option value="completed">Concluída</option>
                        <option value="cancelled">Cancelada</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Endereço</label>
                <input name="address" value="{{ old('address') }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Início</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Previsão de término</label>
                    <input type="date" name="end_date_estimated" value="{{ old('end_date_estimated') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Equipe (funcionários)</label>
                <select name="employee_ids[]" multiple class="mt-1 block w-full border-gray-300 rounded-md">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notas</label>
                <textarea name="notes" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="mt-6">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Salvar</button>
        </div>
    </form>
</div>
</x-app-layout>


