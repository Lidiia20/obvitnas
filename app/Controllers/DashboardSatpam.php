<?php

namespace App\Controllers;

use App\Models\KunjunganModel;
use App\Models\UsersModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Controller;

class DashboardSatpam extends BaseController
{
    protected $kunjunganModel;

    public function __construct()
    {
        $this->kunjunganModel = new KunjunganModel();
        
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $gi_id = 1; // GITET New Ujung Berung fixed GI
        
        // Initialize default values
        $kunjungan = [];
        $approved_count = $pending_count = $checkout_count = $total_count = 0;
        
        try {
            // Use direct mysqli connection like in checkin()
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            // Get pending kunjungan
            $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan 
                    FROM kunjungan 
                    LEFT JOIN users ON users.id = kunjungan.user_id 
                    WHERE kunjungan.gi_id = ? AND status = ?";
            
            $stmt = $mysqli->prepare($sql);
            $status = 'pending';
            $stmt->bind_param('is', $gi_id, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $kunjungan = $result->fetch_all(MYSQLI_ASSOC);
            
            // Get counts
            $approved_result = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = $gi_id AND status = 'approved'");
            $approved_count = $approved_result->fetch_assoc()['count'];
            
            $pending_result = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = $gi_id AND status = 'pending'");
            $pending_count = $pending_result->fetch_assoc()['count'];
            
            $checkout_result = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = $gi_id AND status = 'checkout'");
            $checkout_count = $checkout_result->fetch_assoc()['count'];
            
            $total_result = $mysqli->query("SELECT COUNT(*) as count FROM kunjungan WHERE gi_id = $gi_id");
            $total_count = $total_result->fetch_assoc()['count'];
            
            $barang_masuk_count = $barangMasukModel->countAll();

            // Hitung total barang keluar
            $barang_keluar_count = $barangKeluarModel->countAll();

            // Kirim ke view
            $data['barang_masuk_count'] = $barang_masuk_count;
            $data['barang_keluar_count'] = $barang_keluar_count;

            $mysqli->close();
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::dashboard error: ' . $e->getMessage());
        }

        $data = [
            'gi_nama' => 'GITET New Ujung Berung',
            'kunjungan' => $kunjungan,
            'approved_count' => $approved_count,
            'pending_count' => $pending_count,
            'checkout_count' => $checkout_count,
            'total_count' => $total_count,
        ];

        return view('satpam/dashboard', $data);
    }

    public function checkin()
    {
        $gi_id = 1;
        
        try {
            log_message('info', 'DashboardSatpam::checkin - Starting with gi_id: ' . $gi_id);
            
            // Hardcode connection to avoid config issues
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan, kunjungan.undangan 
                    FROM kunjungan 
                    LEFT JOIN users ON users.id = kunjungan.user_id 
                    WHERE kunjungan.gi_id = ? AND status = ?";
            
            $stmt = $mysqli->prepare($sql);
            $status = 'pending';
            $stmt->bind_param('is', $gi_id, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $kunjungan = $result->fetch_all(MYSQLI_ASSOC);
            
            $mysqli->close();
            
            log_message('info', 'DashboardSatpam::checkin - Direct mysqli query returned: ' . count($kunjungan) . ' records');
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::checkin error: ' . $e->getMessage());
            $kunjungan = [];
        }
        
        log_message('info', 'DashboardSatpam::checkin - Final data count: ' . count($kunjungan));
        return view('satpam/checkin', ['kunjungan' => $kunjungan]);
    }

    public function verifikasi_checkin()
    {
        $id = $this->request->getPost('id');
        $jamCheckin = Time::now('Asia/Jakarta', 'en_US')->toDateTimeString();
        
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            // Update kunjungan
            $sql = "UPDATE kunjungan SET 
                    status = ?, 
                    nama_satpam_checkin = ?, 
                    warna_kartu_visitor = ?, 
                    nomor_kartu_visitor = ?, 
                    jam_masuk = ? 
                    WHERE id = ?";
            
            $stmt = $mysqli->prepare($sql);
            $status = 'approved';
            $nama_satpam = $this->request->getPost('nama_satpam_checkin');
            $warna_kartu = $this->request->getPost('warna_kartu_visitor');
            $nomor_kartu = $this->request->getPost('nomor_kartu_visitor');
            
            $stmt->bind_param('sssssi', $status, $nama_satpam, $warna_kartu, $nomor_kartu, $jamCheckin, $id);
            $stmt->execute();
            
            // Get kunjungan and user data
            $sql2 = "SELECT k.*, u.nama, u.no_hp FROM kunjungan k LEFT JOIN users u ON u.id = k.user_id WHERE k.id = ?";
            $stmt2 = $mysqli->prepare($sql2);
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $data = $result->fetch_assoc();
            
            $mysqli->close();
            
            if ($data && !empty($data['no_hp'])) {
                $this->kirimWa($this->format_wa($data['no_hp']), "Halo {$data['nama']}, Anda telah check-in pada pukul {$jamCheckin}.");
            }
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::verifikasi_checkin error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan check-in.');
        }

        return redirect()->to('/satpam/dashboard')->with('success', 'Check-In berhasil. Tamu telah masuk ke sistem.');
    }

    public function checkout()
    {
        $gi_id = 1;
        
        try {
            // Use direct mysqli connection
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan 
                    FROM kunjungan 
                    LEFT JOIN users ON users.id = kunjungan.user_id 
                    WHERE kunjungan.gi_id = ? AND status = ?";
            
            $stmt = $mysqli->prepare($sql);
            $status = 'approved';
            $stmt->bind_param('is', $gi_id, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $kunjungan = $result->fetch_all(MYSQLI_ASSOC);
            
            $mysqli->close();
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::checkout error: ' . $e->getMessage());
            $kunjungan = [];
        }

        return view('satpam/checkout', ['kunjungan' => $kunjungan]);
    }

    public function verifikasi_checkout()
    {
        $id = $this->request->getPost('id');
        $jamKeluar = Time::now('Asia/Jakarta', 'en_US')->toDateTimeString();
        
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            // Update kunjungan
            $sql = "UPDATE kunjungan SET 
                    status = ?, 
                    nama_satpam_checkout = ?, 
                    jam_keluar = ? 
                    WHERE id = ?";
            
            $stmt = $mysqli->prepare($sql);
            $status = 'checkout';
            $nama_satpam = $this->request->getPost('nama_satpam_checkout');
            
            $stmt->bind_param('sssi', $status, $nama_satpam, $jamKeluar, $id);
            $stmt->execute();
            
            // Get kunjungan and user data
            $sql2 = "SELECT k.*, u.nama, u.no_hp FROM kunjungan k LEFT JOIN users u ON u.id = k.user_id WHERE k.id = ?";
            $stmt2 = $mysqli->prepare($sql2);
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $data = $result->fetch_assoc();
            
            $mysqli->close();
            
            if ($data && $data['jam_masuk']) {
                $masuk = Time::parse($data['jam_masuk'], 'Asia/Jakarta');
                $keluar = Time::parse($jamKeluar, 'Asia/Jakarta');
                $durasi = $keluar->difference($masuk);

                $durasiText = '';
                if ($durasi->getHours() > 0) {
                    $durasiText .= $durasi->getHours() . ' jam ';
                }
                if ($durasi->getMinutes() > 0) {
                    $durasiText .= $durasi->getMinutes() . ' menit';
                }

                if (!empty($data['no_hp'])) {
                    $pesan = "Halo {$data['nama']}, Anda telah check-out pada pukul {$jamKeluar}. Lama berada di lokasi: {$durasiText}.";
                    $this->kirimWa($this->format_wa($data['no_hp']), $pesan);
                }
            } else {
                return redirect()->back()->with('error', 'Data jam masuk tidak ditemukan.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::verifikasi_checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan check-out.');
        }
        
        return redirect()->to('/satpam/checkout')->with('success', 'Check-Out berhasil.');
    }

    private function format_wa($no_hp)
    {
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
        return (substr($no_hp, 0, 1) === '0') ? '62' . substr($no_hp, 1) : $no_hp;
    }

    private function kirimWa($target, $pesan)
    {
        $token = 'ajVq92Yp3fUYg2Dozbdz';

        $client = \Config\Services::curlrequest();
        $client->post('https://api.fonnte.com/send', [
            'headers' => ['Authorization' => $token],
            'form_params' => [
                'target' => $target,
                'message' => $pesan,
                'delay' => 2
            ]
        ]);
    }

    public function scanWajah($kunjungan_id)
{
    try {
        $kunjunganModel = new \App\Models\KunjunganModel();
        $usersModel     = new \App\Models\UsersModel();

        // Ambil data kunjungan
        $kunjungan = $kunjunganModel->find($kunjungan_id);
        if (!$kunjungan) {
            return redirect()->back()->with('error', 'Data kunjungan tidak ditemukan.');
        }

        // Ambil data user berdasarkan user_id di tabel kunjungan
        $user = $usersModel->find($kunjungan['user_id']);
        if (!$user) {
            return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Gabungkan data
        $kunjunganArray               = $kunjungan;
        $kunjunganArray['nama']       = $user['nama'];
        $kunjunganArray['foto_identitas'] = $user['foto_identitas'];
        $kunjunganArray['foto_selfie']    = !empty($user['foto_selfie']) 
                                            ? $user['foto_selfie'] 
                                            : 'default.png'; // fallback bila kosong

    } catch (\Exception $e) {
        log_message('error', 'DashboardSatpam::scanWajah error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengambil data kunjungan.');
    }

    return view('satpam/scan_wajah', [
        'kunjungan' => $kunjunganArray
    ]);
}


    public function verifikasiWajah()
{
    $kunjungan_id = $this->request->getPost('kunjungan_id');
    $imageData = $this->request->getPost('image_webcam');

    if (!$imageData) {
        return redirect()->back()->with('error', '❌ Gagal mengambil foto dari webcam.');
    }

    $parts = explode(',', $imageData);
    if (count($parts) !== 2) {
        return redirect()->back()->with('error', '❌ Format gambar tidak valid.');
    }

    $webcamBase64 = $parts[1];

    if (strlen(base64_decode($webcamBase64)) < 1000) {
        return redirect()->back()->with('error', '❌ Gambar dari webcam terlalu kecil atau kosong.');
    }

    try {
        $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
        
        if ($mysqli->connect_error) {
            throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
        }
        
        // Get kunjungan and user data
        $sql = "SELECT k.*, u.foto_identitas FROM kunjungan k LEFT JOIN users u ON u.id = k.user_id WHERE k.id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $kunjungan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        $mysqli->close();
        
        if (!$data) {
            return redirect()->back()->with('error', '❌ Data kunjungan tidak ditemukan.');
        }

        if (empty($data['foto_identitas'])) {
            return redirect()->back()->with('error', '❌ Foto identitas tidak ditemukan.');
        }
        
    } catch (\Exception $e) {
        log_message('error', 'DashboardSatpam::verifikasiWajah error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengambil data untuk verifikasi.');
    }

    // PERBAIKAN PATH - Cek beberapa kemungkinan lokasi file
    $possible_paths = [
        FCPATH . 'public/uploads/identitas/' . $data['foto_identitas'],
        FCPATH . 'uploads/identitas/' . $data['foto_identitas'],
        WRITEPATH . '../public/uploads/identitas/' . $data['foto_identitas'],
        ROOTPATH . 'public/uploads/identitas/' . $data['foto_identitas']
    ];
    
    $ktp_image_path = null;
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $ktp_image_path = $path;
            log_message('info', '✅ File foto identitas ditemukan di: ' . $path);
            break;
        }
    }
    
    // Jika tidak ditemukan, log semua path yang dicoba
    if (!$ktp_image_path) {
        log_message('error', '❌ Foto identitas tidak ditemukan di semua lokasi yang dicoba:');
        foreach ($possible_paths as $path) {
            log_message('error', '   - ' . $path);
        }
        return redirect()->back()->with('error', '❌ Foto identitas tidak ditemukan di server.');
    }

    $is_verified = $this->compare_faces($ktp_image_path, $webcamBase64);

    if ($is_verified) {
        session()->set("verified_face_{$kunjungan_id}", true);
        session()->setFlashdata('success', '✅ Verifikasi wajah berhasil.');
        return redirect()->to("/satpam/formCheckin/{$kunjungan_id}");
    } else {
        // Cek log terbaru untuk memberikan pesan yang lebih spesifik
        $log_file = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.log';
        $recent_logs = [];
        if (file_exists($log_file)) {
            $logs = file($log_file);
            $recent_logs = array_slice($logs, -10); // Ambil 10 baris terakhir
        }
        
        $error_message = '❌ Verifikasi wajah gagal. ';
        
        // Cek log untuk pesan error spesifik
        foreach ($recent_logs as $log) {
            if (strpos($log, '❌ Tidak ada wajah terdeteksi') !== false) {
                $error_message .= 'Tidak ada wajah terdeteksi dalam gambar. Pastikan wajah terlihat jelas di kamera.';
                break;
            } elseif (strpos($log, '❌ Gambar terlalu blur') !== false) {
                $error_message .= 'Gambar terlalu blur. Pastikan kamera fokus dan pencahayaan cukup.';
                break;
            } elseif (strpos($log, '❌ Wajah terlalu kecil') !== false) {
                $error_message .= 'Wajah terlalu kecil dalam gambar. Dekatkan wajah ke kamera.';
                break;
            } elseif (strpos($log, '❌ Gambar webcam terlalu kecil') !== false) {
                $error_message .= 'Kualitas gambar terlalu rendah. Pastikan koneksi kamera stabil.';
                break;
            }
        }
        
        // Jika tidak ada error spesifik, gunakan pesan default
        if ($error_message === '❌ Verifikasi wajah gagal. ') {
            $error_message .= 'Pastikan wajah jelas, terang, dan menghadap kamera dengan baik.';
        }
        
        session()->setFlashdata('error', $error_message);
        return redirect()->back();
    }
}

    public function formCheckin($kunjungan_id) {
    try {
        $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
        
        if ($mysqli->connect_error) {
            throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
        }
        
        // Get kunjungan data
        $sql = "SELECT * FROM kunjungan WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $kunjungan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $kunjungan = $result->fetch_assoc();
        
        if (!$kunjungan) {
            $mysqli->close();
            return redirect()->to('/satpam/checkin')->with('error', 'Data kunjungan tidak ditemukan.');
        }
        
        // Get user data
        $sql2 = "SELECT * FROM users WHERE id = ?";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param('i', $kunjungan['user_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $user = $result2->fetch_assoc();
        
        if (!$user) {
            $mysqli->close();
            return redirect()->to('/satpam/checkin')->with('error', 'Data user tidak ditemukan.');
        }
        
        // Pastikan wajah telah diverifikasi
        if (!session()->get('verified_face_' . $kunjungan_id)) {
            $mysqli->close();
            return redirect()->to('/satpam/checkin')->with('error', 'Wajah belum diverifikasi.');
        }
        
        // Get all active satpam from database
        $satpam_sql = "SELECT id, nama, email, no_hp, alamat, gi_id, regu_number, is_koordinator, status 
                       FROM satpam 
                       WHERE status = 'active' 
                       ORDER BY regu_number ASC, nama ASC";
        
        $result_satpam = $mysqli->query($satpam_sql);
        $satpam_regu = [];
        
        if ($result_satpam) {
            while ($row = $result_satpam->fetch_assoc()) {
                $satpam_regu[] = [
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'email' => $row['email'] ?? '',
                    'nip' => '', // NIP tidak ada di struktur tabel ini
                    'posisi' => $row['is_koordinator'] == 1 ? 'Koordinator' : 'Satpam',
                    'regu_number' => $row['regu_number'],
                    'is_koordinator' => $row['is_koordinator'] == 1
                ];
            }
        }
        
        // Jika tidak ada satpam aktif, buat data fallback
        if (empty($satpam_regu)) {
            $satpam_regu = [
                ['id' => 1, 'nama' => 'DIAN H', 'nip' => '', 'posisi' => 'Koordinator', 'regu_number' => 1],
                ['id' => 2, 'nama' => 'DIDIN S', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 1],
                ['id' => 3, 'nama' => 'SANDI PURNAMA', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 1],
                ['id' => 4, 'nama' => 'DIK DIK', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 1],
                ['id' => 6, 'nama' => 'ARI H', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 2],
                ['id' => 7, 'nama' => 'M. WIJATHMINTA', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 2],
                ['id' => 8, 'nama' => 'AHMAD RIFA F', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 2],
                ['id' => 10, 'nama' => 'AAN', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 3],
                ['id' => 11, 'nama' => 'ATIF HIDAYAT', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 3],
                ['id' => 12, 'nama' => 'DADANG S', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 3],
                ['id' => 14, 'nama' => 'TAUFIK Z', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 4],
                ['id' => 15, 'nama' => 'ABDUL AZIZ', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 4],
                ['id' => 16, 'nama' => 'DIAN EFFENDI', 'nip' => '', 'posisi' => 'Satpam', 'regu_number' => 4]
            ];
            log_message('info', 'DashboardSatpam::formCheckin - Using fallback satpam data');
        }
        
        // Set current shift info berdasarkan waktu
        $current_hour = (int)date('H');
        $current_shift = [
            'regu_number' => 1,
            'shift' => 'P',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '16:00:00'
        ];
        
        if ($current_hour >= 8 && $current_hour < 16) {
            $current_shift = ['regu_number' => 1, 'shift' => 'P', 'jam_mulai' => '08:00:00', 'jam_selesai' => '16:00:00'];
        } elseif ($current_hour >= 16 && $current_hour < 24) {
            $current_shift = ['regu_number' => 2, 'shift' => 'S', 'jam_mulai' => '16:00:00', 'jam_selesai' => '24:00:00'];
        } else {
            $current_shift = ['regu_number' => 3, 'shift' => 'M', 'jam_mulai' => '00:00:00', 'jam_selesai' => '08:00:00'];
        }
        
        $mysqli->close();
        
        // Log data untuk debugging
        log_message('info', 'DashboardSatpam::formCheckin - Data kunjungan: ' . json_encode($kunjungan));
        log_message('info', 'DashboardSatpam::formCheckin - Data user: ' . json_encode($user));
        log_message('info', 'DashboardSatpam::formCheckin - Current shift: ' . json_encode($current_shift));
        log_message('info', 'DashboardSatpam::formCheckin - Satpam count: ' . count($satpam_regu));
        
    } catch (\Exception $e) {
        if (isset($mysqli)) {
            $mysqli->close();
        }
        log_message('error', 'DashboardSatpam::formCheckin error: ' . $e->getMessage());
        return redirect()->to('/satpam/checkin')->with('error', 'Gagal mengambil data kunjungan: ' . $e->getMessage());
    }

    return view('satpam/form_checkin', [
        'kunjungan' => $kunjungan,
        'user' => $user,
        'current_shift' => $current_shift,
        'satpam_regu' => $satpam_regu,
    ]);
}

    function compare_faces($ktp_image_path, $live_image_base64) {
        $api_key = 'wAmOrD6C3BwvEuQPFHFsSL3xj7h8JzvG';
        $api_secret = 'ihok6x2aOXneQDcEnJ6av-KAcNnbUwn_';
        $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

        if (!file_exists($ktp_image_path)) {
            log_message('error', '❌ Foto KTP tidak ditemukan: ' . $ktp_image_path);
            return false;
        }

        // Simpan gambar webcam (base64) ke file sementara
        $temp_file = tempnam(sys_get_temp_dir(), 'webcam_') . '.jpg';
        file_put_contents($temp_file, base64_decode($live_image_base64));

        if (!file_exists($temp_file)) {
            log_message('error', '❌ Gagal menyimpan gambar webcam.');
            return false;
        }

        // Cek ukuran file untuk memastikan gambar tidak terlalu kecil
        $file_size = filesize($temp_file);
        if ($file_size < 5000) { // Minimal 5KB
            log_message('error', '❌ Gambar webcam terlalu kecil: ' . $file_size . ' bytes');
            @unlink($temp_file);
            return false;
        }

        log_message('debug', '✅ File webcam disimpan: ' . $temp_file);
        log_message('debug', '📏 Ukuran file webcam: ' . $file_size . ' bytes');

        $data = [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'image_file1' => new \CURLFile($ktp_image_path),
            'image_file2' => new \CURLFile($temp_file),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout 30 detik

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        @unlink($temp_file); // Hapus setelah selesai

        if ($http_code !== 200) {
            log_message('error', '❌ Face++ API error HTTP: ' . $http_code);
            return false;
        }

        $result = json_decode($response, true);

        if (isset($result['error_message'])) {
            log_message('error', 'Face++ error: ' . $result['error_message']);
            
            // Berikan pesan error yang lebih spesifik
            if (strpos($result['error_message'], 'no face') !== false) {
                log_message('error', '❌ Tidak ada wajah terdeteksi dalam gambar');
            } elseif (strpos($result['error_message'], 'blur') !== false) {
                log_message('error', '❌ Gambar terlalu blur');
            } elseif (strpos($result['error_message'], 'too small') !== false) {
                log_message('error', '❌ Wajah terlalu kecil dalam gambar');
            }
            
            return false;
        }

        if (isset($result['confidence'])) {
            $confidence = $result['confidence'];
            log_message('info', "🎯 Face++ confidence: {$confidence}");
            
            // Threshold yang lebih rendah untuk testing
            $threshold = 30; // Turunkan dari 50 ke 30
            
            if ($confidence >= $threshold) {
                log_message('info', "✅ Verifikasi berhasil dengan confidence: {$confidence} (threshold: {$threshold})");
                return true;
            } else {
                log_message('info', "❌ Verifikasi gagal dengan confidence: {$confidence} (threshold: {$threshold})");
                return false;
            }
        }

        log_message('error', '❌ Face++ tidak mengembalikan nilai confidence');
        return false;
    }


    public function historyKunjungan()
    {
        // Ambil parameter filter dari URL
        $filters = $this->request->getGet();
        
        $gi_id = 1; // GITET New Ujung Berung fixed GI
        
        try {
            $mysqli = new \mysqli('localhost', 'root', '', 'if0_39600889_obvitnas_db');
            
            if ($mysqli->connect_error) {
                throw new \Exception('Database connection failed: ' . $mysqli->connect_error);
            }
            
            // Build SQL query with filters
            $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan 
                    FROM kunjungan 
                    LEFT JOIN users ON users.id = kunjungan.user_id 
                    WHERE kunjungan.gi_id = ? AND kunjungan.status != 'pending'";
            
            $params = [$gi_id];
            $types = 'i';
            
            // Add filters
            if (!empty($filters['nama'])) {
                $sql .= " AND users.nama LIKE ?";
                $params[] = '%' . $filters['nama'] . '%';
                $types .= 's';
            }
            
            if (!empty($filters['keperluan'])) {
                $sql .= " AND kunjungan.keperluan LIKE ?";
                $params[] = '%' . $filters['keperluan'] . '%';
                $types .= 's';
            }
            
            if (!empty($filters['tanggal'])) {
                $sql .= " AND DATE(kunjungan.created_at) = ?";
                $params[] = $filters['tanggal'];
                $types .= 's';
            }
            
            $sql .= " ORDER BY kunjungan.created_at DESC";
            
            $stmt = $mysqli->prepare($sql);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $kunjungan = $result->fetch_all(MYSQLI_ASSOC);
            
            $mysqli->close();
            
        } catch (\Exception $e) {
            log_message('error', 'DashboardSatpam::historyKunjungan error: ' . $e->getMessage());
            $kunjungan = [];
        }

        $data = [
            'kunjungan' => $kunjungan,
            'filters' => $filters,
        ];

        return view('satpam/history_kunjungan', $data);
    }

    

}
