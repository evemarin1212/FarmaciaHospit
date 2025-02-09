<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reporte') }}
        </h2>
    </x-slot>

    <!-- Formulario para slicitar un nuevo reporte -->
    @livewire('Reporte.ReporteForm')

    <!-- Componente de tabla Lote -->
    @livewire('Reporte.ReporteLote')


</x-app-layout>
