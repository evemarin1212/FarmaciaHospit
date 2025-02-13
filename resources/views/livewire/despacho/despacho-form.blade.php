<!-- Formulario de despacho -->
<div class="w-full shadow-md p-6 dark:bg-gray-800">

    <div class="mb-6">
        <div class="py-12 flex justify-center items-center">
            <div class="w-full max-w-4xl rounded-lg p-6 relative">

                @if ($formView)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-3/4 shadow-lg overflow-y-auto max-h-[78vh]">
                            {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-1/2 shadow-lg max-h-[80vh] overflow-y-auto"> --}}
                            <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-gray-200">
                                Registrar Nuevo Despacho
                            </h1>
                            <!-- Contenedor con scroll limitado -->
                            <div class="max-h-[75vh] overflow-y-auto">
                                <form wire:submit.prevent="guardarDespacho">
                                    <!-- Tipo de despacho -->
                                    <div class="mb-4">
                                        <label for="tipo_despacho" class="block text-sm font-medium focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                            Tipo de Despacho:
                                        </label>
                                        <select wire:model.live="tipo_despacho" id="tipo_despacho"
                                            class="w-full mt-1 p-2 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                            <option value="">Seleccione</option>
                                            <option value="emergencia">Emergencia</option>
                                            <option value="hospitalizado">Hospitalizado</option>
                                            <option value="quirofano">Quirófano</option>
                                        </select>
                                        @error('tipo_despacho')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Columna Izquierda: Información del Paciente -->
                                    @if (in_array($tipo_despacho, ['emergencia', 'hospitalizado']))
                                        <div class="flex flex-row-2 max-h-[36vh] overflow-y-auto">
                                            <div class="flex flex-col w-full px-4">
                                                <div class="mb-4">
                                                    @if ($paciente_opcion != 'nuevo')
                                                        <label for="paciente_opcion" class="block text-sm font-medium text-gray-700 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">Paciente:</label>
                                                        <select wire:model.live="paciente_opcion" id="paciente_opcion"
                                                            class="w-full mt-1 p-2 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            <option value="search">Buscar existente</option>
                                                            <option value="nuevo">Registrar nuevo</option>
                                                        </select>
                                                        @error('paciente_opcion')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    @endif
                                                </div>
                            
                                                @if ($paciente_opcion === 'nuevo')
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                                            Datos del Paciente:
                                                        </label>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                            <input type="text" wire:model.live="paciente_nombre" placeholder="Nombre" required
                                                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:text-gray-200">
                                                                @error('paciente_nombre')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <input type="text" wire:model.live="paciente_apellido" placeholder="Apellido" required
                                                                class="w-full border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                @error('paciente_apellido')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <div class="flex flex-row space-x-2">
                                                                <select id="nacionalidad" wire:model.live="nacionalidad"
                                                                class="border-gray-300 p-2 w-1/3 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                                    <option value="V">V</option>
                                                                    <option value="E">E</option>
                                                                </select>
                                                                <input type="text" wire:model.live="paciente_dni" placeholder="Cédula" required
                                                                    class="w-full border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            </div>

                                                                @error('nacionalidad')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                                @error('paciente_dni')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                                                            <select wire:model.live="paciente_estatus"
                                                                class="w-full p-2 border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
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
                                                        <input type="text" wire:model.live="search" id="paciente_search" placeholder="Buscar por cédula..."
                                                            class="w-full border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                        @if ($pacientes->isNotEmpty())
                                                            <ul class="mt-2 p-2 border border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm max-h-48 overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
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
                                                        {{-- @if($selectedPacienteId)
                                                            <div
                                                                class="mt-2 p-2 border focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 border-green-500 rounded-md bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                Paciente seleccionado: {{ App\Models\Paciente::find($selectedPacienteId)->nombre }}
                                                            </div>
                                                        @endif --}}
                                                        @error('selectedPacienteId') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Columna Derecha: Despacho de Medicamentos -->
                                            <div class="flex flex-col w-full">
                                                <div class="mb-4">
                                                    <label for="medicamento_id" class="block text-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 font-medium text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                                        Seleccionar Medicamento:
                                                    </label>
                                                    <input type="text" id="tipo_medicamento_busqueda" wire:model.live="tipo_medicamento_busqueda"
                                                        class="w-full mt-2 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                        placeholder="Buscar por nombre medicamento">

                                                    @if (!empty($medicamentos_selec_bus) && count($medicamentos_selec_bus) > 0)
                                                        <div class="flex space-x-2 mt-2">
                                                            <select id="medicamentos_selec_bus" wire:model.live="medicamento_id"
                                                                class="w-full mt-2 p-2 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                                <option value="">Seleccione un tipo</option>
                                                                @foreach ($medicamentos_selec_bus as $medicamento)
                                                                    <option value="{{ $medicamento->id }}">
                                                                        {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="text" wire:model="cantidad_medicamento" placeholder="Cantidad" required
                                                                class="w-1/4 border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            <button type="button" wire:click="agregarMedicamento"
                                                                class="w-1/4 px-4 py-2 bg-emerald-500 text-white rounded-md shadow-sm">
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
                                                    @if (!empty($medicamentos_selec ))
                                                        <ul class="mt-2 p-2 max-h-24 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 overflow-y-auto border border-gray-300 rounded-md shadow-sm">
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
                                                    @error('medicamentos_selec') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                
                                                <!-- Observación en caso de Emergencia -->
                                                @if ($tipo_despacho === 'emergencia')
                                                    <div class="mb-4">
                                                        <label for="observacion" class="block text-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 font-medium text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                                            Observación:
                                                        </label>
                                                        <textarea wire:model="observacion" id="observacion" rows="3"
                                                            class="w-full p-2 mt-1 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 " placeholder="Observaciones referentes al despacho..." required >{{$observacion}}</textarea>
                                                            @error('observacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                            
                                    <!-- Quirofano: -->
                                    @if (in_array($tipo_despacho, ['quirofano']))
                                        <div class="flex flex-row-2">
                                            <div class="flex flex-col w-full px-4">
                                                <div class="mb-4">
                                                    <label for="medicamento_solicitado"
                                                        class="block text-sm font-medium focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 text-gray-700 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">Seleccionar Medicamento:
                                                    </label>
                                                    <input type="text" id="medicamento_solicitado" wire:model.live="medicamento_solicitado"
                                                        class="w-full mt-2 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" 
                                                        placeholder="Buscar medicamento">
                                                
                                                    @if (!empty($medicamentos_solicitados_bus) && count($medicamentos_solicitados_bus) > 0)
                                                        <div class="flex space-x-2 mt-2">
                                                            <select id="medicamentos_selec_bus" wire:model.live="medicamento_solicitado_id"
                                                                class="w-full p-2 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                                <option value="">Seleccione un tipo</option>
                                                                @foreach ($medicamentos_solicitados_bus as $medicamento)
                                                                    <option value="{{ $medicamento->id }}">
                                                                        {{ $medicamento->nombre }} | {{ $medicamento->unidad }} {{ $medicamento->medida }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="text" wire:model="cantidad_medicamento_solicitado" placeholder="Cantidad Solicitada" required
                                                                class="w-1/4 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            <input type="text" wire:model="cantidad_medicamento"
                                                                placeholder="Cantidad despachada" required
                                                                class="w-1/4 border-gray-300 rounded-md focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                                            <button type="button" wire:click="agregarMedicamentoSolicitado"
                                                                class="w-1/4 px-4 py-2 bg-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 text-white rounded-md shadow-sm">Agregar</button>
                                                        </div>
                                                    @else
                                                        <!-- Mensaje de Resultados Vacíos -->
                                                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                                                            No se encontraron resultados.
                                                        </p>
                                                    @endif
                                                    @error('medicamentos_selec_bus') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                    @error('cantidad_medicamento') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                    @error('cantidad_medicamento_solicitado') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                    <!-- tabla sencilla -->
                                                    @if (!empty($medicamentos_solicitados_selec))
                                                        <ul class="mt-2 p-2 max-h-24 overflow-y-auto border border-gray-300 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm">
                                                            @foreach ($medicamentos_solicitados_selec as $index => $medicamento)
                                                                <li class="flex justify-between items-center py-2 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 border-b border-gray-300 dark:border-gray-600">
                                                                    <span>{{ $medicamento['nombre'] }} - {{ $medicamento['unidad'] }} {{ $medicamento['medida'] }}
                                                                        ({{ $medicamento['cantidad'] }}) || ({{ $medicamento['cantidad_despacho'] }})</span>
                                                                    <button type="button" wire:click="eliminarMedicamentoSolicitado({{ $index }})" class="text-red-500">
                                                                        Eliminar
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    @error('medicamentos_solicitados_selec') 
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="flex flex-col w-full">
                                                <div class="mb-4">
                                                    <label for="observacion" class="block text-sm font-medium text-gray-700 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:text-gray-200 after:content-['*'] after:text-red-500 after:ml-1">
                                                        Observación:
                                                    </label>
                                                    <textarea wire:model="observacion" id="observacion" rows="3"
                                                        class="w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:text-gray-200" placeholder="Observaciones referentes al despacho..." required></textarea>
                                                        @error('observacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Botones -->
                                    <div class="flex justify-end mt-2 gap-4">
                                        <button type="button" wire:click="cancelar"
                                            class="px-4 py-2 bg-red-400 hover:bg-red-600 text-white focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md">Cancelar</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-400 hover:bg-green-600 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 text-white rounded-md">Guardar Despacho</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="absolute top-6 right-1">
                        <button type="button" wire:click="form"
                            class="bg-emerald-700 hover:bg-emerald-500 text-white px-6 py-3 rounded-full focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 shadow-md transition duration-300 text-lg font-simbol flex items-center"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-lg mr-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                        </svg>
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