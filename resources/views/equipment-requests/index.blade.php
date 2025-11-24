<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisições de Equipamento') }}
            </h2>
            <div class="flex items-center space-x-4">
                @can('create service-orders')
                <a href="{{ route('equipment-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Nova Requisição') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6">
                        <form method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-64">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Buscar por número..." 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                            </div>
                            <div>
                                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todos os status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluído</option>
                                </select>
                            </div>
                            <div>
                                <select name="type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todos os tipos</option>
                                    <option value="loan" {{ request('type') === 'loan' ? 'selected' : '' }}>Empréstimo</option>
                                    <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Devolução</option>
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                            <a href="{{ route('equipment-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        </form>
                    </div>

                    @if($equipmentRequests->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma requisição encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando uma nova requisição de equipamento.</p>
                            <div class="mt-6">
                                @can('create service-orders')
                                <a href="{{ route('equipment-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Nova Requisição') }}
                                </a>
                                @endcan
                            </div>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Funcionário</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    @if(auth()->user()->can('view service-orders') || auth()->user()->can('edit service-orders'))
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($equipmentRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('equipment-requests.show', $request) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                #{{ $request->number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->employee->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $request->type === 'loan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $request->type === 'loan' ? 'Empréstimo' : 'Devolução' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($request->status)
                                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                                    @case('approved') bg-blue-100 text-blue-800 @break
                                                    @case('rejected') bg-red-100 text-red-800 @break
                                                    @case('completed') bg-green-100 text-green-800 @break
                                                @endswitch">
                                                @switch($request->status)
                                                    @case('pending') Pendente @break
                                                    @case('approved') Aprovado @break
                                                    @case('rejected') Rejeitado @break
                                                    @case('completed') Concluído @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        @if(auth()->user()->can('view service-orders') || auth()->user()->can('edit service-orders'))
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                @can('view service-orders')
                                                <a href="{{ route('equipment-requests.show', $request) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @endcan
                                                @can('edit service-orders')
                                                    @if($request->status === 'pending')
                                                    <a href="{{ route('equipment-requests.edit', $request) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    @endif
                                                @endcan
                                                @can('view service-orders')
                                                <a href="{{ route('equipment-requests.pdf', $request) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" target="_blank">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $equipmentRequests->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
