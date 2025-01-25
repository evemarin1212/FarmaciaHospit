<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Lotes
        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </h2>

    <!-- Filtro -->
    <select 
        wire:model.live="filter" 
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
    >
        <option value="todos">Todos</option>
        <option value="vencidos">Vencidos</option>
        <option value="por_vencer">Por Vencer (30 días)</option>
        <option value="agotados">Agotados</option>
        <option value="por_agotar">Por Agotar (30 cantidades)</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-blue-500 text-white dark:bg-blue-600">
            <tr>
                <th class="px-4 py-2 text-left">Código de lote</th>
                <th class="px-4 py-2 text-left">Medicamento</th>
                <th class="px-4 py-2 text-left">Cantidad</th>
                <th class="px-4 py-2 text-left">Fecha de Vencimiento</th>
                <th class="px-4 py-2 text-left">Estatus</th>
                <th class="px-4 py-2 text-left">Estado</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotes as $lote)
                <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                    <td class="px-4 py-2">{{ $lote->codigo_lote }}</td>
                    <td class="px-4 py-2">{{ $lote->medicamento->nombre }}</td>
                    <td class="px-4 py-2">{{ $lote->cantidad }}</td>
                    <td class="px-4 py-2">{{ $lote->fecha_vencimiento }}</td>
                    <td class="px-4 py-2">{{ $lote->estatus }}</td>
                    <td class="px-4 py-2">
                        @if($lote->cantidad == 0)
                            Agotado
                        @elseif($lote->cantidad <= 30)
                            Por Agotar
                        @elseif($lote->fecha_vencimiento < now())
                            Vencido
                        @elseif($lote->fecha_vencimiento <= now()->addDays(30))
                            Por Vencer
                        @else
                            Disponible
                        @endif
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="ver({{ $lote->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                Ver
                            </button>
                            <button wire:click="edit({{ $lote->id }})" class="bg-green-500 text-white px-2 py-1 rounded">
                                Editar
                            </button>
                            <button wire:click="eliminar({{ $lote->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
                                Eliminar
                            </button>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Paginación -->
    <div class="mt-4">
        {{ $lotes->links() }}
    </div>

    <!-- Modal para Ver/Editar Lote -->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-1/2 shadow-lg overflow-y-auto max-h-screen">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                {{ $editar ? 'Editar Lote de Medicamento' : 'Detalles del Lote de Medicamento' }}
            </h3>
            
            <div class="space-y-4">
                <!-- Código del Lote -->
                <div>
                    <label for="codigo_lote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Código del Lote
                    </label>
                    <input type="text" id="codigo_lote" wire:model="LoteSeleccionado.codigo_lote" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                        {{ $editar ? '' : 'readonly' }}>
                    @error('codigo_lote') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Medicamento -->
                <div>
                    <label for="medicamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Medicamento
                    </label>
                    <select id="medicamento_id" wire:model="LoteSeleccionado.medicamento_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                            {{ $editar ? '' : 'disabled' }}>
                        <option value="">Seleccionar Medicamento</option>
                        @foreach($medicamentos as $medicamento)
                            <option value="{{ $medicamento->id }}">{{ $medicamento->nombre }}</option>
                        @endforeach
                        <option value="nuevo">Nuevo Medicamento</option>
                    </select>
                    @error('medicamento_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Nuevo Medicamento -->
                @if($LoteSeleccionado['medicamento_id'] === 'nuevo')
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nombre del Medicamento
                        </label>
                        <input type="text" id="nombre" wire:model="medicamentoSeleccionado.nombre"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                            {{ $editar ? '' : 'readonly' }}>
                        @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="presentacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Presentación
                        </label>
                        <select id="presentacion" wire:model="medicamentoSeleccionado.presentacion"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                {{ $editar ? '' : 'disabled' }}>
                            <option value="">Seleccionar Presentación</option>
                            <option value="encapsulado">Encapsulado</option>
                            <option value="intravenoso">Intravenoso</option>
                            <option value="jarabe">Jarabe</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('presentacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
    
                    @if($medicamentoSeleccionado['presentacion'] === 'otro')
                        <div>
                            <label for="nueva_presentacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Especificar Presentación
                            </label>
                            <input type="text" id="nueva_presentacion" wire:model="medicamentoSeleccionado.nueva_presentacion"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                {{ $editar ? '' : 'readonly' }}>
                            @error('nueva_presentacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif
                @endif
    
                <!-- Cantidad -->
                <div>
                    <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Cantidad
                    </label>
                    <input type="number" id="cantidad" wire:model="LoteSeleccionado.cantidad"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                        {{ $editar ? '' : 'readonly' }}>
                    @error('cantidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Fecha de Vencimiento -->
                <div>
                    <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Fecha de Vencimiento
                    </label>
                    <input type="date" id="fecha_vencimiento" wire:model="LoteSeleccionado.fecha_vencimiento"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                        {{ $editar ? '' : 'readonly' }}>
                    @error('fecha_vencimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Origen -->
                <div>
                    <label for="origen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Origen
                    </label>
                    <input type="text" id="origen" wire:model="LoteSeleccionado.origen"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    {{ $editar ? '' : 'readonly' }}>
                    @error('origen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
               
                <!-- Fecha de Registro -->
                <div>
                    <label for="fecha_registro" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Fecha de Registro
                    </label>
                    <input type="date" id="fecha_registro" wire:model="LoteSeleccionado.fecha_registro"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                        {{ 'readonly' }}>
                    @error('fecha_registro') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end">
                @if($editar)
                    <button wire:click="guardar" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Guardar</button>
                @endif
                <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
            </div>
        </div>
    </div>

    @endif
</div>