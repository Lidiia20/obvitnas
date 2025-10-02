<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\KunjunganModel; 
use App\Models\ZonaUptModel;   
use App\Models\ZonaUltgModel;
use App\Models\ZonaGiModel;
use App\Models\SatpamModel;
use App\Models\AdminGIModel;


class AdminK3L extends BaseController
{
    public function index()
    {
        $kunjunganModel = new KunjunganModel();

        $start = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $kunjungan = $kunjunganModel->where('created_at >=', $start)
                                    ->where('created_at <=', $end)
                                    ->findAll();

        $total = count($kunjungan);
        $approved = count(array_filter($kunjungan, fn($k) => $k['status'] === 'approved'));
        $checkout = count(array_filter($kunjungan, fn($k) => $k['status'] === 'checkout'));
        $pending = count(array_filter($kunjungan, fn($k) => $k['status'] === 'pending'));

        $weekly = [];
        foreach ($kunjungan as $k) {
            $week = date('W', strtotime($k['created_at']));
            $weekly[$week] = ($weekly[$week] ?? 0) + 1;
        }

        $data = [
            'total' => $total,
            'approved' => $approved,
            'checkout' => $checkout,
            'pending' => $pending,
            'weekly_data' => $weekly,
            'start_date' => $start,
            'end_date' => $end
        ];

        return view('admink3l/dashboard', $data);
    }

    public function kelolaUser()
{
    $usersModel = new \App\Models\UsersModel();
    $zonaGiModel = new \App\Models\ZonaGiModel();

    $users = $usersModel->where('role', 'tamu')->findAll();

    $giList = $zonaGiModel->findAll();
    $gi_nama = [];
    foreach ($giList as $gi) {
        $gi_nama[$gi['id']] = $gi['nama_gi'];
    }

    return view('admink3l/kelola_user', [
        'users' => $users,
        'gi_nama' => $gi_nama
    ]);
}



    public function kelolaSatpam()
{
    $satpam = (new \App\Models\SatpamModel())
    ->select('satpam.*, zona_gi.nama_gi as gi_nama')
    ->join('zona_gi', 'zona_gi.id = satpam.gi_id', 'left')
    ->findAll();


    return view('admink3l/kelola_satpam', ['satpam' => $satpam]);
}

public function kelolaAdminGI()
{
    $admin = (new \App\Models\AdminGIModel())
    ->select('admin_gi.*, zona_gi.nama_gi as gi_nama')
    ->join('zona_gi', 'zona_gi.id = admin_gi.gi_id', 'left')
    ->findAll();


    return view('admink3l/kelola_admingi', ['admin_gi' => $admin]);
}


    public function tambahUser()
{
    $giModel = new \App\Models\ZonaGiModel();
    $data['gi_list'] = $giModel->findAll(); // Kirim daftar GI

    return view('admink3l/tambah_user', $data);
}


    public function simpanUser()
{
    $role = $this->request->getPost('role');

    if ($role === 'satpam') {
        $model = new \App\Models\SatpamModel();
        $data = [
            'nama' => $this->request->getPost('nama'),
            // 'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'gi_id' => $this->request->getPost('gi_id')
        ];
    } elseif ($role === 'admin_gi') {
        $model = new \App\Models\AdminGIModel();
        $data = [
            'nama' => $this->request->getPost('nama'),
            // 'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'gi_id' => $this->request->getPost('gi_id')
        ];
    } else {
        // default: user tamu
        $model = new \App\Models\UsersModel();
        $data = [
            'nama' => $this->request->getPost('nama'),
            // 'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $role,
            'gi_id' => $this->request->getPost('gi_id')
        ];
    }

    $model->save($data);
    return redirect()->to('/admink3l/kelola-user')->with('success', 'User berhasil ditambahkan.');
}


    public function hapusUser($role, $id)
{
    if ($role === 'satpam') {
        $model = new \App\Models\SatpamModel();
    } elseif ($role === 'admin_gi') {
        $model = new \App\Models\AdminGIModel();
    } else {
        return redirect()->back()->with('error', 'Role tidak valid');
    }

    $model->delete($id);
    return redirect()->to('/admink3l/kelola-akun')->with('success', 'User berhasil dihapus');
}

