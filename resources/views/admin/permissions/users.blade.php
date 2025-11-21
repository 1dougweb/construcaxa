<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuários e Papéis') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Nome') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Email') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700">{{ __('Papéis') }}</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-3 py-2">{{ $user->name }}</td>
                                        <td class="px-3 py-2">{{ $user->email }}</td>
                                        <td class="px-3 py-2">
                                            <form action="{{ route('admin.permissions.users.roles', $user) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach ($roles as $role)
                                                        <label class="inline-flex items-center gap-2 text-sm">
                                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($user->hasRole($role->name)) class="rounded border-gray-300">
                                                            <span>{{ $role->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <x-button type="submit">{{ __('Salvar') }}</x-button>
                                            </form>
                                        </td>
                                        <td class="px-3 py-2 text-right"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


