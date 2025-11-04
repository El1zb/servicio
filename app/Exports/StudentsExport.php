<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $careerId;
    protected $periodId;
    protected $search;

    public function __construct($careerId = null, $periodId = null, $search = null)
    {
        $this->careerId = $careerId;
        $this->periodId = $periodId;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Student::with('documents.file', 'career', 'period');

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

        $students = $query->get();
        $allFiles = File::pluck('name')->toArray();
        $data = [];

        foreach ($students as $student) {
            $row = [
                $student->name.' '.$student->last_name_paterno.' '.$student->last_name_materno,
                $student->control_number,
                $student->career->name ?? '',
                $student->period->name ?? ''
            ];

            foreach ($allFiles as $fileName) {
                $doc = $student->documents->firstWhere('file.name', $fileName);
                if ($doc) {
                    $status = $doc->student_file_path ? 'Entregado' : 'No entregado';
                    if (!empty($doc->comments)) {
                        $status .= ': '.$doc->comments;
                    }
                } else {
                    $status = 'No aplicable';
                }
                $row[] = $status;
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $allFiles = File::pluck('name')->toArray();
        return array_merge(['Nombre', 'No Control', 'Carrera', 'Periodo'], $allFiles);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    for ($col = 5; $col <= Coordinate::columnIndexFromString($highestColumn); $col++) {
                        $cell = $sheet->getCellByColumnAndRow($col, $row);
                        $value = $cell->getValue();

                        if (str_contains($value, 'Entregado')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('C6EFCE'); // verde
                        } elseif (str_contains($value, 'No entregado')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FFC7CE'); // rojo
                        } elseif (str_contains($value, 'No aplicable')) {
                            $cell->getStyle()->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FFEB9C'); // amarillo
                        }
                    }
                }

                // Negrita en encabezados
                $sheet->getStyle('A1:'.$highestColumn.'1')->getFont()->setBold(true);
            }
        ];
    }
}
