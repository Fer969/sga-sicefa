<?php

namespace Modules\SGA\Resources\views\exports;

use Modules\SGA\Entities\AttendanceRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceRegistrationExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $type;
    protected $date;
    protected $generatedAt;

    public function __construct($type, $date)
    {
        $this->type = $type;
        $this->date = $date;
        $this->generatedAt = now();
    }

    public function collection()
    {
        $query = AttendanceRegistration::with([
            'apprentice.person',
            'apprentice.course.program',
            'asistance'
        ]);

        switch ($this->type) {
            case 'day':
                $query->whereHas('asistance', function ($q) { $q->whereDate('date', $this->date); });
                break;
            case 'week':
                $query->whereHas('asistance', function ($q) {
                    $q->whereBetween('date', [$this->date['start'], $this->date['end']]);
                });
                break;
            case 'month':
                $query->whereHas('asistance', function ($q) {
                    $q->whereYear('date', $this->date['year'])
                      ->whereMonth('date', $this->date['month']);
                });
                break;
        }

        return $query->orderByDesc('created_at')
                    ->get();
    }

    public function map($attendance): array
    {
        $person = $attendance->apprentice->person;
        $course = $attendance->apprentice->course;
        $program = $course->program ?? null;

        return [
            'document_number' => $person->document_number ?? '',
            'full_name' => trim(($person->first_name ?? '') . ' ' . 
                              ($person->first_last_name ?? '') . ' ' . 
                              ($person->second_last_name ?? '')),
            'course_code' => $course->code ?? '',
            'program_name' => $program->name ?? '',
            'attendance_date' => $attendance->asistance && $attendance->asistance->date ? Carbon::parse($attendance->asistance->date)->format('d/m/Y') : '',
            'attendance_time' => $attendance->created_at ? Carbon::parse($attendance->created_at)->format('H:i') : '',
            'status' => $this->getStatusLabel($attendance->asistencia),
            'porcentaje' => '-',
            'notes' => '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Número de Documento',
            'Nombre Completo',
            'Número de Ficha',
            'Programa',
            'Fecha',
            'Hora',
            'Estado',
            'Porcentaje',
            'Observaciones',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'si' => 'Asistió',
            'no' => 'No asistió',
        ];

        return $labels[$status] ?? $status;
    }

    public function getGeneratedAt()
    {
        return $this->generatedAt;
    }

    public function getReportTitle()
    {
        switch ($this->type) {
            case 'day':
                return 'Reporte de Asistencias del Día - ' . Carbon::parse($this->date)->format('d/m/Y');
            case 'week':
                return 'Reporte de Asistencias de la Semana - ' . 
                       Carbon::parse($this->date['start'])->format('d/m/Y') . ' a ' . 
                       Carbon::parse($this->date['end'])->format('d/m/Y');
            case 'month':
                return 'Reporte de Asistencias del Mes - ' . 
                       Carbon::createFromDate($this->date['year'], $this->date['month'], 1)->format('F Y');
            default:
                return 'Reporte de Asistencias';
        }
    }
} 