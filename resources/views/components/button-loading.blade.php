@props([
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, success, danger
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'disabled' => false,
    'wireTarget' => 'save', // Target padrão para wire:loading
])

@php
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
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled || $loading) disabled @endif
    wire:loading.attr="disabled"
>
    <span wire:loading.remove wire:target="{{ $attributes->get('wire:target', $wireTarget) }}" class="inline-flex items-center whitespace-nowrap gap-2">
        {{ $slot }}
    </span>
    <span wire:loading wire:target="{{ $attributes->get('wire:target', $wireTarget) }}" class="inline-flex items-center whitespace-nowrap gap-2">
        <svg class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="whitespace-nowrap">Salvando...</span>
    </span>
</button>
