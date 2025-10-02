<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\KunjunganModel;
use App\Models\JadwalModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;
use CodeIgniter\Controller;

class AdminGI extends BaseController
{
    protected $GI_ID = 1;

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $kunjunganModel = new KunjunganModel();
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $kunjungan = $kunjunganModel
            ->where('gi_id', $this->GI_ID)
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->findAll();

        $data = [
            'total' => count($kunjungan),
            'approved' => count(array_filter($kunjungan, fn($k) => $k['status'] === 'approved')),
            'checkout' => count(array_filter($kunjungan, fn($k) => $k['status'] === 'checkout')),
            'pending' => count(array_filter($kunjungan, fn($k) => $k['status'] === 'pending')),
            'weekly_data' => $this->rekapMingguan($kunjungan),
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        return view('admin_gi/dashboard', $data);
    }

    private function rekapMingguan($data)
    {
        $weekly = [];
        foreach ($data as $item) {
            $week = date('W', strtotime($item['created_at']));
            $weekly[$week] = ($weekly[$week] ?? 0) + 1;
        }
        return $weekly;
    }

    public function data_kunjungan()
    {
        $model = new KunjunganModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $db = \Config\Database::connect();
        $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan,
                zona_gi.nama_gi as gi_nama, zona_ultg.nama_ultg as ultg_nama, zona_upt.nama_upt as upt_nama
                FROM kunjungan
                LEFT JOIN users ON users.id = kunjungan.user_id
                LEFT JOIN zona_gi ON zona_gi.id = kunjungan.gi_id
                LEFT JOIN zona_ultg ON zona_ultg.id = zona_gi.ultg_id
                LEFT JOIN zona_upt ON zona_upt.id = zona_ultg.upt_id
                WHERE kunjungan.gi_id = ?
                AND MONTH(kunjungan.created_at) = ?
                AND YEAR(kunjungan.created_at) = ?
                ORDER BY kunjungan.created_at DESC";

        $result = $db->query($sql, [$this->GI_ID, $bulan, $tahun]);
        $kunjungan = $result->getResultArray();

        return view('admin_gi/data__kunjungan', [
            'kunjungan' => $kunjungan,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ]);
    }

