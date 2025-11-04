<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Seguimiento Servicio Social</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            margin: 40px;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #fdfdfd;
        }

        h1, h2 {
            text-align: center;
            margin: 0;
        }

        .encabezado {
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .nombre-linea {
            font-weight: bold;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .nombre-linea span {
            border-bottom: 1px solid #34495e;
            flex-grow: 1;
            margin-left: 10px;
            padding: 3px 0;
        }

        .mensaje {
            margin-top: 20px;
            text-align: justify;
            font-size: 13px;
        }

        .requisitos {
            margin-top: 10px;
            padding-left: 20px;
        }

        .requisitos li {
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 13px;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #2980b9; /* color sólido elegante */
            color: white;
            font-weight: 600;
            text-align: center;
        }

        td {
            vertical-align: middle;
        }

        tr.entregado {
            background-color: #dff0d8;
        }

        tr.pendiente {
            background-color: #fcf8e3;
        }

        tr.rechazado {
            background-color: #f2dede;
        }

        tr.no-entregado {
            background-color: #f5f5f5;
            color: #7f8c8d;
        }

        tr:nth-child(even):not(.entregado):not(.pendiente):not(.rechazado):not(.no-entregado) {
            background-color: #fafafa;
        }

        p.nota {
            margin-top: 30px;
            font-size: 11px;
            color: #7f8c8d;
        }

        /* Bordes redondeados solo en la tabla y encabezados */
        table {
            border-radius: 6px;
            overflow: hidden;
        }

        table thead th:first-child {
            border-top-left-radius: 6px;
        }

        table thead th:last-child {
            border-top-right-radius: 6px;
        }

        table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }

        table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }

    </style>
</head>
<body>

    <div class="encabezado">
        <p class="nombre-linea" style="text-transform: uppercase;">
            NOMBRE DEL/LA ESTUDIANTE: 
            <span>
                {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
            </span>
        </p>

        <p><strong>NUEVO SEGUIMIENTO SERVICIO SOCIAL {{ strtoupper($student->period->name) }}</strong></p>
    </div>

    <div class="mensaje">
        <p>
            Espero te encuentres bien, soy la <strong>M.E. Rocío Alfonsín Ferat, Encargada del Departamento de Vinculación,</strong> 
            quien dará seguimiento a tu proceso de servicio social en el ITSCO. Me dirijo a ti con la finalidad de darle 
            seguimiento a tus documentos del servicio social, del periodo {{ strtolower($student->period->name) }}.
        </p>

        <p><strong>Requisitos:</strong></p>
        <ul class="requisitos">
            <li>Tener aprobado el 70% de los créditos totales de tu carrera.</li>
            <li>Tener cargada la materia de Servicio Social.</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Documento</th>
                <th>Estatus</th>
                <th>Comentarios</th>
                <th>Debes presentarlos</th>
            </tr>
        </thead>
        <tbody>
            @php $ultimaFecha = null; @endphp
            @foreach($documents as $i => $doc)
                @php
                    $entregado = $doc->student_file_path ? true : false;

                    $statusClass = '';
                    $statusLabel = '';

                    if (!$entregado) {
                        $statusClass = 'no-entregado';
                        $statusLabel = 'No entregado';
                    } else {
                        switch ($doc->status) {
                            case 'entregado':
                                $statusClass = 'entregado';
                                $statusLabel = 'Entregado';
                                break;
                            case 'en_revision':
                                $statusClass = 'pendiente';
                                $statusLabel = 'En revisión';
                                break;
                            case 'rechazado':
                                $statusClass = 'rechazado';
                                $statusLabel = 'Rechazado';
                                break;
                            default:
                                $statusClass = '';
                                $statusLabel = ucfirst(str_replace('_',' ', $doc->status));
                        }
                    }

                    if ($doc->custom_limit_date) {
                        $fechaLimite = \Carbon\Carbon::parse($doc->custom_limit_date)->format('d/m/Y');
                    } elseif ($doc->file?->limit_date) {
                        $fechaLimite = \Carbon\Carbon::parse($doc->file->limit_date)->format('d/m/Y');
                    } else {
                        $fechaLimite = 'Sin fecha';
                    }

                    $fechaAMostrar = ($fechaLimite !== $ultimaFecha) ? $fechaLimite : '';
                    $ultimaFecha = $fechaLimite;
                @endphp
                <tr class="{{ $statusClass }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $doc->file->name ?? $doc->name }}</td>
                    <td>{{ $statusLabel }}</td>
                    <td>{{ $doc->comments ?? '—' }}</td>
                    <td>{{ $fechaAMostrar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Después de la tabla, antes de la nota final -->
    <div class="mensaje" style="margin-top: 20px;">
        <p><strong>Importante:</strong></p>
        <p>“No retrases la entrega de tu documentación, pues eso afecta en la calificación y seguimiento de tu servicio”</p>

        <p><strong>Nota:</strong></p>
        <ul class="requisitos">
            <li>Obligatorio traer su lapicero tinta azul para cualquier trámite y libreta para anotaciones.</li>
            <li>Para cualquier aclaración favor de enviar correo a: <strong>dv.serviciosocial@gmail.com</strong>, anotando tu nombre, número de control, carrera y periodo de elaboración, o acudir personalmente a la oficina.</li>
            <li>Los horarios de atención son:
                <ul>
                    <li><strong>ITS Cosamaloapan:</strong> martes, miércoles y jueves de 10:00 a 13:00 hrs. y de 15:30 a 16:30 hrs.</li>
                    <li><strong>ITS Cosamaloapan Sistema Mixto (sabatino):</strong> se informará con anticipación el día que se acudirá; fuera del Departamento de Vinculación habrá un contenedor para depositar documentación.</li>
                    <li><strong>Campus Cd. Alemán:</strong> entregar documentación con la Lic. Carmen Pacheco, Coordinadora del Campus.</li>
                    <li><strong>Extensión Otatitlán:</strong> entregar documentación con el Ing. Idelberto Díaz Rojas, Coordinador de la Extensión.</li>
                    <li><strong>Carlos A. Carrillo:</strong> pendiente.</li>
                </ul>
            </li>
            <li>Favor de mantenerse pendientes a:
                <ul>
                    <li>La página de Facebook oficial del ITSCO: Tecnm Campus Cosamaloapan Itsco.</li>
                    <li>Al correo electrónico que proporcionaste en tus documentos.</li>
                </ul>
            </li>
        </ul>

        <p>Atentamente,</p>
        <p><strong>Departamento de Vinculación</strong></p>
    </div>


    <p class="nota">
        <strong>Nota:</strong> Este documento fue generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y') }}.
    </p>

</body>
</html>
