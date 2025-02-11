<div class="p-6 m-4 rounded-lg shadow-lg bg-white/30 backdrop-blur-md dark:bg-gray-800/60">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Reportes Generados
    </h2>

    <!-- Filtro -->
    <select wire:model.live="filter" class="mb-4 w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
        <option value="todos">Todos</option>
        <option value="hospitalizado">Hospitalizado</option>
        <option value="emergencia">Emergencia</option>
        <option value="quirofano">Quirófano</option>
        <option value="via_oral">Vía Oral</option>
        <option value="general">General</option>
    </select>

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse bg-gray-50 rounded-lg shadow-sm overflow-hidden dark:bg-gray-700">
        <thead class="bg-cyan-900 text-white dark:bg-cyan-900">
            <tr>
                <th class="px-4 py-2 text-left">Fecha de Creación</th>
                <th class="px-4 py-2 text-left">Tipo de Reporte</th>
                <th class="px-4 py-2 text-left">Rango de Fechas</th>
                <th class="px-4 py-2 text-left">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportes as $reporte)
                <tr class="border-t last:border-b hover:bg-blue-100 transition dark:border-gray-600 dark:hover:bg-gray-600">
                    <td class="px-4 py-2">{{ $reporte->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 capitalize">{{ $reporte->tipo }}</td>
                    <td class="px-4 py-2">
                        {{ $reporte->fecha_inicio }} - 
                        {{ $reporte->fecha_fin }}
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ $reporte->url }}" target="_blank" 
                           class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Abrir PDF
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-300">
                        No hay reportes disponibles.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $reportes->links() }}
    </div>
</div>
