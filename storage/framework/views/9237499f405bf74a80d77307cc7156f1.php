<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, success, danger
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'disabled' => false,
    'wireTarget' => 'save', // Target padrão para wire:loading
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
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, success, danger
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'disabled' => false,
    'wireTarget' => 'save', // Target padrão para wire:loading
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center font-semibold uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-md border border-transparent whitespace-nowrap';
    
    $variantClasses = [
        'primary' => 'bg-indigo-600 dark:bg-indigo-700 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:ring-indigo-500 active:bg-indigo-900',
        'secondary' => 'bg-gray-600 dark:bg-gray-700 text-white hover:bg-gray-700 dark:hover:bg-gray-600 focus:ring-gray-500 active:bg-gray-900',
        'success' => 'bg-green-600 dark:bg-green-700 text-white hover:bg-green-700 dark:hover:bg-green-600 focus:ring-green-500 active:bg-green-900',
        'danger' => 'bg-red-600 dark:bg-red-700 text-white hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500 active:bg-red-900',
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3 text-sm',
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
?>

<button 
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge(['class' => $classes])); ?>

    <?php if($disabled || $loading): ?> disabled <?php endif; ?>
    wire:loading.attr="disabled"
>
    <span wire:loading.remove wire:target="<?php echo e($attributes->get('wire:target', $wireTarget)); ?>" class="inline-flex items-center whitespace-nowrap gap-2">
        <?php echo e($slot); ?>

    </span>
    <span wire:loading wire:target="<?php echo e($attributes->get('wire:target', $wireTarget)); ?>" class="inline-flex items-center whitespace-nowrap gap-2">
        <svg class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="whitespace-nowrap">Salvando...</span>
    </span>
</button>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/button-loading.blade.php ENDPATH**/ ?>