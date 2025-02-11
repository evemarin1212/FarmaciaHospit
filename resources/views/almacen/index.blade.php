<x-app-layout>
    <div class="min-h-screen ">
        <div class=" pb-8">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Almacén') }}
                </h2>
            </x-slot>
            <div class="py-12">
                <!-- Componente Nuevo Lote -->
                <livewire:almacen.almacen />
            </div>

        </div>

    </div>

</x-app-layout>
