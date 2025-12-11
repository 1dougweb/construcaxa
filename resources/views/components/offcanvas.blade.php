@props(['id', 'title', 'width' => 'w-96', 'show' => false])

@php
    $showClass = $show ? 'translate-x-0' : 'translate-x-full';
@endphp

<!-- Backdrop -->
<div 
    id="{{ $id }}-backdrop"
    class="fixed inset-0 bg-gray-900 dark:bg-black bg-opacity-50 dark:bg-opacity-70 backdrop-blur-sm z-40 transition-opacity duration-300 opacity-0 pointer-events-none"
    style="display: none;"
    onclick="if(typeof window.closeOffcanvas === 'function') { window.closeOffcanvas('{{ $id }}'); } else if(typeof closeOffcanvas === 'function') { closeOffcanvas('{{ $id }}'); }"
></div>

<!-- Offcanvas -->
<div 
    id="{{ $id }}"
    class="fixed right-0 top-0 h-full {{ $width }} bg-white dark:bg-gray-800 shadow-xl z-50 transform transition-transform duration-300 ease-in-out translate-x-full overflow-y-auto"
    style="display: none;"
>
    <!-- Header -->
    <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between z-10">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h2>
        <button 
            onclick="if(typeof window.closeOffcanvas === 'function') { window.closeOffcanvas('{{ $id }}'); } else if(typeof closeOffcanvas === 'function') { closeOffcanvas('{{ $id }}'); }"
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
        {{ $slot }}
    </div>
</div>

