<div>
    <div class="p-6 m-4 rounded-lg shadow-lg bg-white/30 backdrop-blur-md dark:bg-gray-800/60">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Despachos Quirofano Realizados
        </h2>

        <!-- Filtro -->
        <select wire:model.live="filter" class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
            <option value="todos">Todos</option>
            <option value="recientes">Recientes</option>
        </select>

        <!-- Tabla -->
        <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
            <thead class="bg-cyan-900 text-white dark:bg-cyan-900">
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
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $despacho->fecha_pedido }}</td>
                        <td class="px-4 py-2">{{ $despacho->medicamentos->count('cantidad_solicitada') }}</td>
                        <td class="px-4 py-2">{{ $despacho->medicamentos->count('cantidad') }}</td>
                        <td class="px-4 py-2">
                            <!-- Botones en la tabla -->
                            <button wire:click="ver({{ $despacho->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                Ver
                            </button>
                            @if ($tipo === 'Admin')
                                <button wire:click="confirmarEliminacion('¿Estás seguro de eliminar este despacho?', {{ $despacho->id }})" 
                                    class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $despachos->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($modal)
        <div class="fixed top-0 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Contenedor del Modal -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full ">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    @if($accion === 'ver')
                        <!-- Encabezado con título y botón de cierre -->
                        <div class="border-b pb-2 mb-4">
                            <h3 id="modal-title" class="text-3xl text-center font-bold text-gray-900 dark:text-gray-100">
                                Detalles del Despacho Quirófano
                            </h3>
                        </div>
                        <!-- Fecha de despacho -->
                        <div class="border-b pb-2 mb-4">
                            <span class="text-2xl font-semibold text-gray-500 dark:text-gray-300">
                                <strong>Fecha del despacho:</strong>
                                {{ $DespachoSeleccionado->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <!-- Listado de despachos -->
                        <div class="max-h-48 overflow-y-auto space-y-4">
                            @foreach($DespachoSeleccionado->despachosMedicamentos as $despachoMedicamento)
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $loop->iteration }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <p class="font-semibold text-blak-700 dark:text-gray-200">
                                            <strong>Nombre:</strong> 
                                            {{ $despachoMedicamento->medicamento->nombre }} - 
                                            {{ $despachoMedicamento->medicamento->unidad }} 
                                            {{ $despachoMedicamento->medicamento->medida }} - 
                                            {{ $despachoMedicamento->medicamento->presentacion->via_administracion }}
                                        </p>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="font-semibold text-blak-700 text-gray-700 dark:text-gray-200">
                                                <strong>Cantidad Despachada:</strong>
                                            </p>
                                            <p>
                                                {{ $despachoMedicamento->cantidad }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class=" font-semibold text-blak-700 text-gray-700 dark:text-gray-200">
                                                <strong>Cantidad Solicitada:</strong>
                                            </p>
                                            <p>
                                                {{ $despachoMedicamento->solicitudes->cantidad }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Botón de cierre al final (alternativa al botón de la cabecera) -->
                        <div class="mt-4 flex justify-end">
                            <button wire:click="cerrar" class="bg-blue-500 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md transition-colors">
                                Cerrar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
