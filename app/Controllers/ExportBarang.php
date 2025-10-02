<?php

namespace App\Controllers;

use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportBarang extends BaseController
{
    protected $GI_ID = 1;

    public function exportMasukPDF()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        $barangMasukModel = new BarangMasukModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $barangMasuk = $barangMasukModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'barangMasuk' => $barangMasuk,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ];

        if (empty($barangMasuk)) {
            session()->setFlashdata('error', 'Tidak ada data barang masuk untuk diekspor.');
            return redirect()->to('/admin-gi/kelolaBarang?month=' . $bulan . '&year=' . $tahun);
        }

        $html = view('admin_gi/export_barang_masuk_pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="laporan_barang_masuk_' . $bulan . '_' . $tahun . '.pdf"');
        header('Cache-Control: max-age=0');

        $dompdf->stream('laporan_barang_masuk_' . $bulan . '_' . $tahun . '.pdf', ['Attachment' => true]);
        exit;
    }

    public function exportMasukExcel()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        $barangMasukModel = new BarangMasukModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $barangMasuk = $barangMasukModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'barangMasuk' => $barangMasuk,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ];

        if (empty($barangMasuk)) {
            session()->setFlashdata('error', 'Tidak ada data barang masuk untuk diekspor.');
            return redirect()->to('/admin-gi/kelolaBarang?month=' . $bulan . '&year=' . $tahun);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Barang Masuk');
        $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK - UPT Bandung');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A2', 'Periode: ' . date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->fromArray([
            'No', 'Tanggal', 'Waktu', 'Nama Instansi', 'Nama Petugas', 'No HP', 'Nama PIC Tujuan',
            'No Surat Pengantar', 'Keterangan Barang', 'Kesesuaian', 'Nama Satpam Pemeriksa'
        ], NULL, 'A4');
        $sheet->getStyle('A4:K4')->getFont()->setBold(true);
        $sheet->getStyle('A4:K4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        $sheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');

        $row = 5;
        foreach ($barangMasuk as $i => $d) {
            $sheet->fromArray([
                $i + 1,
                $d['tanggal'] ?? '-',
                $d['waktu'] ?? '-',
                $d['nama_instansi'] ?? 'Tidak ada data',
                $d['nama_petugas'] ?? 'Tidak ada data',
                $d['no_hp'] ?? 'Tidak ada data',
                $d['nama_pic_tujuan'] ?? 'Tidak ada data',
                $d['no_surat_pengantar'] ?? 'Tidak ada data',
                $d['keterangan_barang'] ?? 'Tidak ada data',
                $d['kesesuaian'] ?? 'Belum Dicek',
                $d['satpam_pemeriksa'] ?? 'Tidak ada data'
            ], NULL, "A{$row}");
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders
        $lastRow = count($barangMasuk) + 4;
        $sheet->getStyle("A4:K{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "laporan_barang_masuk_" . $bulan . "_" . $tahun . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportKeluarPDF()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        $barangKeluarModel = new BarangKeluarModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $barangKeluar = $barangKeluarModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'barangKeluar' => $barangKeluar,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ];

        if (empty($barangKeluar)) {
            session()->setFlashdata('error', 'Tidak ada data barang keluar untuk diekspor.');
            return redirect()->to('/admin-gi/kelolaBarang?month=' . $bulan . '&year=' . $tahun);
        }

        $html = view('admin_gi/export_barang_keluar_pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="laporan_barang_keluar_' . $bulan . '_' . $tahun . '.pdf"');
        header('Cache-Control: max-age=0');

        $dompdf->stream('laporan_barang_keluar_' . $bulan . '_' . $tahun . '.pdf', ['Attachment' => true]);
        exit;
    }

    public function exportKeluarExcel()
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        $barangKeluarModel = new BarangKeluarModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $barangKeluar = $barangKeluarModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'barangKeluar' => $barangKeluar,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ];

        if (empty($barangKeluar)) {
            session()->setFlashdata('error', 'Tidak ada data barang keluar untuk diekspor.');
            return redirect()->to('/admin-gi/kelolaBarang?month=' . $bulan . '&year=' . $tahun);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Barang Keluar');
        $sheet->setCellValue('A1', 'LAPORAN BARANG KELUAR - UPT Bandung');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A2', 'Periode: ' . date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->fromArray([
            'No', 'Tanggal', 'Waktu', 'Nama Instansi', 'Nama Petugas', 'No HP', 'Pemilik Barang',
            'Pejabat Penerbit Surat', 'No Surat', 'Keterangan Barang', 'Nama Satpam Pemeriksa'
        ], NULL, 'A4');
        $sheet->getStyle('A4:K4')->getFont()->setBold(true);
        $sheet->getStyle('A4:K4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        $sheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');

        $row = 5;
        foreach ($barangKeluar as $i => $d) {
            $sheet->fromArray([
                $i + 1,
                $d['tanggal'] ?? '-',
                $d['waktu'] ?? '-',
                $d['nama_instansi'] ?? 'Tidak ada data',
                $d['nama_petugas'] ?? 'Tidak ada data',
                $d['no_hp'] ?? 'Tidak ada data',
                $d['pemilik_barang'] ?? 'Tidak ada data',
                $d['pejabat_penerbit_surat'] ?? 'Tidak ada data',
                $d['no_surat'] ?? 'Tidak ada data',
                $d['keterangan_barang'] ?? 'Tidak ada data',
                $d['satpam_pemeriksa'] ?? 'Tidak ada data'
            ], NULL, "A{$row}");
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders
        $lastRow = count($barangKeluar) + 4;
        $sheet->getStyle("A4:K{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "laporan_barang_keluar_" . $bulan . "_" . $tahun . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}