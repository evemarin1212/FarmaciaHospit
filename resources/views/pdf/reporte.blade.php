<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .content { margin: 20px; }
    </style>
</head>
<body>
    <div class="title">Reporte de {{ ucfirst($tipo) }}</div>
    <div class="content">
        <p><strong>Tipo de Reporte:</strong> {{ ucfirst($tipo) }}</p>
        <p><strong>Fecha de Inicio:</strong> {{ $fecha_inicio }}</p>
        <p><strong>Fecha de Fin:</strong> {{ $fecha_fin }}</p>
    </div>
</body>
</html>
