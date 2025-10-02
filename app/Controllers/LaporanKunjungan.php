<?php

namespace App\Controllers;

use App\Models\KunjunganModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class LaporanKunjungan extends BaseController
{
    protected $kunjunganModel;

    public function __construct()
    {
        $this->kunjunganModel = new KunjunganModel();
    }

    // Preview PDF
    public function previewPDF()
    {
        $start = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'kunjungan'  => $this->getKunjungan($start, $end),
            'start_date' => $start,
            'end_date'   => $end
        ];

        $html = view('admin_gi/kunjungan_pdf', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan_kunjungan.pdf', ['Attachment' => false]);
        exit;
    }

    // Export PDF
    public function exportPDF()
    {
        $start = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'kunjungan'  => $this->getKunjungan($start, $end),
            'start_date' => $start,
            'end_date'   => $end
        ];

        $html = view('admin_gi/kunjungan_pdf', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('admin_gi_kunjungan.pdf', ['Attachment' => true]);
        exit;
    }

    // Export CSV
    public function exportCSV()
    {
        $start = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $kunjungan = $this->getKunjungan($start, $end);

        $filename = "laporan_kunjungan_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fh = fopen('php://output', 'w');
        fputcsv($fh, ['No','Nama Tamu','No Kendaraan','Keperluan','Status','Tanggal']);

        foreach ($kunjungan as $i => $row) {
            fputcsv($fh, [
                $i+1,
                $row['nama_tamu'] ?? '-',
                $row['no_kendaraan'] ?? '-',
                $row['keperluan'] ?? '-',
                $row['status'] ?? '-',
                !empty($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-'
            ]);
        }

        fclose($fh);
        exit;
    }

    // Helper ambil data
    private function getKunjungan($start, $end)
    {
        return $this->kunjunganModel
                    ->select('kunjungan.*, users.nama as nama_tamu')
                    ->join('users','users.id = kunjungan.user_id','left')
                    ->where('created_at >=', $start . ' 00:00:00')
                    ->where('created_at <=', $end   . ' 23:59:59')
                    ->orderBy('created_at','DESC')
                    ->findAll();
    }

    public function exportExcel()
    {
        $start = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $kunjungan = $this->getKunjungan($start, $end);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'LAPORAN KUNJUNGAN GI');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A2', 'Periode: ' . $start . ' s/d ' . $end);
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // Header tabel
        $sheet->fromArray(['No','Nama Tamu','No Kendaraan','Keperluan','Status','Tanggal'], NULL, 'A4');
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setRGB('CCCCCC');

        // Data
        $row = 5;
        foreach ($kunjungan as $i => $k) {
            $sheet->fromArray([
                $i+1,
                $k['nama_tamu'] ?? '-',
                $k['no_kendaraan'] ?? '-',
                $k['keperluan'] ?? '-',
                ucfirst($k['status'] ?? '-'),
                !empty($k['created_at']) ? date('d/m/Y H:i', strtotime($k['created_at'])) : '-'
            ], NULL, "A{$row}");
            $row++;
        }

        foreach(range('A','F') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Laporan_Kunjungan_" . date('Y-m-d_H-i-s') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
