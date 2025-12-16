<x-app-layout>
    <div class="p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-between flex items-center justify-between mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Usuários e Papéis') }}
                </h2>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">{{ __('Nome') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</th>
                                    <th class="px-3 py-2 text-left font-medium text-gray-700 dark:text-gray-300">{{ __('Papéis') }}</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @foreach ($users as $user)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                                        <td class="px-3 py-2">
                                            <form action="{{ route('admin.permissions.users.roles', $user) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach ($roles as $role)
                                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($user->hasRole($role->name)) class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                                                            <span>{{ $role->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <x-button-loading variant="primary" type="submit">{{ __('Salvar') }}</x-button-loading>
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


