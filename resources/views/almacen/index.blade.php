<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Almacén') }}
        </h2>
    </x-slot>

    <!-- Componente Nuevo Lote -->
    <livewire:almacen.almacen />

</x-app-layout>
