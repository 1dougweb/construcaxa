<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notificações') }}
            </h2>
            <div class="flex items-center space-x-4">
                @if(auth()->user()->notifications()->unread()->count() > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="bi bi-check-all mr-2"></i>
                        Marcar todas como lidas
                    </button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 dark:bg-gray-800">
                    <!-- Filtros -->
                    <div class="mb-6 flex items-center gap-4">
                        <a href="{{ route('notifications.index') }}" 
                           class="px-4 py-2 rounded-md text-sm font-medium {{ !request('filter') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Todas
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                           class="px-4 py-2 rounded-md text-sm font-medium {{ request('filter') === 'unread' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Não lidas
                            @if(auth()->user()->notifications()->unread()->count() > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs bg-indigo-500 text-white rounded-full">
                                    {{ auth()->user()->notifications()->unread()->count() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                           class="px-4 py-2 rounded-md text-sm font-medium {{ request('filter') === 'read' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            Lidas
                        </a>
                    </div>

                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <i class="bi bi-bell-slash text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma notificação encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if(request('filter') === 'unread')
                                    Você não tem notificações não lidas.
                                @elseif(request('filter') === 'read')
                                    Você não tem notificações lidas.
                                @else
                                    Você ainda não recebeu nenhuma notificação.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div 
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$notification->read_at ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : 'bg-white dark:bg-gray-800' }}"
                                    x-data="{ 
                                        deleting: false,
                                        read: {{ $notification->read_at ? 'true' : 'false' }},
                                        async deleteNotification() {
                                            if (!confirm('Tem certeza que deseja excluir esta notificação?')) return;
                                            this.deleting = true;
                                            try {
                                                const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
                                                const response = await fetch('{{ route('notifications.destroy', $notification) }}', {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'Content-Type': 'application/json',
                                                    },
                                                });
                                                if (response.ok) {
                                                    this.$el.remove();
                                                } else {
                                                    alert('Erro ao excluir notificação');
                                                }
                                            } catch (error) {
                                                console.error('Erro:', error);
                                                alert('Erro ao excluir notificação');
                                            } finally {
                                                this.deleting = false;
                                            }
                                        },
                                        async markAsRead() {
                                            if (this.read) return;
                                            try {
                                                const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
                                                const response = await fetch('{{ route('notifications.read', $notification) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'Content-Type': 'application/json',
                                                    },
                                                });
                                                if (response.ok) {
                                                    this.read = true;
                                                    this.$el.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20', 'border-indigo-200', 'dark:border-indigo-800');
                                                    this.$el.classList.add('bg-white', 'dark:bg-gray-800');
                                                }
                                            } catch (error) {
                                                console.error('Erro:', error);
                                            }
                                        }
                                    }"
                                    @if($notification->data && isset($notification->data['url']))
                                        onclick="window.location.href='{{ $notification->data['url'] }}'; $dispatch('mark-as-read')"
                                    @endif>
                                    <div class="flex items-start gap-4">
                                        <!-- Ícone -->
                                        <div class="flex-shrink-0 mt-1">
                                            @php
                                                $icons = [
                                                    'equipment_loan' => 'bi-tools',
                                                    'material_request' => 'bi-clipboard-check',
                                                    'budget_approval' => 'bi-receipt',
                                                    'proposal_approval' => 'bi-file-earmark-text',
                                                ];
                                                $icon = $icons[$notification->type] ?? 'bi-bell';
                                            @endphp
                                            <i class="bi {{ $icon }} text-2xl text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                        
                                        <!-- Conteúdo -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $notification->title }}
                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $notification->message }}
                                                    </p>
                                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                
                                                <!-- Indicador de não lida -->
                                                @if(!$notification->read_at)
                                                    <span class="flex-shrink-0 h-2 w-2 bg-indigo-500 rounded-full mt-2"></span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Ações -->
                                        <div class="flex-shrink-0 flex items-center gap-2">
                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('notifications.read', $notification) }}" class="inline" @submit.prevent="markAsRead()">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                                        title="Marcar como lida">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" @submit.prevent="deleteNotification()">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit"
                                                    :disabled="deleting"
                                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                                    title="Excluir">
                                                    <i class="bi bi-trash" x-show="!deleting"></i>
                                                    <i class="bi bi-arrow-repeat animate-spin" x-show="deleting" x-cloak></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

