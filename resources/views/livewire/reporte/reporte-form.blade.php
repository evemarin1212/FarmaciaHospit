<!-- Formulario para slicitar un nuevo reporte -->
<!-- Formulario de despacho -->
<div class="w-full bg-white shadow-md p-6 dark:bg-gray-800">

    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
        Generar Reportes
    </h1>

    <div class="mb-6">
        <div class="py-12 flex justify-center items-center">
            <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg p-6 relative">

                @if ($formView)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-3/4 shadow-lg overflow-y-auto max-h-3/4">
                            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
                                Solicitar un nuevo reporte
                            </h1>
                            <!-- Contenedor con scroll limitado -->
                            <div class="max-h-[75vh] overflow-y-auto">
                                <form wire:submit.prevent="guardarDespacho">

                                    <!-- Botones -->
                                    <div class="flex justify-end gap-4">
                                        <button type="button" wire:click="cancelar"
                                            class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancelar</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded-md">Guardar Despacho</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="absolute top-6 right-6">
                        <button type="button" wire:click="form"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full shadow-md transition duration-300 text-lg font-simbol">
                            + Nuevo Reporte
                        </button>
                    </div>
                @endif
                <!-- Mensaje de Éxito -->
                @if (session()->has('message'))
                    <div class="mt-4 text-green-600">{{ session('message') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>