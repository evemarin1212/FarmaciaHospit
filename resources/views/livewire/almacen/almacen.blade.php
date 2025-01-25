<div>
    <!-- Formulario para registrar nuevo lote -->
    <div class="w-full bg-white shadow-md p-6 dark:bg-gray-800">

        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
            Gestión de Almacén
        </h1>

        <div class="mb-6">
            <div class="py-12 flex justify-center items-center">
                <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg p-6 relative">

                    @if ($formView)
                        @include('livewire.almacen.formulario-lote') {{-- Subcomponente formulario --}}
                    @else
                    <div class="absolute top-6 right-6">
                        <button
                            type="button"
                            wire:click="form"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full shadow-md transition duration-300 text-lg"
                        >
                            Registrar nuevo lote
                        </button>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Componente de Medicamentos -->
    @livewire('almacen.medicamentostable')

    <!-- Componente de Lotes -->
    @livewire('almacen.lotestable')
</div>