    public function kelolaZona()
{
    $uptModel = new ZonaUptModel();
    $ultgModel = new ZonaUltgModel();
    $giModel = new ZonaGiModel();

    $data['upt'] = $uptModel->findAll();
    $data['ultg'] = $ultgModel->select('zona_ultg.*, zona_upt.nama_upt as nama_upt')
        ->join('zona_upt', 'zona_upt.id = zona_ultg.upt_id')
        ->findAll();
    $data['gi'] = $giModel->select('zona_gi.*, zona_ultg.nama_ultg as nama_ultg')
        ->join('zona_ultg', 'zona_ultg.id = zona_gi.ultg_id')
        ->findAll();

    // ✅ Tambahkan ini supaya upt_list bisa dipakai di view
    $data['upt_list'] = $data['upt'];

    return view('admink3l/kelola_zona', $data);
}


    public function simpanZonaUpt()
    {
        $model = new ZonaUptModel();
        $model->insert(['nama' => $this->request->getPost('nama')]);
        return redirect()->to('/admink3l/kelola-zona');
    }

    public function simpanZonaUltg()
    {
        $model = new ZonaUltgModel();
        $model->insert([
            'nama' => $this->request->getPost('nama'),
            'upt_id' => $this->request->getPost('upt_id'),
        ]);
        return redirect()->to('/admink3l/kelola-zona');
    }

    public function simpanZonaGi()
    {
        $model = new ZonaGiModel();
        $model->insert([
            'nama' => $this->request->getPost('nama'),
            'ultg_id' => $this->request->getPost('ultg_id'),
        ]);
        return redirect()->to('/admink3l/kelola-zona');
    }
    public function laporanGI()
{
    $uptModel = new ZonaUptModel();
    $ultgModel = new ZonaUltgModel();
    $giModel = new ZonaGiModel();
    $kunjunganModel = new KunjunganModel();

    $data['upt_list'] = $uptModel->findAll();
    $data['ultg_list'] = $ultgModel->findAll();
    $data['gi_list'] = $giModel->findAll();

    $upt_id = $this->request->getGet('upt_id');
    $ultg_id = $this->request->getGet('ultg_id');
    $gi_id = $this->request->getGet('gi_id');

    $query = $kunjunganModel;
    if ($gi_id) {
        $query = $query->where('gi_id', $gi_id);
    } elseif ($ultg_id) {
        $query = $query->where('ultg_id', $ultg_id);
    } elseif ($upt_id) {
        $query = $query->where('upt_id', $upt_id);
    }

    $data['kunjungan'] = $query->findAll();
    $data['upt_id'] = $upt_id;
    $data['ultg_id'] = $ultg_id;
    $data['gi_id'] = $gi_id;

    return view('admink3l/laporan_gi', $data);
}


