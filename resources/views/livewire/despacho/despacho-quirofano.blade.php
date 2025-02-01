<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Despachos Quirofano Realizados
    </h2>

    <!-- Filtro -->
    <select wire:model.live="filter" class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
        <option value="todos">Todos</option>
        <option value="recientes">Recientes</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-blue-500 text-white dark:bg-blue-600">
            <tr>
                <th class="px-4 py-2 text-left">Nº</th>
                <th class="px-4 py-2 text-left">Fecha</th>
                <th class="px-4 py-2 text-left">Solicitudes</th>
                <th class="px-4 py-2 text-left">Cantidad Despachada</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $despacho)
                <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                    <td class="px-4 py-2">{{ $despacho->id }}</td>
                    <td class="px-4 py-2">{{ $despacho->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $despacho->medicamentos->count('cantidad_solicitada') }}</td>
                    <td class="px-4 py-2">{{ $despacho->medicamentos->count('cantidad') }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="ver({{ $despacho->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Ver</button>
                        <button wire:click="editar({{ $despacho->id }})" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Editar</button>
                        <button wire:click="eliminar({{ $despacho->id }})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Eliminar</button>
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
                @if($accion === 'ver')
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Detalles del Despacho</h3>
                    <ul class="space-y-2">
                        @foreach($DespachoSeleccionado->despachosMedicamentos as $despachoMedicamento)
                            <li>
                                <strong>Nombre:</strong> {{ $despachoMedicamento->medicamento->nombre }} <br>
                                <strong>Cantidad Despachada:</strong> {{ $despachoMedicamento->cantidad }}
                                <strong>Cantidad Solicitada:</strong> {{ $despachoMedicamento->solicitudes->cantidad}} <br>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex justify-end mt-4">
                        <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancelar</button>
                    </div>
                @elseif($accion === 'eliminar')
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        Confirmar Eliminación
                    </h3>
                    <p class="mb-4">
                        ¿Está seguro de que desea eliminar este despacho? <br>
                        Las cantidades despachadas serán devueltas al stock.
                    </p>
                    <div class="flex justify-end">
                        <button wire:click="eliminar({{ $DespachoSeleccionado->id }})" class="bg-red-500 text-white px-4 py-2 rounded">
                            Eliminar
                        </button>
                        <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">
                            Cancelar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
