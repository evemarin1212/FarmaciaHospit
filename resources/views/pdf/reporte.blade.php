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
            font-size: 14px;
            margin: 40px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 15px;
            position: absolute;
            z-index: 10;
        }

        .membrete {
            text-align: center;
            font-size: 14px;
            line-height: 0.7;
            margin-bottom: 10px;
            /* position: absolute; */
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .fecha {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .table th, .table td {
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <header class="header">
        {{-- <img src=" {{asset('svg/2sViAPwvYhpvEIpWwI8S6SJgc7S.svg')}} " alt="Logo de la Institución" class="logo"> --}}
        <div class="membrete">
            <p>REPÚBLICA BOLIVARIANA DE VENEZUELA</p>
            <p>MINISTERIO DEL PODER POPULAR PARA LA DEFENSA</p>
            <p>BICENTENARIO DE SERVICIOS PARA LA DEFENSA</p>
            <p>DIRECCIÓN GENERAL DE SALUD DE LA FANB</p>
            <p>HOSPITAL MILITAR CNEL. NELSON SAYAGO MORA</p>
            <p><strong>FARMACIA INTERHOSPITALARIA</strong></p>
        </div>
    </header>

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

