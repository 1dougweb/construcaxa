<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'loading' => false,
    'type' => 'submit',
    'variant' => 'primary' // primary, success, danger, secondary
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
    'loading' => false,
    'type' => 'submit',
    'variant' => 'primary' // primary, success, danger, secondary
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:ring-indigo-500 active:bg-indigo-800 dark:active:bg-indigo-800',
        'success' => 'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600 focus:ring-green-500 active:bg-green-800 dark:active:bg-green-800',
        'danger' => 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500 active:bg-red-800 dark:active:bg-red-800',
        'secondary' => 'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600 focus:ring-gray-500 active:bg-gray-800 dark:active:bg-gray-800',
        default => 'bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:ring-indigo-500 active:bg-indigo-800 dark:active:bg-indigo-800',
    };
    
    $classes = $baseClasses . ' ' . $variantClasses;
?>

<button 
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge(['class' => $classes])); ?>

    <?php if($loading): ?> disabled <?php endif; ?>
    data-loading-button
>
    <span class="loading-spinner" style="display: <?php echo e($loading ? 'inline-block' : 'none'); ?>;">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </span>
    <span class="button-text"><?php echo e($slot); ?></span>
</button>

<?php if (! $__env->hasRenderedOnce('252755fd-430c-45b6-87c1-75c1d310e8a5')): $__env->markAsRenderedOnce('252755fd-430c-45b6-87c1-75c1d310e8a5'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('[data-loading-button]');
        buttons.forEach(function(button) {
            const form = button.closest('form');
            if (form && !form.hasAttribute('data-loading-initialized')) {
                form.setAttribute('data-loading-initialized', 'true');
                form.addEventListener('submit', function() {
                    const submitButton = form.querySelector('[data-loading-button]');
                    if (submitButton) {
                        const spinner = submitButton.querySelector('.loading-spinner');
                        const buttonText = submitButton.querySelector('.button-text');
                        if (spinner) {
                            spinner.style.display = 'inline-block';
                        }
                        if (buttonText) {
                            buttonText.style.opacity = '0.7';
                        }
                        submitButton.disabled = true;
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/components/button-loading.blade.php ENDPATH**/ ?>