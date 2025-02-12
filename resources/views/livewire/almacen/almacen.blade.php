<div>
    <!-- Formulario para registrar nuevo lote -->
    <div class="w-full  shadow-md p-6 dark:bg-gray-800">
        <div class="mb-6">
            <div class="py-12 flex justify-center items-center">
                <div class="w-full max-w-4xl dark:bg-gray-800 rounded-lg p-6 relative">

                    @if ($formView)
                        @include('livewire.almacen.formulario-lote') {{-- Subcomponente formulario --}}
                    @else
                    <div class="absolute top-6 right-1">
                        <button type="button" wire:click="form"
                            class="bg-emerald-700 hover:bg-emerald-500 text-white px-6 py-3 rounded-full shadow-md transition duration-300 text-lg font-simbol flex items-center"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-lg mr-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                        </svg>
                            Nuevo lote
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
