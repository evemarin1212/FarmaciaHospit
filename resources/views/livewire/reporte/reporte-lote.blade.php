<!-- Componente de tabla Lote -->
<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Lotes
            @if (session('error'))
                <div class="bg-red-500 text-white p-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </h2>
        <button class="bg-erde-500 text-white px-2 py-1 rounded">

        </button>
    </div>

    <!-- Campo de búsqueda -->
    <input
        type="text"
        wire:model.live=""
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
        placeholder="Buscar lote..."
    >
    <!-- Filtro -->
    <select
        wire:model.live=""
        class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
    >
        <option value="todos">Todos</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-blue-500 text-white dark:bg-blue-600">
            <tr>
                <th class="px-4 py-2 text-left">Nº</th>
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
            {{-- @foreach($lotes as $lote) --}}
                <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                    {{-- <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $lote->codigo_lote }}</td>
                    <td class="px-4 py-2">{{ $lote->medicamento->nombre }}</td>
                    <td class="px-4 py-2">{{ $lote->cantidad }}</td>
                    <td class="px-4 py-2">{{ $lote->fecha_vencimiento }}</td>
                    <td class="px-4 py-2">{{ $lote->estatus }}</td>
                    <td class="px-4 py-2">
                        @if($lote->fecha_vencimiento < now())
                            <h3 class="font-semibold"> Vencido </h3>
                        @elseif($lote->fecha_vencimiento <= now()->addDays(30))
                            <h3 class="text-red-500  font-semibold"> Por Vencer </h3>
                        @else
                            Disponible
                        @endif --}}
                    </td>
                    <td class="px-4 py-2">
                        <button wire:click="" class="bg-yellow-500 text-white px-2 py-1 rounded">
                            Ver
                        </button>
                        <button wire:click="" onclick="" class="bg-red-500 text-white px-2 py-1 rounded">
                            Eliminar
                        </button>
                        
                    </td>
                </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">

    </div>