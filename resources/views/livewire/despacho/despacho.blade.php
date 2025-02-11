<div>
    <!-- Formulario para registrar nuevo lote -->
    <div class="w-full shadow-md p-6 dark:bg-gray-800">

        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
            Gestión de Despacho
        </h1>

        <div class="mb-6">
            <div class="py-12 flex justify-center items-center">
                <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg p-6 relative">

                        @include('livewire.despacho.despacho-form') {{-- Subcomponente formulario --}}

                </div>
            </div>
        </div>
    </div>

    <!-- Componente de tabla -->
    @livewire('Despacho.DespachoTable')

</div>
