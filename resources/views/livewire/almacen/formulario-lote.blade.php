<!-- Formulario de lote -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class=" p-6 rounded-lg w-3/4 bg-white shadow-lg overflow-y-auto max-h-[36rem]">
        <h3 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
            Registrar Nuevo Lote
        </h3>

        <form wire:submit.prevent="save" class="w-full flex flex-col justify-center">
            <div class="flex flex-row-2 w-full h-[24rem] overflow-y-auto space-x-2 justify-between ">
                <!-- Medicamento -->
                <div class="flex flex-col w-full px-4">
                    <div>
                        <label for="select_medicamento" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                            Medicamento:
                        </label>
                        @if ($select_medicamento != 'nuevo')
                            <select id="select_medicamento"
                            wire:model.live="select_medicamento"
                                class="block p-2 w-full border-gray-300 rounded-md shadow-sm  focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option value="search">Buscar existente</option>
                                <option value="nuevo">Nuevo Medicamento</option>
                            </select>
                            @error('select_medicamento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>

                    <!-- Campos para Nuevo Medicamento -->
                    @if($select_medicamento === 'nuevo')
                        <div>
                            <label for="nombre" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                                Nombre del Medicamento:
                            </label>
                            <input type="text" id="nombre" wire:model.lazy="nombre"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" 
                                placeholder="Nombre del medicamento">
                            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Presentación -->
                        <div class="">
                            <!-- Etiqueta del Campo -->
                            <label for="select_presentacion" 
                                class="mt-6  block text-sm font-medium text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                Presentación:
                            </label>

                            <!-- Selector de Opciones -->
                            @if ($select_presentacion != 'nuevo')
                                <select id="select_presentacion" wire:model.live="select_presentacion"
                                    class="w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                    <option value="search">Buscar existente</option>
                                    <option value="nuevo">Nueva Presentación</option>
                                </select>
                                <!-- Validación de Errores -->
                                @error('select_presentacion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            @endif
                        </div>

                        <!-- Campos para Nueva Presentación -->
                        @if ($select_presentacion === 'nuevo')
                            <div class="mb-4">
                                <label for="nueva_presentacion"
                                    class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                    Nueva Presentación:
                                </label>
                                <input type="text" id="nueva_presentacion" wire:model="nueva_presentacion"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" 
                                    placeholder="Ingrese la nueva presentación">
                                @error('nueva_presentacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            {{-- via de administracion --}}
                            <div class="mb-4">
                                <select id="via_administracion" wire:model.live="via_administracion"
                                    class="w-full mt-2 p-2 border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Seleccione una vía de administración</option>
                                    <option value="Oral">Oral</option>
                                    <option value="Sublingual">Sublingual</option>
                                    <option value="Tópica">Tópica</option>
                                    <option value="Oftálmica">Oftálmica</option>
                                    <option value="Ótica">Ótica</option>
                                    <option value="Nasal">Nasal</option>
                                    <option value="Inhalatoria">Inhalatoria</option>
                                    <option value="Rectal">Rectal</option>
                                    <option value="Vaginal">Vaginal</option>
                                    <option value="Intramuscular">Intramuscular</option>
                                    <option value="Intravenosa">Intravenosa</option>
                                    <option value="Subcutanea">Subcutánea</option>
                                </select>
                                @error('via_administracion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <!-- Campo de Búsqueda para Presentación Existente -->
                            <div class="mb-4">
                                <input type="text" id="tipo_presentacion_busqueda" wire:model.live="tipo_presentacion_busqueda"
                                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    placeholder="Buscar presentación por nombre">

                                <!-- Selector de Resultados -->
                                @if (!empty($tipos_presentacion) && count($tipos_presentacion) > 0)
                                    <select id="tipo_presentacion" wire:model.live="tipo_presentacion"
                                        class="w-full mt-2 p-2 border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="">Seleccione un tipo</option>
                                        @foreach ($tipos_presentacion as $id => $tipo)
                                            <option value="{{ $id }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- Mensaje de Resultados Vacíos -->
                                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                                        No se encontraron resultados.
                                    </p>
                                @endif
                                @error('tipo_presentacion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                @error('nueva_presentacion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                @error('via_administracion')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <!-- Final Presentación -->

                        <div>
                            <label for="unidad" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                                Unidad:
                            </label>
                            <input type="text" id="unidad" wire:model="unidad"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" placeholder="Concentración del medicamento, ej.: 500" required>
                            @error('unidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="medida" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                                Medida:
                            </label>
                            <input type="text" step="1" id="medida" wire:model="medida"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" placeholder="Unidad de medida, ej.: ml" required>
                            @error('medida') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <!-- Campo de búsqueda -->
                        <div>
                            <input
                            type="text" id="search" wire:model.live="search"
                                class="mt-2 block border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                placeholder="Buscar por nombre..."
                            >
                            <!-- Lista de resultados -->
                            @if ($medicamentos->isNotEmpty())
                                <ul class="mt-2 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                                    @foreach ($medicamentos as $medicamento)
                                        <li wire:click="selectSearch({{ $medicamento->id }})"
                                            class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 px-4 p-2">
                                            <span>{{ $medicamento->nombre }} - {{ $medicamento->unidad }}{{ $medicamento->medida }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-gray-500 dark:text-gray-400 mt-2">
                                    No se encontraron resultados.
                                </div>
                            @endif
                        </div>

                    @endif
                </div>

                <!-- Lote -->
                <div class="flex flex-col w-full px-4">
                    <div>
                        <label for="codigo_lote" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                            Código del Lote:
                        </label>
                        <input type="text" id="codigo_lote" wire:model="codigo_lote"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" placeholder="Escribe el código" required>
                        @error('codigo_lote') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cantidad" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                            Cantidad:
                        </label>
                        <input type="text" id="cantidad" wire:model="cantidad"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" placeholder="cantidad de unidades, ej.: 1000" required>
                        @error('cantidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fecha_vencimiento" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                            Fecha de Vencimiento:
                        </label>
                        <input type="date" id="fecha_vencimiento" wire:model="fecha_vencimiento"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                        @error('fecha_vencimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="origen" class="mt-3 block text-sm font-medium text-gray-700 dark:text-gray-300 after:content-['*'] after:text-red-500 after:ml-1">
                            Origen:
                        </label>
                        <input type="text" id="origen" wire:model.lazy="origen"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" 
                            placeholder="Origen del lote" required>
                        @error('origen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <!-- Botones -->
            <div class="flex justify-end mt-6 gap-4">
                <button type="button" wire:click="cancelar" 
                    class="bg-red-400 hover:bg-red-600 text-white my-2 px-4 py-2 rounded-md shadow-sm">
                    Cancelar
                </button>
                <button type="submit" 
                    class="bg-green-400 hover:bg-green-600 text-white my-2 px-4 py-2 rounded-md shadow-sm">
                    Guardar Lote
                </button>
            </div>
        </form>

    </div>
</div>