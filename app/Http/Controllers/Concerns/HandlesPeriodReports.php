<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait HandlesPeriodReports
{
    protected function resolvePeriod(Request $request): array
    {
        $period = $request->query('period');
        if (! in_array($period, ['weekly', 'monthly', 'yearly'], true)) {
            $period = 'monthly';
        }

        $now = Carbon::now();

        if ($period === 'weekly') {
            $start = $now->copy()->startOfWeek();
            $end   = $now->copy()->endOfWeek();
            $name  = 'Mingguan';
            $label = $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');
        } elseif ($period === 'yearly') {
            $start = $now->copy()->startOfYear();
            $end   = $now->copy()->endOfYear();
            $name  = 'Tahunan';
            $label = $now->format('Y');
        } else {
            $start = $now->copy()->startOfMonth();
            $end   = $now->copy()->endOfMonth();
            $name  = 'Bulanan';
            $label = $now->translatedFormat('F Y');
        }

        return compact('period', 'start', 'end', 'name', 'label');
    }

    protected function periodOutput(Request $request, $query, string $dateColumn, string $title, array $columns, string $filename)
    {
        $isReport = $request->boolean('report');
        $isExport = $request->query('export') === 'xlsx';

        if (! $isReport && ! $isExport) {
            return null;
        }

        $p = $this->resolvePeriod($request);

        $records = (clone $query)
            ->whereBetween($dateColumn, [$p['start'], $p['end']])
            ->reorder($dateColumn)
            ->get();

        $headers = array_keys($columns);
        $fns     = array_values($columns);
        $rows    = $records->map(function ($rec) use ($fns) {
            return array_map(function ($fn) use ($rec) {
                $v = $fn($rec);
                return $v === null ? '' : (is_scalar($v) ? $v : (string) $v);
            }, $fns);
        })->all();

        if ($isReport) {
            return view('reports.period-report', [
                'title'   => $title,
                'period'  => $p,
                'headers' => $headers,
                'rows'    => $rows,
            ]);
        }

        $file = $filename . '-' . $p['period'] . '-' . now()->format('Ymd');

        return $this->streamSpreadsheet($file, $title . ' (' . $p['name'] . ': ' . $p['label'] . ')', $headers, $rows);
    }

    protected function streamSpreadsheet(string $file, string $sheetTitle, array $headers, array $rows)
    {
        if (class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            $book  = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $book->getActiveSheet();
            $sheet->setTitle('Laporan');

            $sheet->fromArray($headers, null, 'A1');
            $r = 2;
            foreach ($rows as $row) {
                $sheet->fromArray($row, null, 'A' . $r++);
            }

            $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(max(1, count($headers)));
            $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
            for ($i = 1; $i <= count($headers); $i++) {
                $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
            }
            $book->getProperties()->setTitle($sheetTitle);

            return response()->streamDownload(function () use ($book) {
                (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book))->save('php://output');
            }, $file . '.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        }

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $file . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
