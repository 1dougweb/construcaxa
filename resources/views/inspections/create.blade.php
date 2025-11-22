<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Vistoria') }}
        </h2>
    </x-slot>

    <div class="p-4">
        @livewire('inspection-form')
    </div>
</x-app-layout>

