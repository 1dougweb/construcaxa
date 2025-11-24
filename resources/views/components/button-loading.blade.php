@props([
    'loading' => false,
    'type' => 'submit'
])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed']) }}
    @if($loading) disabled @endif
    data-loading-button
>
    <span class="loading-spinner" style="display: {{ $loading ? 'inline-block' : 'none' }};">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </span>
    {{ $slot }}
</button>

@once
@push('scripts')
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
@endpush
@endonce

