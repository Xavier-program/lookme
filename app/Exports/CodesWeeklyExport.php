<?php

namespace App\Exports;

use App\Models\Code;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CodesWeeklyExport implements FromCollection, WithEvents
{
    protected array $rows = [];

    public function collection()
    {
        $codes = Code::with('girl')
            ->orderBy('created_at', 'desc')
            ->get();

        $lastWeek = null;
        $weeklyUsedByGirl = [];

        foreach ($codes as $code) {

            $currentWeek = $code->created_at->format('o-W');
            $weekStart = $code->created_at->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd   = $code->created_at->copy()->endOfWeek(Carbon::SUNDAY);

            // 👉 CIERRE DE SEMANA
            if ($lastWeek !== null && $lastWeek !== $currentWeek) {
                $this->rows[] = ['RESUMEN SEMANAL'];
                foreach ($weeklyUsedByGirl as $girl => $count) {
                    $this->rows[] = [$girl, $count . ' códigos usados'];
                }
                $this->rows[] = [];
                $weeklyUsedByGirl = [];
            }

            // 👉 ENCABEZADO SEMANA
            if ($lastWeek !== $currentWeek) {
                $this->rows[] = ['SEMANA DEL ' . $weekStart->format('d/m/Y') . ' AL ' . $weekEnd->format('d/m/Y')];
                $this->rows[] = ['Código', 'Estado', 'Fecha creación', 'Fecha uso', 'Chica', 'Vencimiento'];
                $lastWeek = $currentWeek;
            }

            // CONTADOR
            if ($code->used_at && $code->girl) {
                $key = $code->girl->name . ' (ID ' . $code->girl->id . ')';
                $weeklyUsedByGirl[$key] = ($weeklyUsedByGirl[$key] ?? 0) + 1;
            }

            $estado = 'Disponible';
            if ($code->used_at) {
                $estado = now()->greaterThan($code->used_at->copy()->addHour())
                    ? 'Expirado'
                    : 'Usado';
            }

            $this->rows[] = [
                $code->code,
                $estado,
                $code->created_at->format('d/m/Y H:i'),
                $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-',
                $code->girl ? $code->girl->name : '-',
                $code->used_at ? $code->used_at->copy()->addHour()->format('d/m/Y H:i') : '-',
            ];
        }


        // 👉 RESUMEN DE LA ÚLTIMA SEMANA
if (!empty($weeklyUsedByGirl)) {
    $this->rows[] = ['RESUMEN SEMANAL'];
    foreach ($weeklyUsedByGirl as $girl => $count) {
        $this->rows[] = [$girl, $count . ' códigos usados'];
    }
}


        return collect($this->rows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();

                // Auto tamaño columnas
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellA = $sheet->getCell("A{$row}")->getValue();

                    // 👉 FILA SEMANA
                    if (str_starts_with($cellA, 'SEMANA DEL')) {
                        $sheet->mergeCells("A{$row}:F{$row}");
                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 14,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '000000'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }

                    // 👉 ENCABEZADOS TABLA
                    if ($cellA === 'Código') {
                        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'E5E7EB'],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                        ]);
                    }

                    // 👉 RESUMEN SEMANAL
                    if ($cellA === 'RESUMEN SEMANAL') {
                        $sheet->mergeCells("A{$row}:F{$row}");
                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'DBEAFE'],
                            ],
                        ]);
                    }
                }

                // Bordes generales
                $sheet->getStyle("A1:F{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
