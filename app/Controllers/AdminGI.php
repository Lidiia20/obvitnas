<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\KunjunganModel;
use App\Models\JadwalModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminGI extends BaseController
{
    protected $GI_ID = 1;

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
{
    try {
        $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
        
        if ($mysqli->connect_error) {
            throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        log_message('info', '=== DASHBOARD DEBUG START ===');
        
        // Get kunjungan counts (sama seperti sebelumnya)
        $total_query = "SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = {$this->GI_ID}";
        log_message('info', 'Query total: ' . $total_query);
        $total_result = $mysqli->query($total_query);
        $total_count = $total_result ? $total_result->fetch_assoc()['count'] : 0;
        log_message('info', 'Total count: ' . $total_count);
        
        $approved_query = "SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = {$this->GI_ID} AND status = 'approved'";
        $approved_result = $mysqli->query($approved_query);
        $approved_count = $approved_result ? $approved_result->fetch_assoc()['count'] : 0;
        
        $pending_query = "SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = {$this->GI_ID} AND status = 'pending'";
        $pending_result = $mysqli->query($pending_query);
        $pending_count = $pending_result ? $pending_result->fetch_assoc()['count'] : 0;
        
        $checkout_query = "SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = {$this->GI_ID} AND status = 'checkout'";
        $checkout_result = $mysqli->query($checkout_query);
        $checkout_count = $checkout_result ? $checkout_result->fetch_assoc()['count'] : 0;

        // Get barang counts (sama seperti sebelumnya)
        $barang_masuk_count = 0;
        $barang_keluar_count = 0;
        
        $check_masuk = $mysqli->query("SHOW COLUMNS FROM barang_masuk LIKE 'gi_id'");
        if ($check_masuk && $check_masuk->num_rows > 0) {
            log_message('info', 'Kolom gi_id ADA di barang_masuk');
            $barang_masuk_result = $mysqli->query("SELECT COUNT(*) as count FROM barang_masuk WHERE gi_id = {$this->GI_ID}");
            if ($barang_masuk_result) {
                $barang_masuk_count = $barang_masuk_result->fetch_assoc()['count'];
            }
        } else {
            log_message('warning', 'Kolom gi_id TIDAK ADA di barang_masuk - hitung semua data');
            $barang_masuk_result = $mysqli->query("SELECT COUNT(*) as count FROM barang_masuk");
            if ($barang_masuk_result) {
                $barang_masuk_count = $barang_masuk_result->fetch_assoc()['count'];
            }
        }
        log_message('info', 'Barang masuk count: ' . $barang_masuk_count);
        
        $check_keluar = $mysqli->query("SHOW COLUMNS FROM barang_keluar LIKE 'gi_id'");
        if ($check_keluar && $check_keluar->num_rows > 0) {
            log_message('info', 'Kolom gi_id ADA di barang_keluar');
            $barang_keluar_result = $mysqli->query("SELECT COUNT(*) as count FROM barang_keluar WHERE gi_id = {$this->GI_ID}");
            if ($barang_keluar_result) {
                $barang_keluar_count = $barang_keluar_result->fetch_assoc()['count'];
            }
        } else {
            log_message('warning', 'Kolom gi_id TIDAK ADA di barang_keluar - hitung semua data');
            $barang_keluar_result = $mysqli->query("SELECT COUNT(*) as count FROM barang_keluar");
            if ($barang_keluar_result) {
                $barang_keluar_count = $barang_keluar_result->fetch_assoc()['count'];
            }
        }
        log_message('info', 'Barang keluar count: ' . $barang_keluar_count);

        // Get monthly data untuk 12 bulan terakhir (perbaikan: handle DateTime lebih aman)
        $monthly_data = [];
        $start_date = date('Y-m-01', strtotime('-11 months'));
        $end_date = date('Y-m-t', strtotime('now'));  // Pastikan end_date valid
        $sql = "SELECT 
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count
        FROM kunjungan 
        WHERE gi_id = ? 
        AND created_at >= ? AND created_at <= ?
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY year, month";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new \Exception('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('iss', $this->GI_ID, $start_date, $end_date);  // 'i' untuk gi_id, 'ss' untuk dates
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        $counts = [];
        foreach ($rows as $row) {
            $dt = sprintf('%04d-%02d', $row['year'], $row['month']);
            $counts[$dt] = (int)$row['count'];  // Cast ke int
        }
        
        // Loop aman untuk generate 12 bulan
        $current_date = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        while ($current_date <= $end) {
            $key = $current_date->format('M Y');  // e.g., 'Oct 2025'
            $ym = $current_date->format('Y-m');   // e.g., '2025-10'
            $monthly_data[$key] = $counts[$ym] ?? 0;
            $current_date->modify('+1 month');
        }
        log_message('info', 'Monthly data keys: ' . implode(', ', array_keys($monthly_data)));
        log_message('info', 'Monthly data values: ' . json_encode(array_values($monthly_data)));

        // Get recent kunjungan (sama)
        $kunjungan_result = $mysqli->query("SELECT id, status, created_at, nama_satpam_checkin, nama_satpam_checkout 
                             FROM kunjungan WHERE gi_id = {$this->GI_ID} ORDER BY created_at DESC LIMIT 10");
        $kunjungan = [];
        if ($kunjungan_result) {
            $kunjungan = $kunjungan_result->fetch_all(MYSQLI_ASSOC);
        }

        $mysqli->close();

        $data = [
            'total_count' => $total_count,
            'approved_count' => $approved_count,
            'pending_count' => $pending_count,
            'checkout_count' => $checkout_count,
            'barang_masuk_count' => $barang_masuk_count,
            'barang_keluar_count' => $barang_keluar_count,
            'monthly_data' => $monthly_data,  // Pastikan nama ini
            'kunjungan' => $kunjungan,
        ];

        log_message('info', 'Dashboard Data: ' . json_encode($data));
        log_message('info', '=== DASHBOARD DEBUG END ===');

    } catch (\Exception $e) {
        log_message('error', 'AdminGI::dashboard error: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        
        $data = [
            'total_count' => 0,
            'approved_count' => 0,
            'pending_count' => 0,
            'checkout_count' => 0,
            'barang_masuk_count' => 0,
            'barang_keluar_count' => 0,
            'monthly_data' => [],  // Default kosong
            'kunjungan' => [],
        ];
    }

    return view('admin_gi/dashboard', $data);
}

    public function data_kunjungan()
    {
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

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

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('iis', $this->GI_ID, $bulan, $tahun);
            $stmt->execute();
            $result = $stmt->get_result();
            $kunjungan = $result->fetch_all(MYSQLI_ASSOC);

            $mysqli->close();

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::data_kunjungan error: ' . $e->getMessage());
            $kunjungan = [];
        }

        return view('admin_gi/data__kunjungan', [
            'kunjungan' => $kunjungan,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ]);
    }

    public function export()
{
    $start_date = $this->request->getGet('start_date');
    $end_date = $this->request->getGet('end_date');

    try {
        $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
        
        if ($mysqli->connect_error) {
            throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan,
                zona_gi.nama_gi as gi_nama, zona_ultg.nama_ultg as ultg_nama, zona_upt.nama_upt as upt_nama,
                kunjungan.nama_satpam_checkin, kunjungan.nama_satpam_checkout
                FROM kunjungan
                LEFT JOIN users ON users.id = kunjungan.user_id
                LEFT JOIN zona_gi ON zona_gi.id = kunjungan.gi_id
                LEFT JOIN zona_ultg ON zona_ultg.id = zona_gi.ultg_id
                LEFT JOIN zona_upt ON zona_upt.id = zona_ultg.upt_id
                WHERE kunjungan.gi_id = ?";
        
        $params = [$this->GI_ID];
        $types = 'i';

        if ($start_date && $end_date) {
            $sql .= " AND created_at >= ? AND created_at <= ?";
            $params[] = $start_date;
            $params[] = $end_date . ' 23:59:59';
            $types .= 'ss';
        }

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $kunjungan = $result->fetch_all(MYSQLI_ASSOC);

        $mysqli->close();

        if (empty($kunjungan)) {
            throw new \Exception('Tidak ada data kunjungan untuk diekspor');
        }

        $data['kunjungan'] = $kunjungan;
        $html = view('admin_gi/kunjungan_pdf', $data); // Pastikan view PDF diperbarui
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_kunjungan.pdf', ['Attachment' => true]);
        exit;

    } catch (\Exception $e) {
        log_message('error', 'AdminGI::export error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
    }
}

    public function exportExcel()
{
    $bulan = $this->request->getGet('month') ?? date('m');
    $tahun = $this->request->getGet('year') ?? date('Y');

    try {
        $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
        
        if ($mysqli->connect_error) {
            throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
        }

        $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan,
                zona_gi.nama_gi as gi_nama, zona_ultg.nama_ultg as ultg_nama, zona_upt.nama_upt as upt_nama,
                kunjungan.nama_satpam_checkin, kunjungan.nama_satpam_checkout
                FROM kunjungan
                LEFT JOIN users ON users.id = kunjungan.user_id
                LEFT JOIN zona_gi ON zona_gi.id = kunjungan.gi_id
                LEFT JOIN zona_ultg ON zona_ultg.id = zona_gi.ultg_id
                LEFT JOIN zona_upt ON zona_upt.id = zona_ultg.upt_id
                WHERE kunjungan.gi_id = ?
                AND MONTH(kunjungan.created_at) = ?
                AND YEAR(kunjungan.created_at) = ?
                ORDER BY kunjungan.created_at DESC";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('iis', $this->GI_ID, $bulan, $tahun);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $mysqli->close();

        if (empty($data)) {
            throw new \Exception('Tidak ada data kunjungan untuk diekspor');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['No', 'Nama Tamu', 'No Kendaraan', 'Keperluan', 'Zona', 'Status', 'Tanggal', 'Waktu', 'Nama Satpam Check-In', 'Nama Satpam Check-Out'], NULL, 'A1');

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
                date('H:i', strtotime($d['created_at'])) . ' WIB',
                $d['nama_satpam_checkin'] ?? '-',
                $d['nama_satpam_checkout'] ?? '-'
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

    } catch (\Exception $e) {
        log_message('error', 'AdminGI::exportExcel error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menekspor data: ' . $e->getMessage());
    }
}

    public function kelolaUser()
    {
        $satpamModel = new \App\Models\SatpamModel();
        $usersModel = new UsersModel();
        $kunjunganModel = new KunjunganModel();

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $unit = $this->request->getGet('unit');

        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

            // Fetch satpam list
            $sql = "SELECT * FROM satpam WHERE gi_id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $this->GI_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $satpamList = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($satpamList as &$s) {
                $s['total_checkin'] = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan 
                    WHERE gi_id = {$this->GI_ID} 
                    AND nama_satpam_checkin = '{$s['nama']}' 
                    AND created_at >= '" . ($start ?? '1970-01-01') . "' 
                    AND created_at <= '" . ($end ?? date('Y-m-d')) . " 23:59:59'")->fetch_assoc()['count'];

                $s['total_checkout'] = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan 
                    WHERE gi_id = {$this->GI_ID} 
                    AND nama_satpam_checkout = '{$s['nama']}' 
                    AND updated_at >= '" . ($start ?? '1970-01-01') . "' 
                    AND updated_at <= '" . ($end ?? date('Y-m-d')) . " 23:59:59'")->fetch_assoc()['count'];
            }

            // Fetch users list
            $users_sql = "SELECT users.*, COUNT(kunjungan.id) as total_kunjungan, MAX(kunjungan.created_at) as last_visit
                         FROM users
                         LEFT JOIN kunjungan ON users.id = kunjungan.user_id AND kunjungan.gi_id = ?
                         WHERE users.created_at IS NOT NULL";
            
            $params = [$this->GI_ID];
            $types = 'i';

            if ($start) {
                $users_sql .= " AND users.created_at >= ?";
                $params[] = $start . ' 00:00:00';
                $types .= 's';
            }
            if ($end) {
                $users_sql .= " AND users.created_at <= ?";
                $params[] = $end . ' 23:59:59';
                $types .= 's';
            }
            if ($unit) {
                $users_sql .= " AND users.asal_unit = ?";
                $params[] = $unit;
                $types .= 's';
            }

            $users_sql .= " GROUP BY users.id ORDER BY users.created_at DESC";

            $users_stmt = $mysqli->prepare($users_sql);
            $users_stmt->bind_param($types, ...$params);
            $users_stmt->execute();
            $users_result = $users_stmt->get_result();
            $usersList = $users_result->fetch_all(MYSQLI_ASSOC);

            // Fetch unit list
            $unit_sql = "SELECT DISTINCT asal_unit FROM users WHERE asal_unit IS NOT NULL AND asal_unit != '' ORDER BY asal_unit";
            $unit_result = $mysqli->query($unit_sql);
            $unitList = $unit_result->fetch_all(MYSQLI_ASSOC);

            $mysqli->close();

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::kelolaUser error: ' . $e->getMessage());
            $satpamList = [];
            $usersList = [];
            $unitList = [];
        }

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
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            $kunjungan_sql = "SELECT * FROM kunjungan WHERE user_id = ? AND gi_id = ? ORDER BY created_at DESC";
            $kunjungan_stmt = $mysqli->prepare($kunjungan_sql);
            $kunjungan_stmt->bind_param('ii', $id, $this->GI_ID);
            $kunjungan_stmt->execute();
            $kunjungan_result = $kunjungan_stmt->get_result();
            $kunjungan = $kunjungan_result->fetch_all(MYSQLI_ASSOC);

            $mysqli->close();

            $user['total_kunjungan'] = count($kunjungan);
            $user['kunjungan_approved'] = count(array_filter($kunjungan, fn($k) => $k['status'] === 'approved'));
            $user['kunjungan_pending'] = count(array_filter($kunjungan, fn($k) => $k['status'] === 'pending'));
            $user['last_visit'] = !empty($kunjungan) ? $kunjungan[0]['created_at'] : null;
            $user['riwayat_kunjungan'] = $kunjungan;

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::getUserDetail error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengambil data user: ' . $e->getMessage()
            ]);
        }
    }

    public function editUser($id)
    {
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

            if ($this->request->getMethod() === 'GET') {
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                $mysqli->close();

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
                    'no_hp' => $this->request->getPost('no_hp'),
                    'email' => $this->request->getPost('email'),
                    'no_kendaraan' => $this->request->getPost('no_kendaraan'),
                    'keperluan' => $this->request->getPost('keperluan'),
                    'detail_keperluan' => $this->request->getPost('detail_keperluan'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if ($this->request->getPost('password')) {
                    $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }

                $sql = "UPDATE users SET " . implode(', ', array_map(fn($key) => "$key = ?", array_keys($updateData))) . " WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $values = array_values($updateData);
                $values[] = $id;
                $types = str_repeat('s', count($updateData)) . 'i';
                $stmt->bind_param($types, ...$values);
                $stmt->execute();

                $mysqli->close();

                return redirect()->to('/admin-gi/kelolaUser')->with('success', 'Data user berhasil diupdate.');
            }

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::editUser error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate data user: ' . $e->getMessage());
        }
    }

    public function hapusUser($id)
    {
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $mysqli->close();

            return redirect()->to('/admin-gi/kelolaUser')->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::hapusUser error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function kelolaBarang()
    {
        $bulan = $this->request->getGet('month') ?? date('m');
        $tahun = $this->request->getGet('year') ?? date('Y');

        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }

            // Fetch barang masuk
            $sql_masuk = "SELECT * FROM barang_masuk 
                         WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
                         ORDER BY created_at DESC";
            $stmt_masuk = $mysqli->prepare($sql_masuk);
            $stmt_masuk->bind_param('ii', $bulan, $tahun);
            $stmt_masuk->execute();
            $result_masuk = $stmt_masuk->get_result();
            $barangMasuk = $result_masuk->fetch_all(MYSQLI_ASSOC);

            // Fetch barang keluar
            $sql_keluar = "SELECT * FROM barang_keluar 
                          WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
                          ORDER BY created_at DESC";
            $stmt_keluar = $mysqli->prepare($sql_keluar);
            $stmt_keluar->bind_param('ii', $bulan, $tahun);
            $stmt_keluar->execute();
            $result_keluar = $stmt_keluar->get_result();
            $barangKeluar = $result_keluar->fetch_all(MYSQLI_ASSOC);

            $mysqli->close();

        } catch (\Exception $e) {
            log_message('error', 'AdminGI::kelolaBarang error: ' . $e->getMessage());
            $barangMasuk = [];
            $barangKeluar = [];
        }

        return view('admin_gi/kelola_barang', [
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'selected_month' => $bulan,
            'selected_year' => $tahun
        ]);
    }
}