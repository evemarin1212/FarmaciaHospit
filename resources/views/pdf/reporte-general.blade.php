<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General de Medicamentos</title>
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
        <h2>Reporte General de Medicamentos</h2>
        <p class="fecha">Rango de Fechas: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d-m-Y') }} a {{ \Carbon\Carbon::parse($fecha_fin)->format('d-m-Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Cantidad Total Despachada</th>
                <th>Cantidad Inicial</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicamentos as $medicamento)
                <tr>
                    <td>{{ $medicamento->nombre }}</td>
                    <td>{{ $medicamento->cantidad_despachada }}</td>
                    <td>{{ $medicamento->cantidad_inicial }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
