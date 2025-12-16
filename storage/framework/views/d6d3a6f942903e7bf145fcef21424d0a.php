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
    <div class="p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Título e Botões acima da tabela (fora do card) -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    <?php echo e(__('Produtos')); ?>

                </h2>
                <div class="flex items-center space-x-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view categories')): ?>
                    <a href="<?php echo e(route('categories.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <?php echo e(__('Gerenciar Categorias')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create products')): ?>
                    <button 
                        onclick="openOffcanvas('product-offcanvas')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <?php echo e(__('Novo Produto')); ?>

                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('product-list');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-531600022-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Produto -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'product-offcanvas','title' => 'Novo Produto','width' => 'w-full md:w-[600px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'product-offcanvas','title' => 'Novo Produto','width' => 'w-full md:w-[600px]']); ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('product-form', ['product' => null]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-531600022-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $attributes = $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $component = $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>

    <!-- Offcanvas para Movimentação de Estoque -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'stock-movement-offcanvas','title' => 'Movimentação de Estoque','width' => 'w-full md:w-[500px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'stock-movement-offcanvas','title' => 'Movimentação de Estoque','width' => 'w-full md:w-[500px]']); ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('stock-movement-form', ['productId' => null]);

$key = 'stock-movement-form';

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-531600022-2', 'stock-movement-form');

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $attributes = $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $component = $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
    (function() {
        const ensureNotificationContainer = () => {
            if (!document.getElementById('notifications')) {
                const container = document.createElement('div');
                container.id = 'notifications';
                container.className = 'fixed bottom-0 right-0 m-6 z-50';
                document.body.appendChild(container);
            }
        };

        const showNotification = (message, type = 'info', duration = 3000) => {
            ensureNotificationContainer();
            const container = document.getElementById('notifications');
            const notification = document.createElement('div');
            let bgColor = 'bg-blue-500';
            if (type === 'success') bgColor = 'bg-green-500';
            if (type === 'error') bgColor = 'bg-red-500';
            if (type === 'warning') bgColor = 'bg-yellow-500';

            notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg mb-3 opacity-100 transition-opacity duration-500`;
            notification.textContent = message || 'Operação concluída';

            container.appendChild(notification);
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, duration);
        };

        const getProductFormComponent = () => {
            const offcanvas = document.getElementById('product-offcanvas');
            if (!offcanvas) return null;
            const componentEl = offcanvas.querySelector('[wire\\:id]');
            return componentEl ? Livewire.find(componentEl.getAttribute('wire:id')) : null;
        };

        const onProductSaved = (detail = {}) => {
            console.log('[products] product-saved recebido', detail);
            closeOffcanvas('product-offcanvas');
            
            // Atualizar lista
            if (window.Livewire) {
                window.Livewire.dispatch('refresh-products');
            }
            
            // Mostrar notificação
            if (detail.message) {
                if (window.showNotification) {
                    window.showNotification(detail.message, detail.type || 'success', 4000);
                } else {
                    console.warn('[products] showNotification não disponível, mensagem:', detail.message);
                }
            }
        };

        // Browser event (dispatch do Livewire 3)
        window.addEventListener('product-saved', (event) => {
            console.log('[products] Evento product-saved recebido (window)', event);
            onProductSaved(event.detail || {});
        });

        // Fallback explícito do browser para evitar dependência de websockets
        window.addEventListener('product-saved-browser', (event) => {
            console.log('[products] Evento product-saved-browser recebido (window)', event);
            onProductSaved(event.detail || {});
        });

        // Listener direto via Livewire.on (fallback)
        if (window.Livewire) {
            window.Livewire.on('product-saved', (detail = {}) => {
                console.log('[products] Evento product-saved recebido (Livewire.on)', detail);
                onProductSaved(detail);
            });
            window.Livewire.on('product-saved-browser', (detail = {}) => {
                console.log('[products] Evento product-saved-browser recebido (Livewire.on)', detail);
                onProductSaved(detail);
            });
        }
        
        // Listener para eventos Livewire customizados
        document.addEventListener('livewire:init', () => {
            console.log('[products] Livewire inicializado, configurando listeners');
        });

        const handleEditProduct = (productId) => {
            if (!productId) return;

            const offcanvas = document.getElementById('product-offcanvas');
            const title = offcanvas ? offcanvas.querySelector('h2') : null;
            if (title) title.textContent = 'Editar Produto';

            openOffcanvas('product-offcanvas');

            // SEMPRE resetar o formulário antes de carregar um novo produto
            // Isso evita contaminação de estado entre produtos
            const component = getProductFormComponent();
            if (component) {
                // Resetar primeiro
                component.call('resetForm');
                
                // Aguardar um pouco para garantir que o reset foi processado
            setTimeout(() => {
                    // Agora carregar o produto
                    component.call('loadProduct', productId);
                }, 100);
            }
        };

        Livewire.on('edit-product', ({ id }) => handleEditProduct(id));

        window.addEventListener('edit-product', (event) => {
            const productId = event.detail?.id;
            if (productId) handleEditProduct(productId);
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="product-offcanvas"]') && !e.target.closest('[onclick*="edit-product"]')) {
                const offcanvas = document.getElementById('product-offcanvas');
                const title = offcanvas ? offcanvas.querySelector('h2') : null;
                if (title) title.textContent = 'Novo Produto';

                const component = getProductFormComponent();
                if (component) Livewire.dispatch('reset-product-form');
            }
        });

        // Handler para abrir movimentação de estoque
        window.addEventListener('open-stock-movement', (event) => {
            const productId = event.detail?.productId;
            if (productId) {
                const offcanvas = document.getElementById('stock-movement-offcanvas');
                if (offcanvas) {
                    // Aguardar um pouco para garantir que o componente está disponível
                    setTimeout(() => {
                        const componentEl = offcanvas.querySelector('[wire\\:id]');
                        if (componentEl) {
                            const component = Livewire.find(componentEl.getAttribute('wire:id'));
                            if (component) {
                                // Carregar produto
                                component.call('loadProduct', productId);
                            }
                        }
                    }, 100);
                }
            }
        });

        // Listener para quando a movimentação for salva
        window.addEventListener('stock-movement-saved', (event) => {
            const offcanvas = document.getElementById('stock-movement-offcanvas');
            if (offcanvas) {
                const componentEl = offcanvas.querySelector('[wire\\:id]');
                if (componentEl) {
                    const component = Livewire.find(componentEl.getAttribute('wire:id'));
                    if (component) {
                        component.call('resetForm');
                    }
                }
            }
            closeOffcanvas('stock-movement-offcanvas');
            if (window.Livewire) {
                window.Livewire.dispatch('refresh-products');
            }
            if (event.detail?.message && window.showNotification) {
                window.showNotification(event.detail.message, 'success', 4000);
            }
        });

        // Resetar formulário quando o offcanvas for fechado
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="closeOffcanvas"]') || e.target.closest('[id="stock-movement-offcanvas-backdrop"]')) {
                const offcanvas = document.getElementById('stock-movement-offcanvas');
                if (offcanvas && offcanvas.classList.contains('translate-x-0')) {
                    setTimeout(() => {
                        const componentEl = offcanvas.querySelector('[wire\\:id]');
                        if (componentEl) {
                            const component = Livewire.find(componentEl.getAttribute('wire:id'));
                            if (component) {
                                component.call('resetForm');
                            }
                        }
                    }, 300);
                }
            }
        });
    })();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/products/index.blade.php ENDPATH**/ ?>