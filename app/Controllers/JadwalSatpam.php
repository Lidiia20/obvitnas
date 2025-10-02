<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\SatpamModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JadwalSatpam extends BaseController
{
    protected $GI_ID = 1;

    public function index()
    {
        $jadwalModel = new JadwalModel();
        $satpamModel = new SatpamModel();

        $jadwal = $jadwalModel->findAll();
        $satpamList = $satpamModel->where('gi_id', $this->GI_ID)
                                 ->where('nama IS NOT NULL')
                                 ->where('nama !=', '')
                                 ->findAll();

        return view('admin_gi/jadwal', [
            'jadwal' => $jadwal,
            'satpamList' => $satpamList
        ]);
    }

    public function simpan()
    {
        $jadwalModel = new JadwalModel();

        $data = [
            'nama_satpam' => $this->request->getPost('nama_satpam'),
            'tanggal' => $this->request->getPost('tanggal'),
            'shift' => $this->request->getPost('shift')
        ];

        try {
            $result = $jadwalModel->save($data);
            
            if ($result) {
                return redirect()->to('/admin-gi/jadwal')->with('success', 'Jadwal berhasil disimpan.');
            } else {
                return redirect()->to('/admin-gi/jadwal')->with('error', 'Gagal menyimpan jadwal.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin-gi/jadwal')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function hapus($id)
    {
        $jadwalModel = new JadwalModel();
        $jadwalModel->delete($id);

        return redirect()->to('/admin-gi/jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function exportPDF()
    {
        // Pastikan tidak ada output sebelumnya
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $jadwalModel = new JadwalModel();
        $satpamModel = new SatpamModel();

        $jadwal = $jadwalModel->findAll();
        $satpamList = $satpamModel->where('gi_id', $this->GI_ID)
                                 ->where('nama IS NOT NULL')
                                 ->where('nama !=', '')
                                 ->findAll();

        $data = [
            'jadwal' => $jadwal,
            'satpamList' => $satpamList
        ];

        $html = view('admin_gi/export_jadwal_pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Set headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="jadwal_satpam.pdf"');
        header('Cache-Control: max-age=0');
        
        $dompdf->stream('jadwal_satpam.pdf', ['Attachment' => true]);
        exit;
    }

    public function exportExcel()
    {
        // Pastikan tidak ada output sebelumnya
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $jadwalModel = new JadwalModel();
        $satpamModel = new SatpamModel();

        $jadwal = $jadwalModel->findAll();
        $satpamList = $satpamModel->where('gi_id', $this->GI_ID)
                                 ->where('nama IS NOT NULL')
                                 ->where('nama !=', '')
                                 ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set judul
        $sheet->setCellValue('A1', 'JADWAL SATPAM GITET NEW UJUNG BERUNG');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Set periode
        $sheet->setCellValue('A2', 'Periode: ' . date('F Y'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        
        // Set header tabel
        $sheet->fromArray(['No', 'Nama Satpam', 'Tanggal', 'Shift'], NULL, 'A4');
        
        // Styling header
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);
        $sheet->getStyle('A4:D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal('center');

        $row = 5;
        foreach ($jadwal as $i => $j) {
            // Format shift
            $shiftLabel = '';
            switch ($j['shift']) {
                case 'P':
                    $shiftLabel = 'Pagi (08:00 - 16:00)';
                    break;
                case 'S':
                    $shiftLabel = 'Siang (16:00 - 00:00)';
                    break;
                case 'M':
                    $shiftLabel = 'Malam (00:00 - 08:00)';
                    break;
                case 'L':
                    $shiftLabel = 'Libur';
                    break;
                default:
                    $shiftLabel = $j['shift'];
            }
            
            // Format tanggal
            $tanggal = date('d/m/Y', strtotime($j['tanggal']));
            
            $sheet->fromArray([
                $i + 1,
                $j['nama_satpam'],
                $tanggal,
                $shiftLabel
            ], NULL, "A{$row}");
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders
        $lastRow = $row - 1;
        $sheet->getStyle("A4:D{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = "Jadwal_Satpam_" . date('Y-m-d') . ".xlsx";
        
        // Set headers sebelum output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
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