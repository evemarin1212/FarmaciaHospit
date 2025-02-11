<div class="p-6 m-4 rounded-lg shadow-lg bg-white/30 backdrop-blur-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Despachos Realizados
    </h2>

    <!-- Filtro -->
    <select wire:model.live="filter"
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
        <option value="todos">Todos</option>
        <option value="recientes">Recientes</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-cyan-900 text-white dark:bg-cyan-900">
            <tr>
                <th class="px-4 py-2 text-left">Nº</th>
                <th class="px-4 py-2 text-left">Fecha</th>
                <th class="px-4 py-2 text-left">Tipo de despacho</th>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Cedula</th>
                <th class="px-4 py-2 text-left">Estatus</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $despacho)
            <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $despacho->fecha_pedido }}</td>
                <td class="px-4 py-2">{{ $despacho->tipo }}</td>
                <td class="px-4 py-2">
                    @if($despacho->paciente)
                    {{ $despacho->paciente->nombre ?? 'Nombre no disponible' }} {{ $despacho->paciente->apellido ??
                    'Apellido no disponible' }}
                    @else
                    No disponible
                    @endif
                </td> <!-- Nombre -->
                <td class="px-4 py-2">
                    @if($despacho->paciente)
                    {{ $despacho->paciente->dni ?? 'DNI no disponible' }}
                    @else
                    No disponible
                    @endif
                </td> <!-- Cedula -->
                <td class="px-4 py-2">
                    @if($despacho->paciente)
                    {{ $despacho->paciente->estatus ?? 'Estatus no disponible' }}
                    @else
                    No disponible
                    @endif
                </td> <!-- Estatus -->
                <td class="px-4 py-2">
                    <!-- Botones en la tabla -->
                    <button wire:click="ver({{ $despacho->id }})"
                        class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                        Ver
                    </button>
                    {{-- nueva eliminacion --}}
                    <button wire:click="confirmarEliminacion('¿Estás seguro de eliminar este despacho?', {{ $despacho->id }})" 
                    {{-- <button wire:click="eliminar({{ $despacho->id }})" --}}
                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                        Eliminar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $despachos->links() }}
    </div>

    <!-- Modal -->
    @if($modal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Contenedor del Modal -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl transform transition-all w-3/4 max-h-3/4">
                <!-- Encabezado del Modal -->
                <div class="border-b pb-3 mb-4">
                    <h3 id="modal-title" class="text-xl text-center font-bold text-gray-900 dark:text-gray-100">
                        Detalles del Despacho
                    </h3>
                </div>

                <!-- Contenedor de las dos columnas -->
                <div class="grid grid-cols-2 gap-4 max-h-48 overflow-y-auto">
                    <!-- Primera Columna: Código y Fecha -->
                    <div class="space-y-4">
                        <!-- Código del despacho -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md shadow-sm">
                            <!-- Fecha del despacho -->
                            <div class="mt-1 flex gap-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Fecha:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $DespachoSeleccionado->created_at->format('d/m/Y') }}</span>
                            </div>
                            <!-- Pacientes -->
                            <div class="mt-1 flex gap-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Paciente:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $DespachoSeleccionado->paciente->nombre }} {{ $DespachoSeleccionado->paciente->apellido }}</span>
                            </div>
                            <!-- Cédula -->
                            <div class="mt-1 flex gap-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Cédula:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $DespachoSeleccionado->paciente->dni}}</span>
                            </div>
                            <!-- Estatus -->
                            <div class="mt-1 flex gap-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Estatus:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $DespachoSeleccionado->paciente->estatus}}</span>
                            </div>
                            @if ($DespachoSeleccionado->tipo === 'emergencia' )
                                <!-- Estatus -->
                                <div class="mt-1 flex gap-2">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Observación:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $DespachoSeleccionado->observacion}}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Segunda Columna: Medicamentos y Cantidad -->
                    <div class="space-y-4">
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md shadow-sm max-h-48 overflow-y-auto">
                            @foreach($DespachoSeleccionado->medicamentos as $medicamento)
                                <div class="mb-4">
                                    <div class="flex gap-4">
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Medicamento:</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $medicamento->nombre }} - 
                                            {{ $medicamento->unidad }} 
                                            {{ $medicamento->medida }} - 
                                            {{ $medicamento->presentacion->via_administracion }}</span>
                                    </div>
                                    <div class="mt-1 flex gap-4">
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Cantidad:</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $medicamento->pivot->cantidad }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pie del Modal con Botón de Cierre -->
                <div class="mt-4 flex justify-end">
                    <button wire:click="cerrar" class="bg-blue-500 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


{{-- <div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Despachos Realizados
    </h2>

    <!-- Filtro -->
    <select wire:model.live="filter"
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
        <option value="todos">Todos</option>
        <option value="recientes">Recientes</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-blue-500 text-white dark:bg-blue-600">
            <tr>
                <th class="px-4 py-2 text-left">Nº</th>
                <th class="px-4 py-2 text-left">Fecha</th>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Cedula</th>
                <th class="px-4 py-2 text-left">Estatus</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $despacho)
            <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                <td class="px-4 py-2">{{ $despacho->id }}</td>
                <td class="px-4 py-2">{{ $despacho->fecha_pedido }}</td>
                <td class="px-4 py-2">{{ $despacho->paciente->nombre }} {{ $despacho->paciente->apellido }}</td>
                <!-- Nombre -->
                <td class="px-4 py-2">{{ $despacho->paciente->dni }}</td> <!-- Cedula -->
                <td class="px-4 py-2">{{ $despacho->paciente->estatus }}</td> <!-- Medicamento -->
                <td class="px-4 py-2">
                    <button wire:click="ver({{ $despacho->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                        Ver
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $despachos->links() }}
    </div>

    <!-- Modal -->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-1/2 shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                Detalles del Despacho
            </h3>

            <div class="space-y-4">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Código:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->codigo }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Medicamento:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->medicamento->nombre
                        }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Cantidad:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->cantidad }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Fecha:</span>
                    <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->created_at->format('d/m/Y')
                        }}</span>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
            </div>
        </div>
    </div>
    @endif
</div> --}}