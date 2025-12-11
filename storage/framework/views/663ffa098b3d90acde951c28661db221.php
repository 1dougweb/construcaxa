<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Notificações')); ?>

            </h2>
            <div class="flex items-center space-x-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->notifications()->unread()->count() > 0): ?>
                <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="bi bi-check-all mr-2"></i>
                        Marcar todas como lidas
                    </button>
                </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 dark:bg-gray-800">
                    <!-- Filtros -->
                    <div class="mb-6 flex items-center gap-4">
                        <a href="<?php echo e(route('notifications.index')); ?>" 
                           class="px-4 py-2 rounded-md text-sm font-medium <?php echo e(!request('filter') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            Todas
                        </a>
                        <a href="<?php echo e(route('notifications.index', ['filter' => 'unread'])); ?>" 
                           class="px-4 py-2 rounded-md text-sm font-medium <?php echo e(request('filter') === 'unread' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            Não lidas
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->notifications()->unread()->count() > 0): ?>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-indigo-500 text-white rounded-full">
                                    <?php echo e(auth()->user()->notifications()->unread()->count()); ?>

                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>
                        <a href="<?php echo e(route('notifications.index', ['filter' => 'read'])); ?>" 
                           class="px-4 py-2 rounded-md text-sm font-medium <?php echo e(request('filter') === 'read' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            Lidas
                        </a>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->isEmpty()): ?>
                        <div class="text-center py-12">
                            <i class="bi bi-bell-slash text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma notificação encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('filter') === 'unread'): ?>
                                    Você não tem notificações não lidas.
                                <?php elseif(request('filter') === 'read'): ?>
                                    Você não tem notificações lidas.
                                <?php else: ?>
                                    Você ainda não recebeu nenhuma notificação.
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div 
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors <?php echo e(!$notification->read_at ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : 'bg-white dark:bg-gray-800'); ?>"
                                    x-data="{ 
                                        deleting: false,
                                        read: <?php echo e($notification->read_at ? 'true' : 'false'); ?>,
                                        async deleteNotification() {
                                            if (!confirm('Tem certeza que deseja excluir esta notificação?')) return;
                                            this.deleting = true;
                                            try {
                                                const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
                                                const response = await fetch('<?php echo e(route('notifications.destroy', $notification)); ?>', {
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
                                                const response = await fetch('<?php echo e(route('notifications.read', $notification)); ?>', {
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
                                    <?php if($notification->data && isset($notification->data['url'])): ?>
                                        onclick="window.location.href='<?php echo e($notification->data['url']); ?>'; $dispatch('mark-as-read')"
                                    <?php endif; ?>>
                                    <div class="flex items-start gap-4">
                                        <!-- Ícone -->
                                        <div class="flex-shrink-0 mt-1">
                                            <?php
                                                $icons = [
                                                    'equipment_loan' => 'bi-tools',
                                                    'material_request' => 'bi-clipboard-check',
                                                    'budget_approval' => 'bi-receipt',
                                                    'proposal_approval' => 'bi-file-earmark-text',
                                                ];
                                                $icon = $icons[$notification->type] ?? 'bi-bell';
                                            ?>
                                            <i class="bi <?php echo e($icon); ?> text-2xl text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                        
                                        <!-- Conteúdo -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        <?php echo e($notification->title); ?>

                                                    </h3>
                                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                        <?php echo e($notification->message); ?>

                                                    </p>
                                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                                    </p>
                                                </div>
                                                
                                                <!-- Indicador de não lida -->
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->read_at): ?>
                                                    <span class="flex-shrink-0 h-2 w-2 bg-indigo-500 rounded-full mt-2"></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Ações -->
                                        <div class="flex-shrink-0 flex items-center gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->read_at): ?>
                                                <form method="POST" action="<?php echo e(route('notifications.read', $notification)); ?>" class="inline" @submit.prevent="markAsRead()">
                                                    <?php echo csrf_field(); ?>
                                                    <button 
                                                        type="submit"
                                                        class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                                        title="Marcar como lida">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <form method="POST" action="<?php echo e(route('notifications.destroy', $notification)); ?>" class="inline" @submit.prevent="deleteNotification()">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            <?php echo e($notifications->links()); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/notifications/index.blade.php ENDPATH**/ ?>