<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Papéis e Permissões') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($roles as $role)
                            <div class="border rounded-md">
                                <div class="px-4 py-3 border-b font-medium">{{ $role->name }}</div>
                                <div class="p-4">
                                    <form action="{{ route('admin.permissions.roles.permissions', $role) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach ($permissions as $perm)
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" @checked($role->hasPermissionTo($perm->name)) class="rounded border-gray-300">
                                                    <span>{{ $perm->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <x-button type="submit">{{ __('Salvar') }}</x-button>
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


