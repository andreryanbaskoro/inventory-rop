<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanExcelExporter
{
    /**
     * @param  array{judul: string, kolom: list<string>, baris: list<list<mixed>>}  $data
     */
    public function unduh(array $data, string $namaBerkas): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        $sheet->setCellValue('A1', $data['judul']);
        $sheet->mergeCells('A1:'.chr(64 + max(count($data['kolom']), 1)).'1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $barisMulai = 3;
        foreach ($data['kolom'] as $indeks => $label) {
            $kolom = chr(65 + $indeks);
            $sheet->setCellValue($kolom.$barisMulai, $label);
            $sheet->getStyle($kolom.$barisMulai)->getFont()->setBold(true);
            $sheet->getStyle($kolom.$barisMulai)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF1E3A5F');
            $sheet->getStyle($kolom.$barisMulai)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($kolom.$barisMulai)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $baris = $barisMulai + 1;
        foreach ($data['baris'] as $isiBaris) {
            foreach ($isiBaris as $indeks => $nilai) {
                $sheet->setCellValue(chr(65 + $indeks).$baris, $nilai);
            }
            $baris++;
        }

        foreach (range(0, count($data['kolom']) - 1) as $indeks) {
            $sheet->getColumnDimension(chr(65 + $indeks))->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $namaBerkas, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