    public function export()
    {
        $model = new KunjunganModel();
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $builder = $model->where('gi_id', $this->GI_ID);

        if ($start_date && $end_date) {
            $builder = $builder->where('created_at >=', $start_date)->where('created_at <=', $end_date . ' 23:59:59');
        }

        $data['kunjungan'] = $builder->findAll();

        if (empty($data['kunjungan'])) {
            throw new \Exception('Tidak ada data kunjungan untuk diekspor');
        }

        $html = view('admin_gi/kunjungan_pdf', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_kunjungan.pdf', ['Attachment' => true]);
        exit;
    }

    public function exportExcel()
    {
        $model = new KunjunganModel();
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $db = \Config\Database::connect();
        $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan,
                zona_gi.nama_gi as gi_nama, zona_ultg.nama_ultg as ultg_nama, zona_upt.nama_upt as upt_nama
                FROM kunjungan
                LEFT JOIN users ON users.id = kunjungan.user_id
                LEFT JOIN zona_gi ON zona_gi.id = kunjungan.gi_id
                LEFT JOIN zona_ultg ON zona_ultg.id = zona_gi.ultg_id
                LEFT JOIN zona_upt ON zona_upt.id = zona_ultg.upt_id
                WHERE kunjungan.gi_id = ?
                AND MONTH(kunjungan.created_at) = ?
                AND YEAR(kunjungan.created_at) = ?
                ORDER BY kunjungan.created_at DESC";

        $result = $db->query($sql, [$this->GI_ID, $bulan, $tahun]);
        $data = $result->getResultArray();

        if (empty($data)) {
            throw new \Exception('Tidak ada data kunjungan untuk diekspor');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['No', 'Nama Tamu', 'No Kendaraan', 'Keperluan', 'Zona', 'Status', 'Tanggal', 'Waktu'], NULL, 'A1');

        $row = 2;
        foreach ($data as $i => $d) {
            $zona_parts = [];
            if (!empty($d['upt_nama'])) $zona_parts[] = $d['upt_nama'];
            if (!empty($d['ultg_nama'])) $zona_parts[] = $d['ultg_nama'];
            if (!empty($d['gi_nama'])) $zona_parts[] = $d['gi_nama'];
            $zona = implode(' / ', $zona_parts) ?: 'Tidak ada data';

            $sheet->fromArray([
                $i + 1,
                $d['nama_tamu'] ?? 'Tidak ada data',
                $d['no_kendaraan'] ?? 'Tidak ada data',
                $d['keperluan'] ?? 'Tidak ada data',
                $zona,
                ucfirst($d['status']),
                date('d/m/Y', strtotime($d['created_at'])),
                date('H:i', strtotime($d['created_at'])) . ' WIB'
            ], NULL, "A{$row}");
            $row++;
        }

        $filename = "Laporan_Kunjungan_{$bulan}_{$tahun}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $response = service('response');
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', "attachment; filename=\"{$filename}\"");
        $response->setHeader('Cache-Control', 'max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function kelolaUser()
    {
        $satpamModel = new \App\Models\SatpamModel();
        $usersModel = new UsersModel();
        $kunjunganModel = new KunjunganModel();

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $unit = $this->request->getGet('unit');

        $satpamList = $satpamModel->where('gi_id', $this->GI_ID)->findAll();

        foreach ($satpamList as &$s) {
            $s['total_checkin'] = $kunjunganModel
                ->where('gi_id', $this->GI_ID)
                ->where('nama_satpam_checkin', $s['nama'])
                ->where('created_at >=', $start ?? '1970-01-01')
                ->where('created_at <=', ($end ?? date('Y-m-d')) . ' 23:59:59')
                ->countAllResults(false);

            $s['total_checkout'] = $kunjunganModel
                ->where('gi_id', $this->GI_ID)
                ->where('nama_satpam_checkout', $s['nama'])
                ->where('updated_at >=', $start ?? '1970-01-01')
                ->where('updated_at <=', ($end ?? date('Y-m-d')) . ' 23:59:59')
                ->countAllResults(false);
        }

        $usersBuilder = $usersModel->select('users.*, COUNT(kunjungan.id) as total_kunjungan, MAX(kunjungan.created_at) as last_visit')
                                 ->join('kunjungan', 'users.id = kunjungan.user_id', 'left')
                                 ->where('kunjungan.gi_id', $this->GI_ID)
                                 ->orWhere('users.created_at IS NOT NULL');

        if ($start) {
            $usersBuilder->where('users.created_at >=', $start . ' 00:00:00');
        }
        if ($end) {
            $usersBuilder->where('users.created_at <=', $end . ' 23:59:59');
        }
        if ($unit) {
            $usersBuilder->where('users.asal_unit', $unit);
        }

        $usersList = $usersBuilder->groupBy('users.id')
                                 ->orderBy('users.created_at', 'DESC')
                                 ->findAll();

        $unitList = $usersModel->select('asal_unit')
                             ->distinct()
                             ->where('asal_unit IS NOT NULL')
                             ->where('asal_unit !=', '')
                             ->orderBy('asal_unit')
                             ->findAll();

        return view('admin_gi/kelola_user', [
            'satpamList' => $satpamList,
            'usersList' => $usersList,
            'unitList' => $unitList,
            'start' => $start,
            'end' => $end,
            'selectedUnit' => $unit
        ]);
    }

    public function getUserDetail($id)
    {
        $usersModel = new UsersModel();
        $kunjunganModel = new KunjunganModel();

        $user = $usersModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ]);
        }

        $kunjungan = $kunjunganModel->where('user_id', $id)
                                    ->where('gi_id', $this->GI_ID)
                                    ->orderBy('created_at', 'DESC')
                                    ->findAll();

        $user['total_kunjungan'] = count($kunjungan);
        $user['kunjungan_approved'] = count(array_filter($kunjungan, fn($k) => $k['status'] === 'approved'));
        $user['kunjungan_pending'] = count(array_filter($kunjungan, fn($k) => $k['status'] === 'pending'));
        $user['last_visit'] = !empty($kunjungan) ? $kunjungan[0]['created_at'] : null;
        $user['riwayat_kunjungan'] = $kunjungan;

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function editUser($id)
    {
        $usersModel = new UsersModel();

        if ($this->request->getMethod() === 'GET') {
            $user = $usersModel->find($id);
            if (!$user) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException("User dengan ID $id tidak ditemukan");
            }

            return view('admin_gi/edit_user', ['user' => $user]);
        }

        if ($this->request->getMethod() === 'POST') {
            $updateData = [
                'nama' => $this->request->getPost('nama'),
                'asal_unit' => $this->request->getPost('asal_unit'),
                'asal_unit_lain' => $this->request->getPost('asal_unit_lain'),
                'alamat' => $this->request->getPost('alamat'),
                'no_hp' => $this->request->getGet('no_hp'),
                'email' => $this->request->getPost('email'),
                'no_kendaraan' => $this->request->getPost('no_kendaraan'),
                'keperluan' => $this->request->getPost('keperluan'),
                'detail_keperluan' => $this->request->getPost('detail_keperluan'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->request->getPost('password')) {
                $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $usersModel->update($id, $updateData);

            return redirect()->to('/admin-gi/kelolaUser')->with('success', 'Data user berhasil diupdate.');
        }
    }

    public function hapusUser($id)
    {
        (new UsersModel())->delete($id);
        return redirect()->to('/admin-gi/kelolaUser')->with('success', 'User berhasil dihapus.');
    }

    public function kelolaBarang()
    {
        $barangMasukModel = new BarangMasukModel();
        $barangKeluarModel = new BarangKeluarModel();

        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        $barangMasuk = $barangMasukModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $barangKeluar = $barangKeluarModel
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin_gi/kelola_barang', [
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ]);
    }
}