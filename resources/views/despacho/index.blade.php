<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Despacho') }}
        </h2>
    </x-slot>

    <!-- Formulario para registrar nuevo lote -->
    @livewire('Despacho.DespachoForm')

    <!-- Componente de tabla -->
    @livewire('Despacho.DespachoTable')

    <!-- Componente de tabla quirofano-->
    @livewire('Despacho.DespachoQuirofano')


</x-app-layout>