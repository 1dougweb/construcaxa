<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'loading' => false,
    'type' => 'submit'
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
    'type' => 'submit'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<button 
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed'])); ?>

    <?php if($loading): ?> disabled <?php endif; ?>
    data-loading-button
>
    <span class="loading-spinner" style="display: <?php echo e($loading ? 'inline-block' : 'none'); ?>;">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </span>
    <?php echo e($slot); ?>

</button>

<?php if (! $__env->hasRenderedOnce('ebac4a21-e732-49d0-824d-e83fd85b7e72')): $__env->markAsRenderedOnce('ebac4a21-e732-49d0-824d-e83fd85b7e72'); ?>
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
                        if (spinner) {
                            spinner.style.display = 'inline-block';
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