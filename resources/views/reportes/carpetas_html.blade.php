<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Carpetas Activas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            color: rgb(0, 0, 128); /* azul institucional */
            margin-bottom: 20px;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        thead {
            background-color: #e6e6e6; /* gris claro */
            color: #000000; /* texto negro */
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 12px;
            text-align: left;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #999999;
        }
    </style>
</head>
<body>

<h2>Reporte de Carpetas Activas</h2>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Solicitante</th>
        <th>Materia</th>
        <th>Tipo de Juicio</th>
        <th>Síntesis</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($carpetas as $c)
        <tr>
            <td>{{ $c->idcarpeta }}</td>
            <td>
                {{ optional($c->solicitante)->nombre }}
                {{ optional($c->solicitante)->apellidopaterno }}
                {{ optional($c->solicitante)->apellidomaterno }}
            </td>
            <td>{{ optional($c->materia)->tipomateria }}</td>
            <td>{{ optional($c->Juicio)->tipo }}</td>
            <td>{{ $c->sintesis }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="no-data">No hay carpetas activas para mostrar.</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
