<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Medicamentos
    </h2>

    <!-- Campo de búsqueda -->
    <input
        type="text"
        wire:model.live="search"
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
        placeholder="Buscar medicamentos..."
    >
    <select
    wire:model.live="filter"
    class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
    >
        <option value="todos">Todos</option>
        <option value="por_agotar">Por Agotar (30 cantidades)</option>
        <option value="agotados">Agotados</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-blue-500 text-white dark:bg-blue-600">
            <tr>
                <th class="px-4 py-2 text-left">Nº</th>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Presentación</th>
                <th class="px-4 py-2 text-left">Unidad</th>
                <th class="px-4 py-2 text-left">Medida</th>
                <th class="px-4 py-2 text-left">Cantidad Disponible</th>
                <th class="px-4 py-2 text-left">Estado</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicamentos as $medicamento)
            <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $medicamento->nombre }}</td>
                <td class="px-4 py-2">{{ $medicamento->presentacion->tipo }}</td>
                <td class="px-4 py-2">{{ $medicamento->unidad }}</td>
                <td class="px-4 py-2">{{ $medicamento->medida }}</td>
                <td class="px-4 py-2">{{ $medicamento->cantidad_disponible }}</td>
                <td class="px-4 py-2">
                    @if($medicamento->cantidad_disponible == 0)
                        <h3 class="font-semibold"> Agotado </h3>
                    @elseif($medicamento->cantidad_disponible <= 30)
                        <h3 class="text-red-500  font-semibold"> Por Agotar </h3>
                    @else
                        Disponible
                    @endif
                </td>
                <td class="px-4 py-2">
                    <button wire:click="ver({{ $medicamento->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Ver</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $medicamentos->links() }}
    </div>

    <!-- Modal para Ver-->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-1/3 shadow-lg overflow-y-auto max-h-screen">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                {{ 'Detalles del Medicamento' }}
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Nombre:</label>
                    <input
                    type="text"
                    wire:model="medicamentoSeleccionado.nombre"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-200"
                    {{ 'readonly' }}>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Presentación:</label>
                    <input type="text" wire:model="medicamentoSeleccionado.presentacion" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-200" {{ 'readonly' }}>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Unidad:</label>
                    <input type="number" wire:model="medicamentoSeleccionado.unidad" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-200" {{  'readonly' }}>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Medida:</label>
                    <input type="text" wire:model="medicamentoSeleccionado.medida" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-200" {{ 'readonly' }}>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Cantidad Disponible:</label>
                    <input type="number" wire:model="medicamentoSeleccionado.cantidad_disponible" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-gray-200" {{ 'readonly' }}>
                </div>
            </div>

        <!-- Botones -->
        <div class="flex justify-end">
            <button wire:click="cerrar" class="bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
        </div>
    </div>

    @endif

    <div wire:ignore>
        <div id="confirmModal" class="modal">
        </div>
    </div>

    {{-- Mensaje de confirmacion --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <script>
        window.addEventListener('confirmDelete', event => {
            if (confirm('¿Estás seguro de que deseas eliminar este medicamento?')) {
                Livewire.dispatch('confirmDelete', event.detail);
            } else {
                // AQUÍ ESTÁ EL CAMBIO IMPORTANTE: NO HACER NADA SI SE CANCELA
                console.log("Eliminación cancelada por el usuario"); // Opcional: Para depuración
            }
        });

        Livewire.on('medicamentoEliminado', () => {
        });
    </script>

</div>