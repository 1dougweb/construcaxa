<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Papéis e Permissões') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($roles as $role)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                                <div class="p-4">
                                    <form action="{{ route('admin.permissions.roles.permissions', $role) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach ($permissions as $perm)
                                                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" @checked($role->hasPermissionTo($perm->name)) class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                                                    <span>{{ $perm->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <x-button-loading variant="primary" type="submit">{{ __('Salvar') }}</x-button-loading>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


