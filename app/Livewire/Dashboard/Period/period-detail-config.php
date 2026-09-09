<?php

use App\Models\Period;

$period = Period::with(['files', 'semesters'])->findOrFail(request()->route('id'));

// Orden por utilidad real para el administrador: revisar documentos entregados
// es la tarea del día a día, crear documentos base es ocasional/setup, y
// aceptar estudiantes es sobre todo al inicio del periodo.
$tabs = [
    'periods.revision'  => ['label' => 'Revisión de Documentos', 'shortLabel' => 'Revisión'],
    'periods.documents' => ['label' => 'Documentos Base',        'shortLabel' => 'Documentos'],
    'periods.students'  => ['label' => 'Gestión de Estudiantes', 'shortLabel' => 'Gestión'],
];
