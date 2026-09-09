<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StudentsExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{
    protected $careerId;
    protected $periodId;
    protected $search;
    protected $statusFilter;
    protected $individualFileIds;

    public function __construct($careerId = null, $periodId = null, $search = null, $statusFilter = null, $individualFileIds = null)
    {
        $this->careerId          = $careerId;
        $this->periodId          = $periodId;
        $this->search            = $search;
        $this->statusFilter      = $statusFilter;
        $this->individualFileIds = $individualFileIds ?? collect();
    }

    public function collection()
    {
        $query = Student::with(['documents.file', 'career', 'period'])
        ->where('status', 'aprobado');


        if ($this->careerId) $query->where('career_id', $this->careerId);
        if ($this->periodId) $query->where('period_id', $this->periodId);
        if ($this->search) {
            $query->where(function($q){
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name_paterno', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name_materno', 'like', '%'.$this->search.'%')
                  ->orWhere('control_number', 'like', '%'.$this->search.'%');
            });
        }

        // Mismo filtro de estatus que la vista de Revisión de Documentos
        // (ManagesRevision::applyRevisionStatusFilter) — mantener sincronizados.
        if ($this->statusFilter === 'pending') {
            $query->whereHas('documents', function ($q) {
                $q->whereNotNull('student_file_path')
                  ->where(fn ($q2) => $q2->where('status', 'en_revision')->orWhereNull('status'));
            });
        } elseif ($this->statusFilter === 'approved') {
            $query->whereHas('documents', fn ($q) => $q->where('status', 'revisado'));
        } elseif ($this->statusFilter === 'rejected') {
            $query->whereHas('documents', fn ($q) => $q->where('status', 'rechazado'));
        } elseif ($this->statusFilter === 'missing_individual') {
            if ($this->individualFileIds->isNotEmpty()) {
                $placeholders = implode(',', array_fill(0, $this->individualFileIds->count(), '?'));
                $query->whereRaw(
                    "(select count(*) from file_student_uploads
                        where file_student_uploads.student_id = students.id
                        and file_student_uploads.file_id in ($placeholders)) < ?",
                    [...$this->individualFileIds->all(), $this->individualFileIds->count()]
                );
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $students = $query->get();

        $filesQuery = File::query();
        if ($this->periodId) {
            $filesQuery->where('period_id', $this->periodId);
        }
        $allFiles = $filesQuery->get();

        $data = [];

        foreach ($students as $student) {
            $row = [
                $student->name.' '.$student->last_name_paterno.' '.$student->last_name_materno,
                $student->control_number,
                $student->career->name ?? 'Sin carrera',
                $student->period->name ?? 'Sin periodo'
            ];

            foreach ($allFiles as $file) {
                $doc = $student->documents->firstWhere('file_id', $file->id);

                if ($doc) {
                    $status = $this->getDocumentStatus($doc, $file);
                } else {
                    $status = 'No asignado';
                }

                $row[] = $status;
            }

            $data[] = $row;
        }

        return collect($data);
    }

    private function getDocumentStatus($doc, $file)
    {
        if (!$doc->is_active) {
            return 'Inactivo';
        }

        if ($doc->student_file_path) {
            $status = '';

            switch ($doc->status) {
                case 'revisado':
                    $status = '✓ Aprobado';
                    break;
                case 'rechazado':
                    $status = '✗ Rechazado';
                    break;
                case 'en_revision':
                default:
                    $status = '⏳ En revisión';
                    break;
            }

            if (!empty($doc->comments)) {
                $status .= ' | ' . $doc->comments;
            }

            return $status;
        } else {
            $fechaLimite = $doc->custom_limit_date ?? $file->limit_date;

            if ($fechaLimite) {
                $fecha = \Carbon\Carbon::parse($fechaLimite);
                $hoy = \Carbon\Carbon::now();

                if ($fecha->lt($hoy)) {
                    return '⚠ No entregado (Vencido: ' . $fecha->format('d/m/Y') . ')';
                } else {
                    return 'Pendiente (Límite: ' . $fecha->format('d/m/Y') . ')';
                }
            }

            return 'Pendiente';
        }
    }

    public function headings(): array
    {
        $filesQuery = File::query();
        if ($this->periodId) {
            $filesQuery->where('period_id', $this->periodId);
        }
        $allFiles = $filesQuery->pluck('name')->toArray();

        return array_merge(
            ['Nombre Completo', 'No. Control', 'Carrera', 'Periodo'],
            $allFiles
        );
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8E8E8']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    if ($col <= 4) {
                        $sheet->getColumnDimension($columnLetter)->setWidth(20);
                    } else {
                        $sheet->getColumnDimension($columnLetter)->setWidth(30);
                    }
                }

                for ($row = 2; $row <= $highestRow; $row++) {
                    for ($col = 5; $col <= $highestColumnIndex; $col++) {
                        $cell = $sheet->getCellByColumnAndRow($col, $row);
                        $value = $cell->getValue();

                        if (str_contains($value, '✓ Aprobado')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('C6EFCE');
                        } elseif (str_contains($value, '✗ Rechazado')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FFC7CE');
                        } elseif (str_contains($value, '⏳ En revisión')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('BDD7EE');
                        } elseif (str_contains($value, 'Vencido')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FFD966');
                        } elseif (str_contains($value, 'Pendiente')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FFF2CC');
                        } elseif (str_contains($value, 'No asignado')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('D9D9D9');
                        } elseif (str_contains($value, 'Inactivo')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('A6A6A6');
                        }

                        $cell->getStyle()->getAlignment()
                            ->setVertical(Alignment::VERTICAL_CENTER)
                            ->setWrapText(true);
                    }
                }

                $sheet->getStyle('A1:'.$highestColumn.$highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('CCCCCC'));

                $sheet->freezePane('A2');

                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }
            }
        ];
    }
}
