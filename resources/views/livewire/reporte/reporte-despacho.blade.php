<!-- Componente de tabla Medicamento -->
<div class="p-6 bg-white shadow-md dark:bg-gray-800">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Despachos Realizados
    </h2>

    <!-- Filtro -->
    <select wire:model.live=""
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
                    <button wire:click=""
                        class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                        Ver
                    </button>
                    {{-- nueva eliminacion --}}
                    {{-- <button wire:click="eliminar({{ $despacho->id }})"
                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                        Eliminar
                    </button> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">

    </div>
