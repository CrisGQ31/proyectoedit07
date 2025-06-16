<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Carpetas PJEdomex</title>
    <style>
        html, body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0; padding: 0;
            background-color: #fff;
            height: 100%;
        }

        .header-logo {
            width: 100%;
            padding: 10px 30px 0 0;
            text-align: right;
            box-sizing: border-box;
        }

        .header-logo img {
            height: 90px;
            object-fit: contain;
        }

        .container {
            background-color: #fff;
            margin: 0 auto;
            padding: 20px 30px 100px;
            max-width: 95%;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            color: rgb(0, 0, 128);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        thead {
            background-color: #e6e6e6;
            color: #000;
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
            color: #999;
        }

        .footer-bar {
            position: fixed;
            bottom: 0; left: 0;
            width: 100%;
            height: 80px;
            background-color: rgb(159, 34, 65);
            z-index: -1;
        }
    </style>
</head>
<body>

<div class="header-logo">
    <img src="{{ public_path('img/report/bordespjem.png') }}" alt="Encabezado PJEM">
</div>

<div class="container">
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
</div>

<div class="footer-bar"></div>

</body>
</html>
