<!-- Formulario de despacho -->
<div class="w-full bg-white shadow-md p-6 dark:bg-gray-800">

    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
        Gestión de Despacho
    </h1>

    <div class="mb-6">
        <div class="py-12 flex justify-center items-center">
            <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg p-6 relative">

                @if ($formView)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-3/4 shadow-lg overflow-y-auto max-h-3/4">
                            {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-1/2 shadow-lg max-h-[80vh] overflow-y-auto"> --}}
                            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
                                Registrar Nuevo Despacho
                            </h1>
                            <!-- Contenedor con scroll limitado -->
                            <div class="max-h-[75vh] overflow-y-auto">
                                <form wire:submit.prevent="guardarDespacho">
                                    <!-- Tipo de despacho -->
                                    <div class="mb-4">
                                        <label for="tipo_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                            Tipo de Despacho:
                                        </label>
                                        <select wire:model.live="tipo_despacho" id="tipo_despacho"
                                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                            <option value="">Seleccione</option>
                                            <option value="emergencia">Emergencia</option>
                                            <option value="hospitalizado">Hospitalizado</option>
                                            <option value="quirofano">Quirófano</option>
                                        </select>
                                        @error('tipo_despacho')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                            
                                    <div class="flex flex-row-2">
                                        <!-- Columna Izquierda: Información del Paciente -->
                                        @if (in_array($tipo_despacho, ['emergencia', 'hospitalizado']))
                                            <div class="flex flex-col w-full px-4">
                                                <div class="mb-4">
                                                    <label for="paciente_opcion" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Paciente:</label>
                                                    <select wire:model.live="paciente_opcion" id="paciente_opcion"
                                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        <option value="">Seleccione</option>
                                                        <option value="search">Buscar existente</option>
                                                        <option value="nuevo">Registrar nuevo</option>
                                                    </select>
                                                    @error('paciente_opcion')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                            
                                                @if ($paciente_opcion === 'nuevo')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                            Datos del Paciente:
                                                        </label>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                            <input type="text" wire:model.live="paciente_nombre" placeholder="Nombre"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                @error('paciente_nombre')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <input type="text" wire:model.live="paciente_apellido" placeholder="Apellido"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                @error('paciente_apellido')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <input type="text" wire:model.live="paciente_dni" placeholder="Cédula"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                @error('paciente_dni')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <select wire:model.live="paciente_estatus"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                <option value="">Estatus</option>
                                                                <option value="militar">Militar</option>
                                                                <option value="afiliado">Afiliado</option>
                                                                <option value="pna">PNA</option>
                                                            </select>
                                                            @error('paciente_estatus')
                                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @elseif ($paciente_opcion === 'search')
                                                    <div class="mb-4">
                                                        <input type="text" wire:model.live="search" id="paciente_search" placeholder="Buscar por cédula"
                                                            class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        @if ($pacientes->isNotEmpty())
                                                            <ul class="mt-2 p-2 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                                                                @foreach ($pacientes as $paciente)
                                                                    <li wire:click="selectPaciente({{ $paciente->id }})"
                                                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-600">
                                                                        {{ $paciente->dni }} - {{ $paciente->nombre }} {{ $paciente->apellido }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="mt-2 text-gray-500 dark:text-gray-400">No se encontraron pacientes</p>
                                                        @endif
                                                        @if($selectedPacienteId)
                                                            <div
                                                                class="mt-2 p-2 border border-green-500 rounded-md bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                Paciente seleccionado: {{ App\Models\Paciente::find($selectedPacienteId)->nombre }}
                                                            </div>
                                                        @endif
                                                        @error('selectedPacienteId') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>

                                        <!-- Columna Derecha: Despacho de Medicamentos -->
                                        <div class="flex flex-col w-full">
                                            <div class="mb-4">
                                                <label for="medicamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                    Seleccionar Medicamento:
                                                </label>
                                                <input type="text" id="tipo_medicamento_busqueda" wire:model.live="tipo_medicamento_busqueda"
                                                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                    placeholder="Buscar medicamento">

                                                @if (!empty($medicamentos_selec_bus))
                                                    <div class="flex space-x-2 mt-2">
                                                        <select id="medicamentos_selec_bus" wire:model.live="medicamento_id"
                                                            class="w-full mt-2 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                            <option value="">Seleccione un tipo</option>
                                                            @foreach ($medicamentos_selec_bus as $medicamento)
                                                                <option value="{{ $medicamento->id }}">
                                                                    {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="number" wire:model="cantidad_medicamento" placeholder="Cantidad"
                                                            class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        <button type="button" wire:click="agregarMedicamento"
                                                            class="w-1/4 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">
                                                            Agregar
                                                        </button>
                                                    </div>
                                                @else
                                                    <!-- Mensaje de Resultados Vacíos -->
                                                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                                                        No se encontraron resultados.
                                                    </p>
                                                @endif
                                                @error('medicamento_id') 
                                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                @enderror
                                                @error('cantidad_medicamento') 
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                                <!-- tabla sencilla -->
                                                @if (!empty($medicamentos_selec))
                                                    <ul class="mt-2 p-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md shadow-sm">
                                                        @foreach ($medicamentos_selec as $index => $medicamento)
                                                            <li class="flex justify-between items-center py-2 border-b border-gray-300 dark:border-gray-600">
                                                                <span>{{ $medicamento['nombre'] }} - {{ $medicamento['unidad'] }} {{ $medicamento['medida'] }} ({{ $medicamento['cantidad'] }})</span>
                                                                <button type="button" wire:click="eliminarMedicamento({{ $index }})" class="text-red-500">
                                                                    Eliminar
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            @endif
                            
                                            <!-- Observación en caso de Emergencia -->
                                            @if ($tipo_despacho === 'emergencia')
                                                <div class="mb-4">
                                                    <label for="observacion" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                        Observación:
                                                    </label>
                                                    <textarea wire:model="observacion" id="observacion" rows="3"
                                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200"></textarea>
                                                        @error('observacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            
                                    <!-- Quirofano: -->
                                    @if ($tipo_despacho === 'quirofano')
                                        <div class="mb-4">
                                            <label for="medicamento_solicitado"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Seleccionar Medicamento:
                                            </label>
                                            <input type="text" id="medicamento_solicitado" wire:model.live="medicamento_solicitado"
                                                class="w-full mt-2 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                placeholder="Buscar tipo de presentación">
                                            
                                            @if (!empty($medicamentos_solicitados_bus))
                                                <div class="flex space-x-2 mt-2">
                                                    <select id="medicamentos_selec_bus" wire:model.live="medicamento_solicitado_id"
                                                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                        <option value="">Seleccione un tipo</option>
                                                        @foreach ($medicamentos_solicitados_bus as $medicamento)
                                                            <option value="{{ $medicamento->id }}">
                                                                {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="number" wire:model="cantidad_medicamento" placeholder="Cantidad Solicitada"
                                                        class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                    <input type="number" wire:model="cantidad_medicamento_solicitado"
                                                        placeholder="Cantidad despachada"
                                                        class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                    <button type="button" wire:click="agregarMedicamentoSolicitado"
                                                        class="w-1/4 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">Agregar</button>
                                                </div>
                                            @else
                                                <!-- Mensaje de Resultados Vacíos -->
                                                <p class="mt-2 text-gray-500 dark:text-gray-400">
                                                    No se encontraron resultados.
                                                </p>
                                            @endif
                                            @error('medicamento_id') 
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                            @error('cantidad_medicamento') 
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                            <!-- tabla sencilla -->
                                            @if (!empty($medicamentos_solicitados_selec))
                                                <ul class="mt-2 p-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md shadow-sm">
                                                    @foreach ($medicamentos_solicitados_selec as $index => $medicamento)
                                                        <li class="flex justify-between items-center py-2 border-b border-gray-300 dark:border-gray-600">
                                                            <span>{{ $medicamento['nombre'] }} - {{ $medicamento['unidad'] }} {{ $medicamento['medida'] }}
                                                                ({{ $medicamento['cantidad'] }}) || ({{ $medicamento['cantidad_despacho'] }})</span>
                                                            <button type="button" wire:click="eliminarMedicamentoSolicitado({{ $index }})" class="text-red-500">
                                                                Eliminar
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif

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
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full shadow-md transition duration-300 text-lg">
                            Nuevo Despacho
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

{{-- <div class="max-h-[75vh] overflow-y-auto">
                                <form wire:submit.prevent="guardarDespacho">
                                    <!-- Tipo de despacho -->
                                    <div class="mb-4">
                                        <label for="tipo_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                            Tipo de Despacho:
                                        </label>
                                        <select wire:model.live="tipo_despacho" id="tipo_despacho"
                                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                            <option value="">Seleccione</option>
                                            <option value="emergencia">Emergencia</option>
                                            <option value="hospitalizado">Hospitalizado</option>
                                            <option value="quirofano">Quirófano</option>
                                        </select>
                                        @error('tipo_despacho')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Emergencia / Hospitalizado -->
                                    <div>
                                        @if (in_array($tipo_despacho, ['emergencia', 'hospitalizado']))
                                            <!-- Selección de Paciente -->
                                            <div class="mb-4">
                                                <label for="paciente_opcion"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Paciente:</label>
                                                <select wire:model.live="paciente_opcion" id="paciente_opcion"
                                                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                    <option value="">Seleccione</option>
                                                    <option value="search">Buscar existente</option>
                                                    <option value="nuevo">Registrar nuevo</option>
                                                </select>
                                                @error('paciente_opcion')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Registro de Nuevo Paciente -->
                                            @if ($paciente_opcion === 'nuevo')
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                        Datos del Paciente:
                                                    </label>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                                        <div>
                                                            <input type="text" wire:model.live="paciente_nombre" placeholder="Nombre"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            @error('paciente_nombre')
                                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <input type="text" wire:model.live="paciente_apellido" placeholder="Apellido"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            @error('paciente_apellido')
                                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <input type="text" wire:model.live="paciente_dni" placeholder="Cédula"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            @error('paciente_dni')
                                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <select wire:model.live="paciente_estatus"
                                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                <option value="">Estatus</option>
                                                                <option value="militar">Militar</option>
                                                                <option value="afiliado">Afiliado</option>
                                                                <option value="pna">PNA</option>
                                                            </select>
                                                            @error('paciente_estatus')
                                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buscar Paciente Existente -->
                                            @elseif ($paciente_opcion === 'search')
                                                <div class="mb-4">
                                                    <input type="text" wire:model.live="search" id="paciente_search" placeholder="Buscar por cédula"
                                                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                    @if ($pacientes->isNotEmpty())
                                                        <ul
                                                            class="mt-2 p-2 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                                                            @foreach ($pacientes as $paciente)
                                                                <li wire:click="selectPaciente({{ $paciente->id }})"
                                                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-600">
                                                                    {{ $paciente->dni }} - {{ $paciente->nombre }} {{ $paciente->apellido }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="mt-2 text-gray-500 dark:text-gray-400">No se encontraron pacientes</p>
                                                    @endif
                                                    @if($selectedPacienteId)
                                                        <div
                                                            class="mt-2 p-2 border border-green-500 rounded-md bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            Paciente seleccionado: {{ App\Models\Paciente::find($selectedPacienteId)->nombre }}
                                                        </div>
                                                    @endif

                                                    @error('selectedPacienteId') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif

                                            <!-- Medicamentos Asociados -->
                                            <div class="mb-4">
                                                <label for="medicamento_id"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Seleccionar
                                                    Medicamento:
                                                </label>

                                                <!-- Campo de Búsqueda para medicamentos existente -->
                                                <div class="mb-4">
                                                    <input type="text" id="tipo_medicamento_busqueda" wire:model.live="tipo_medicamento_busqueda"
                                                        class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                        placeholder="Buscar medicamento">

                                                    <!-- Selector de Resultados -->
                                                    @if (!empty($medicamentos_selec_bus))
                                                        <div class="flex space-x-2 mt-2">
                                                            <select id="medicamentos_selec_bus" wire:model.live="medicamento_id"
                                                                class= "w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                                <option value="">Seleccione un tipo</option>
                                                                @foreach ($medicamentos_selec_bus as $medicamento)
                                                                    <option value="{{ $medicamento->id }} ">
                                                                        {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" wire:model="cantidad_medicamento" placeholder="Cantidad"
                                                                class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            <button type="button" wire:click="agregarMedicamento"
                                                                class="w-1/4 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">
                                                                Agregar
                                                            </button>
                                                        </div>
                                                    @else
                                                        <!-- Mensaje de Resultados Vacíos -->
                                                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                                                            No se encontraron resultados.
                                                        </p>
                                                    @endif
                                                    @error('medicamento_id') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                    @enderror
                                                    @error('cantidad_medicamento') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- tabla sencilla -->
                                                @if (!empty($medicamentos_selec))
                                                    <ul class="mt-2 p-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md shadow-sm">
                                                        @foreach ($medicamentos_selec as $index => $medicamento)
                                                            <li class="flex justify-between items-center py-2 border-b border-gray-300 dark:border-gray-600">
                                                                <span>{{ $medicamento['nombre'] }} - {{ $medicamento['unidad'] }} {{ $medicamento['medida'] }} ({{ $medicamento['cantidad'] }})</span>
                                                                <button type="button" wire:click="eliminarMedicamento({{ $index }})" class="text-red-500">
                                                                    Eliminar
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Campos adicionales para Emergencia -->
                                    @if ($tipo_despacho === 'emergencia')
                                        <div class="mb-4">
                                            <label for="observacion" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                Observación:
                                            </label>
                                            <textarea wire:model="observacion" id="observacion" rows="3"
                                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200"></textarea>
                                            @error('observacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div> --}}
                                        {{-- @elseif ($tipo_despacho === 'quirofano')
                                        <!-- Medicamentos Asociados -->
                                        <div class="mb-4">
                                            <label for="medicamento_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Seleccionar
                                                Medicamento:
                                            </label>

                                            <!-- Campo de Búsqueda para medicamentos existente -->
                                            <div class="mb-4">
                                                <input type="text" id="tipo_medicamento_busqueda" wire:model.live="medicamento_solicitado"
                                                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                    placeholder="Buscar tipo de presentación">

                                                <!-- Selector de Resultados -->
                                                @if (!empty($medicamentos_solicitados_bus))
                                                    <div class="flex space-x-2 mt-2">
                                                        <select id="medicamentos_selec_bus" wire:model.live="medicamento_solicitado_id"
                                                            class= "w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                            <option value="">Seleccione un tipo</option>
                                                            @foreach ($medicamentos_solicitados_bus as $medicamento)
                                                                <option value="{{ $medicamento->id }} ">
                                                                    {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="number" wire:model="cantidad_medicamento"
                                                        placeholder="Cantidad Solicitada"
                                                        class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        <input type="number" wire:model="cantidad_medicamento_solicitado"
                                                            placeholder="Cantidad despachada"
                                                            class="w-1/4 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        <button type="button" wire:click="agregarMedicamentoSolicitado"
                                                            class="w-1/4 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">Agregar</button>
                                                    </div>
                                                @else
                                                    <!-- Mensaje de Resultados Vacíos -->
                                                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                                                        No se encontraron resultados.
                                                    </p>
                                                @endif
                                                @error('medicamento_id') 
                                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                @enderror
                                                @error('cantidad_medicamento') 
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- tabla sencilla -->
                                            @if (!empty($medicamentos_solicitados_selec))
                                                <ul class="mt-2 p-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md shadow-sm">
                                                    @foreach ($medicamentos_solicitados_selec as $index => $medicamento)
                                                        <li class="flex justify-between items-center py-2 border-b border-gray-300 dark:border-gray-600">
                                                            <span>{{ $medicamento['nombre'] }} - {{ $medicamento['unidad'] }} {{ $medicamento['medida'] }} ({{ $medicamento['cantidad'] }}) || ({{ $medicamento['cantidad_despacho'] }})</span>
                                                            <button type="button" wire:click="eliminarMedicamentoSolicitado({{ $index }})" class="text-red-500">
                                                                Eliminar
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif --}}