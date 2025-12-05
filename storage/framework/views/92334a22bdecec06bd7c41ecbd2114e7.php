<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'title', 'width' => 'w-96', 'show' => false]));

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

foreach (array_filter((['id', 'title', 'width' => 'w-96', 'show' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $showClass = $show ? 'translate-x-0' : 'translate-x-full';
?>

<!-- Backdrop -->
<div 
    id="<?php echo e($id); ?>-backdrop"
    class="fixed inset-0 bg-gray-900 dark:bg-black bg-opacity-50 dark:bg-opacity-70 backdrop-blur-sm z-40 transition-opacity duration-300 opacity-0 pointer-events-none"
    style="display: none;"
    onclick="closeOffcanvas('<?php echo e($id); ?>')"
></div>

<!-- Offcanvas -->
<div 
    id="<?php echo e($id); ?>"
    class="fixed right-0 top-0 h-full <?php echo e($width); ?> bg-white dark:bg-gray-800 shadow-xl z-50 transform transition-transform duration-300 ease-in-out translate-x-full overflow-y-auto"
    style="display: none;"
>
    <!-- Header -->
    <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            <?php echo e($title); ?>

        </h2>
        <button 
            onclick="closeOffcanvas('<?php echo e($id); ?>')"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300 transition-colors"
            aria-label="Fechar"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div class="p-6">
        <?php echo e($slot); ?>

    </div>
</div>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/offcanvas.blade.php ENDPATH**/ ?>