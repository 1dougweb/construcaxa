<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => false,
    'title' => 'Confirmar Ação',
    'message' => 'Tem certeza que deseja realizar esta ação?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmAction' => '',
    'cancelAction' => '',
    'type' => 'danger' // danger, warning, info
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'show' => false,
    'title' => 'Confirmar Ação',
    'message' => 'Tem certeza que deseja realizar esta ação?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmAction' => '',
    'cancelAction' => '',
    'type' => 'danger' // danger, warning, info
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $iconClasses = match($type) {
        'danger' => 'bg-red-100 text-red-600',
        'warning' => 'bg-yellow-100 text-yellow-600',
        'info' => 'bg-blue-100 text-blue-600',
        default => 'bg-red-100 text-red-600',
    };
    
    $buttonClasses = match($type) {
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        'info' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        default => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    };
    
    $icon = match($type) {
        'danger' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    };
?>

<!--[if BLOCK]><![endif]--><?php if($show): ?>
<div 
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: block;"
>
    <!-- Backdrop com backdrop-filter -->
    <div 
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
        style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        <?php if($cancelAction): ?>
            wire:click="<?php echo e($cancelAction); ?>"
        <?php else: ?>
            @click="show = false"
        <?php endif; ?>
    ></div>

    <!-- Modal -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div 
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full <?php echo e($iconClasses); ?> sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <?php echo $icon; ?>

                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            <?php echo e($title); ?>

                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                <?php echo e($message); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <!--[if BLOCK]><![endif]--><?php if($confirmAction): ?>
                    <button 
                        type="button" 
                        class="inline-flex w-full justify-center rounded-md border border-transparent <?php echo e($buttonClasses); ?> px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:click="<?php echo e($confirmAction); ?>"
                        wire:loading.attr="disabled"
                        wire:target="<?php echo e($confirmAction); ?>"
                    >
                        <span wire:loading.remove wire:target="<?php echo e($confirmAction); ?>"><?php echo e($confirmText); ?></span>
                        <span wire:loading wire:target="<?php echo e($confirmAction); ?>" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processando...
                        </span>
                    </button>
                <?php else: ?>
                    <button 
                        type="button" 
                        class="inline-flex w-full justify-center rounded-md border border-transparent <?php echo e($buttonClasses); ?> px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="$dispatch('confirm-action')"
                    >
                        <?php echo e($confirmText); ?>

                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <!--[if BLOCK]><![endif]--><?php if($cancelAction): ?>
                    <button 
                        type="button" 
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click="<?php echo e($cancelAction); ?>"
                    >
                        <?php echo e($cancelText); ?>

                    </button>
                <?php else: ?>
                    <button 
                        type="button" 
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="show = false"
                    >
                        <?php echo e($cancelText); ?>

                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/modal-confirm.blade.php ENDPATH**/ ?>