<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Incidencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }
        .estado-pendiente { color: #d97706; }
        .estado-en-proceso { color: #2563eb; }
        .estado-resuelto { color: #059669; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Incidencias</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Infraestructura</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Técnico</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->id }}</td>
                <td>
                    {{ $incidencia->infraestructura->tipo }}<br>
                    <small>{{ $incidencia->infraestructura->ubicacion }}</small>
                </td>
                <td class="estado-{{ $incidencia->estado }}">
                    {{ ucfirst($incidencia->estado) }}
                </td>
                <td>{{ ucfirst($incidencia->prioridad) }}</td>
                <td>{{ $incidencia->tecnico ? $incidencia->tecnico->name : 'No asignado' }}</td>
                <td>{{ $incidencia->fecha->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Ciudad Limpia - Sistema de Gestión de Infraestructura Municipal</p>
    </div>
</body>
</html>
