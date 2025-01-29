<!-- Formulario de lote -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-3/4 shadow-lg overflow-y-auto max-h-3/4">
        <h3 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
            Registrar Nuevo Lote
        </h3>
        
        <form wire:submit.prevent="save" class="w-full flex flex-col justify-center">
            <div class="flex flex-row-2 w-full h-full space-x-2 justify-between ">
                <!-- Medicamento -->
                <div class="flex flex-col w-full px-4">
                    <div>
                        <label for="select_medicamento" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Medicamento:
                        </label>
                        <select id="select_medicamento"
                        wire:model.live="select_medicamento"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="search">Seleccionar Medicamento</option>
                            <option value="search">Buscar existente</option>
                            <option value="nuevo">Nuevo Medicamento</option>
                        </select>
                        @error('select_medicamento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Campos para Nuevo Medicamento -->
                    @if($select_medicamento === 'nuevo')
                        <div>
                            <label for="nombre" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre del Medicamento:
                            </label>
                            <input type="text" id="nombre" wire:model="nombre"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Presentación -->
                        <div class="mb-4">
                            <!-- Etiqueta del Campo -->
                            <label for="select_presentacion" 
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Presentación:
                            </label>

                            <!-- Selector de Opciones -->
                            <select id="select_presentacion" wire:model.live="select_presentacion"
                                class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option value="search">Seleccionar Presentación</option>
                                <option value="search">Buscar existente</option>
                                <option value="nuevo">Nueva Presentación</option>
                            </select>

                            <!-- Validación de Errores -->
                            @error('select_presentacion') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Campos para Nueva Presentación -->
                        @if ($select_presentacion === 'nuevo')
                            <div class="mb-4">
                                <label for="nueva_presentacion" 
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Nueva Presentación:
                                </label>
                                <input type="text" id="nueva_presentacion" wire:model="nueva_presentacion"
                                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    placeholder="Ingrese la nueva presentación">
                                @error('nueva_presentacion') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        @else
                            <!-- Campo de Búsqueda para Presentación Existente -->
                            <div class="mb-4">
                                <label for="tipo_medicamento_busqueda" 
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Buscar Presentación:
                                </label>
                                <input type="text" id="tipo_medicamento_busqueda" wire:model.live="tipo_medicamento_busqueda"
                                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    placeholder="Buscar tipo de medicamento">

                                <!-- Selector de Resultados -->
                                @if (!empty($tipos_medicamento))
                                    <select id="tipo_medicamento" wire:model="tipo_medicamento"
                                        class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="">Seleccione un tipo</option>
                                        @foreach ($tipos_medicamento as $id => $tipo)
                                            <option value="{{ $id }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- Mensaje de Resultados Vacíos -->
                                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                                        No se encontraron resultados.
                                    </p>
                                @endif
                            </div>
                        @endif
                        <!-- Final Presentación -->

                        <div>
                            <label for="unidad" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Unidad:
                            </label>
                            <input type="number" id="unidad" wire:model="unidad"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('unidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="medida" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Medida:
                            </label>
                            <input type="text" step="1" id="medida" wire:model="medida"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('medida') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @elseif ($select_medicamento === 'search')
                        <!-- Campo de búsqueda -->
                        <div>
                            <input
                            type="text" id="search" wire:model.live="search"
                                class="mt-2 block border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                placeholder="Buscar por nombre"
                            >
                            <!-- Lista de resultados -->
                            @if ($medicamentos->isNotEmpty())
                                <ul class="mt-2 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                                    @foreach ($medicamentos as $medicamento)
                                        <li wire:click="selectSearch({{ $medicamento->id }})"
                                            class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 px-4 p-2">
                                            <span>{{ $medicamento->nombre }} - {{ $medicamento->medida }}{{ $medicamento->unidad }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                            <div class="text-gray-500 dark:text-gray-400 mt-2">
                                No se encontraron resultados.
                            </div>
                            @endif

                            @if($medicamento_select)
                                <div
                                    class="mt-2 p-2 border border-green-500 rounded-md bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    Medicamento seleccionado: {{ App\Models\Medicamento::find($medicamento_select)->nombre }}
                                </div>
                            @endif
                        </div>

                    @endif
                </div>

                <!-- Lote -->
                <div class="flex flex-col w-full px-4">
                    <div>
                        <label for="codigo_lote" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Código del Lote:
                        </label>
                        <input type="text" id="codigo_lote" wire:model="codigo_lote"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('codigo_lote') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cantidad" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cantidad:
                        </label>
                        <input type="number" id="cantidad" wire:model="cantidad"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('cantidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fecha_vencimiento" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fecha de Vencimiento:
                        </label>
                        <input type="date" id="fecha_vencimiento" wire:model="fecha_vencimiento"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('fecha_vencimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="origen" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Origen:
                        </label>
                        <input type="text" id="origen" wire:model="origen"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        @error('origen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <!-- Botones -->
            <div class="flex justify-end mt-6 gap-4">
                <button type="button" wire:click="cancelar" 
                    class="bg-red-500 hover:bg-red-600 text-white my-2 px-4 py-2 rounded-md shadow-sm">
                    Cancelar
                </button>
                <button type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white my-2 px-4 py-2 rounded-md shadow-sm">
                    Guardar Lote
                </button>
            </div>
        </form>

    </div>
</div>