    public function exportExcel()
{
    $kunjunganModel = new \App\Models\KunjunganModel();

    $upt_id = $this->request->getGet('upt_id');
    $ultg_id = $this->request->getGet('ultg_id');
    $gi_id = $this->request->getGet('gi_id');
    $tanggal = $this->request->getGet('tanggal');

    $builder = $kunjunganModel->select('*')
        ->join('zona_upt', 'zona_upt.id = kunjungan.upt_id')
        ->join('zona_ultg', 'zona_ultg.id = kunjungan.ultg_id')
        ->join('zona_gi', 'zona_gi.id = kunjungan.gi_id');

    if ($upt_id) {
        $builder->where('kunjungan.upt_id', $upt_id);
    }
    if ($ultg_id) {
        $builder->where('kunjungan.ultg_id', $ultg_id);
    }
    if ($gi_id) {
        $builder->where('kunjungan.gi_id', $gi_id);
    }
    if ($tanggal) {
        $builder->where('DATE(kunjungan.created_at)', $tanggal);
    }

    $data = $builder->get()->getResultArray();

    // Export ke Excel
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->fromArray(['Nama', 'Instansi', 'Keperluan', 'Tanggal', 'Zona UPT', 'ULTG', 'GI'], null, 'A1');

    $row = 2;
    foreach ($data as $d) {
        $sheet->setCellValue("A$row", $d['nama_tamu']);
        $sheet->setCellValue("B$row", $d['unit']);
        $sheet->setCellValue("C$row", $d['keperluan']);
        $sheet->setCellValue("D$row", $d['created_at']);
        $sheet->setCellValue("E$row", $d['nama_upt']);
        $sheet->setCellValue("F$row", $d['nama_ultg']);
        $sheet->setCellValue("G$row", $d['nama_gi']);
        $row++;
    }

    $filename = 'Laporan-Kunjungan-' . date('YmdHis') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

    public function exportPdf()
{
    $upt_id = $this->request->getGet('upt_id');
    $ultg_id = $this->request->getGet('ultg_id');
    $gi_id = $this->request->getGet('gi_id');

    $kunjunganModel = new KunjunganModel();

    if ($gi_id) {
        $kunjungan = $kunjunganModel->where('gi_id', $gi_id)->findAll();
    } elseif ($ultg_id) {
        $kunjungan = $kunjunganModel->where('ultg_id', $ultg_id)->findAll();
    } elseif ($upt_id) {
        $kunjungan = $kunjunganModel->where('upt_id', $upt_id)->findAll();
    } else {
        $kunjungan = $kunjunganModel->findAll();
    }

    $html = view('admink3l/pdf_laporan', ['kunjungan' => $kunjungan]);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan_kunjungan.pdf');
}


    public function kelolaAkun()
{
    $data['admin_gi']  = (new \App\Models\AdminGIModel())->findAll();
    $data['satpam']    = (new \App\Models\SatpamModel())->findAll();
    $data['gi_list']   = (new \App\Models\ZonaGiModel())->findAll();

    // Buat mapping GI untuk tampilan
    $gi = $data['gi_list'];
    $gi_nama = [];
    foreach ($gi as $g) {
        $gi_nama[$g['id']] = $g['nama_gi'];
    }
    $data['gi_nama'] = $gi_nama;

    return view('admink3l/kelola_akun', $data);
}


public function getStats()
{
    $db = \Config\Database::connect();
    $builder = $db->table('kunjungan');

    $upt = $this->request->getGet('upt');
    $ultg = $this->request->getGet('ultg');
    $gi = $this->request->getGet('gi');

    if ($upt) {
        $builder->where('upt_id', $upt);
    }
    if ($ultg) {
        $builder->where('ultg_id', $ultg);
    }
    if ($gi) {
        $builder->where('gi_id', $gi);
    }

    // Harian (7 hari terakhir)
    $harian = $builder
        ->select("DATE(created_at) as tanggal, COUNT(*) as total")
        ->groupBy("DATE(created_at)")
        ->orderBy("tanggal", 'ASC')
        ->limit(7)
        ->get()
        ->getResultArray();

    // Mingguan (4 minggu terakhir)
    $mingguan = $builder
        ->select("YEARWEEK(created_at, 1) as minggu, COUNT(*) as total")
        ->groupBy("minggu")
        ->orderBy("minggu", 'DESC')
        ->limit(4)
        ->get()
        ->getResultArray();

    // Bulanan (6 bulan terakhir)
    $bulanan = $builder
        ->select("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
        ->groupBy("bulan")
        ->orderBy("bulan", 'DESC')
        ->limit(6)
        ->get()
        ->getResultArray();

    return $this->response->setJSON([
        'harian' => array_reverse($harian),
        'mingguan' => array_reverse($mingguan),
        'bulanan' => array_reverse($bulanan),
    ]);
}


    public function tambahAkun()
{
    $adminGIModel = new AdminGIModel();
    
    // Ambil data dari form
    $username = $this->request->getPost('username');
    $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT); // Hash password
    $giId = $this->request->getPost('gi_id');
    $email = $this->request->getPost('email'); // Ambil email dari form

    // Simpan data admin GI
    $adminData = [
        'username' => $username,
        'password' => $password,
        'gi_id' => $giId,
        'email' => $email, // Simpan email
        'created_at' => date('Y-m-d H:i:s'),
    ];

    // Menyimpan data admin GI ke database
    $adminGIModel->save($adminData);

    return redirect()->to('/admin-gi')->with('success', 'Admin GI berhasil ditambahkan.');
    }
}
