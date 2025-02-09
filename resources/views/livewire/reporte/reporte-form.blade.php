<!-- Formulario para slicitar un nuevo reporte -->
<!-- Formulario de despacho -->
<div class="w-full bg-white shadow-md p-6 dark:bg-gray-800">

    <div class="mb-6">
        <div class="py-12 flex justify-center items-center">
            <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg p-6 relative">

                @if ($formView)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-3/4 shadow-lg overflow-y-auto max-h-3/4">
                        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">Solicitar un nuevo reporte</h1>
            
                        <div class="max-h-[75vh] overflow-y-auto">
                            <form wire:submit.prevent="guardarDespacho" class="space-y-6">
                                
                                <!-- Tipo de Reporte -->
                                <div>
                                    <label for="tipo_reporte" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Tipo de Reporte</label>
                                    <select id="tipo_reporte" wire:model="tipo_reporte"
                                        class="w-full p-2 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                        <option value="">Seleccione un tipo</option>
                                        <option value="hospitalizado">Hospitalizado</option>
                                        <option value="emergencia">Emergencia</option>
                                        <option value="quirofano">Quirófano</option>
                                        <option value="via_oral">Vía Oral</option>
                                        <option value="general">General de Medicamentos</option>
                                    </select>
                                    @error('tipo_reporte') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
            
                                <!-- Fechas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="fecha_inicio" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Fecha Inicio</label>
                                        <input type="date" id="fecha_inicio" wire:model="fecha_inicio"
                                            class="w-full p-2 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                        @error('fecha_inicio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
            
                                    <div>
                                        <label for="fecha_fin" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Fecha Fin</label>
                                        <input type="date" id="fecha_fin" wire:model="fecha_fin"
                                            class="w-full p-2 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                        @error('fecha_fin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
            
                                <!-- Botones -->
                                <div class="flex justify-end gap-4">
                                    <button type="button" wire:click="cancelar" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Generar Reporte</button>
                                </div>
            
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="absolute top-6 right-1 ">
                    <button type="button" wire:click="form"
                        class=" bg-blue-500 hover:bg-red-600 text-white px-6 py-3 rounded-full shadow-md transition duration-300 text-lg font-simbol flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-pdf mr-2" viewBox="0 0 16 16">
                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                            <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                        </svg>
                        Nuevo reporte
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

