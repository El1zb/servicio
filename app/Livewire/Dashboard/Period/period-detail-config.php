<?php

use App\Models\Period;

$period = Period::with(['files', 'semesters'])->findOrFail(request()->route('id'));

$tabs = [
    'periods.students'  => ['label' => 'Gestión de Estudiantes', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    'periods.documents' => ['label' => 'Documentos Base',        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    'periods.revision'  => ['label' => 'Revisión de Documentos', 'icon' => 'M12 8v4m0 4h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h7l7 7v9a2 2 0 01-2 2z'],
];