<div>
    <!-- Formulario para registrar nuevo lote -->
    <div class="w-full bg-white shadow-md p-6 dark:bg-gray-800">
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
