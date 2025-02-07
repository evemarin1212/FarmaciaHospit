<!-- resources/views/pdf/reporte.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de {{ $tipo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .titulo {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .fecha {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table, .table th, .table td {
            border: 1px solid black;
        }
        .table th, .table td {
            padding: 5px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <div class="titulo">
        <h2>Reporte de {{ ucfirst($tipo) }}</h2>
        <p class="fecha">Rango de Fechas: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d-m-Y') }} a {{ \Carbon\Carbon::parse($fecha_fin)->format('d-m-Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Fecha de Despacho</th>
                <th>Paciente</th>
                <th>Medicamento</th>
                <th>Cantidad Despachada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $despacho)
                @foreach($despacho->despachosMedicamentos as $despachomedicamento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($despacho->fecha_pedido)->format('d-m-Y') }}</td>
                        <td>{{ $despacho->paciente ? $despacho->paciente->nombre : 'N/A' }}</td>
                        <td>{{ $despachomedicamento->medicamento->nombre }}</td>
                        <td>{{ $despachomedicamento->cantidad }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>

