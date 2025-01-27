<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Despachos Realizados
    </h2>

    <!-- Filtro -->
    <select 
        wire:model.live="filter" 
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
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
                    <td class="px-4 py-2">
                        @if($despacho->paciente)
                            {{ $despacho->paciente->nombre ?? 'Nombre no disponible' }} {{ $despacho->paciente->apellido ?? 'Apellido no disponible' }}
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
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->medicamento->nombre }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Cantidad:</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->cantidad }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Fecha:</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
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
    <select 
        wire:model.live="filter" 
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
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
                    <td class="px-4 py-2">{{ $despacho->paciente->nombre }} {{ $despacho->paciente->apellido }}</td> <!-- Nombre -->
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
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->medicamento->nombre }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Cantidad:</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->cantidad }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Fecha:</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $DespachoSeleccionado->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
                </div>
            </div>
        </div>
    @endif
</div> --}}
