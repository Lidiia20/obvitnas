<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\KunjunganModel;
use App\Models\UsersSatpamModel; // Tambahan untuk sistem regu

class Auth extends BaseController
{
    // Fungsi untuk login multi pengguna (admin, satpam, tamu, koordinator)
    public function loginMulti()
    {
        return view('auth/login_multi');
    }

    public function loginPost()
    {
        $session = session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        switch ($role) {
            case 'admin_k3l':
                $model = new \App\Models\AdminK3LModel();
                break;
            case 'admin_gi':
                $model = new \App\Models\AdminGIModel();
                break;
            case 'satpam':
                return $this->handleReguSatpamLogin($email, $password);
            case 'koordinator_satpam':
                return $this->handleKoordinatorLogin($email, $password);
            case 'tamu':
                $model = new UsersModel();
                break;
            default:
                return redirect()->back()->with('error', 'Role tidak valid.');
        }

        // Handle login untuk role selain satpam dan koordinator
        $user = $model->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email atau password salah.');
        }

        $sessionData = [
            'user_id'    => $user['id'],
            'nama'       => $user['nama'],
            'role'       => $role,
            'isLoggedIn' => true
        ];

        if ($role === 'admin_gi') {
            $sessionData['gi_id'] = $user['gi_id'];
        }

        $session->set($sessionData);

        switch ($role) {
            case 'tamu': return redirect()->to('/dashboard/tamu');
            case 'admin_gi': return redirect()->to('/admin-gi/dashboard');
            case 'admin_k3l': return redirect()->to('/dashboard/admink3l');
        }
    }

    // Handle login untuk regu satpam (1 akun per regu)
    private function handleReguSatpamLogin($username, $password)
    {
        $usersSatpamModel = new UsersSatpamModel();
        
        // Validasi login dengan time-based access
        $login_result = $usersSatpamModel->validateLogin($username, $password);
        
        if (is_array($login_result) && isset($login_result['error'])) {
            return redirect()->back()->with('error', $login_result['error']);
        }

        if ($login_result && $login_result['role'] === 'regu') {
            $sessionData = [
                'satpam_logged_in' => true,
                'satpam_user_id' => $login_result['id'],
                'satpam_username' => $login_result['username'],
                'satpam_role' => 'regu',
                'satpam_regu_number' => $login_result['regu_number'],
                'current_jadwal' => $login_result['jadwal'] ?? null,
                'gi_id' => $login_result['gi_id'],
                'nama' => "Regu " . $login_result['regu_number'],
                'role' => 'satpam',
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            return redirect()->to('/satpam/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password regu salah.');
    }

    // Handle login untuk koordinator satpam (DIAN H)
    private function handleKoordinatorLogin($email, $password)
{
    $usersSatpamModel = new UsersSatpamModel();
    $user = $usersSatpamModel->where('email', $email)
                             ->where('role', 'koordinator')
                             ->where('is_active', 1)
                             ->first();

    if ($user && password_verify($password, $user['password'])) {
        $sessionData = [
            'user_id'        => $user['id'],
            'nama'           => $user['nama'] ?? 'Koordinator Satpam',
            'role'           => 'koordinator_satpam',
            'gi_id'          => $user['gi_id'],
            'is_koordinator' => true,
            'isLoggedIn'     => true
        ];
        session()->set($sessionData);
        return redirect()->to('/koordinator/dashboard');
    }

    return redirect()->back()->with('error', 'Email atau password koordinator salah.');
}


    // Fungsi logout tetap sama
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Rest of the methods remain the same...
    // (forgotPasswordForm, sendResetPassword, kirimWa, register, registerPost, compare_faces)

    // Mengirim reset password
    public function sendResetPassword()
    {
        $no_hp = $this->request->getPost('no_hp');
        $usersModel = new UsersModel();
        $user = $usersModel->where('no_hp', $no_hp)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Nomor tidak ditemukan.');
        }

        $passwordBaru = bin2hex(random_bytes(3));
        $usersModel->update($user['id'], [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        $no_hp_internasional = preg_replace('/^0/', '62', $no_hp);

        $pesan = "Halo {$user['nama']}, password Anda telah direset.\nPassword baru: *{$passwordBaru}*\nGunakan untuk login di sistem.";

        $hasil = $this->kirimWa($no_hp_internasional, $pesan);

        if ($hasil === true) {
            return redirect()->to('/login')->with('success', 'Password baru telah dikirim ke WhatsApp.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pesan WA. Coba lagi atau hubungi admin.');
        }
    }

    // Fungsi untuk mengirim pesan WA
    private function kirimWa($tujuan, $pesan)
    {
        $token = 'ajVq92Yp3fUYg2Dozbdz';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $tujuan,
                'message' => $pesan,
                'delay' => 2,
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            log_message('error', 'CURL Error: ' . curl_error($curl));
            curl_close($curl);
            return false;
        }

        curl_close($curl);

        $json = json_decode($response, true);

        return isset($json['status']) && $json['status'] == true;
    }

    // Fungsi untuk menampilkan form registrasi
    public function register()
    {
        return view('auth/register');
    }

    // Fungsi untuk menangani proses registrasi
    public function registerPost()
{
    $validation = \Config\Services::validation();

    $rules = [
        'nama'            => 'required',
        'asal_unit'       => 'required',
        'instansi'        => 'required',
        'alamat'          => 'required',
        'no_hp'           => 'required',
        'email'           => 'required|valid_email|is_unique[users.email]',
        'password'        => 'required|min_length[6]',
        'foto_identitas'  => 'uploaded[foto_identitas]|is_image[foto_identitas]',  // Foto KTP di-upload
        'foto_selfie'     => 'uploaded[foto_selfie]|is_image[foto_selfie]',  // Foto Selfie di-upload
        'no_kendaraan'    => 'required',
        'keperluan'       => 'required',
    ];

    // Validasi input
    if (!$this->validate($rules)) {
        return view('auth/register', [
            'validation' => $validation
        ]);
    }

    // Ambil data dari form
    $nama           = $this->request->getPost('nama');
    $asal_unit      = $this->request->getPost('asal_unit');
    $instansi       = $this->request->getPost('instansi');
    $alamat         = $this->request->getPost('alamat');
    $no_hp          = $this->request->getPost('no_hp');
    $email          = $this->request->getPost('email');
    $password       = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    $no_kendaraan   = $this->request->getPost('no_kendaraan');
    $keperluan      = $this->request->getPost('keperluan');
    $detail         = $this->request->getPost('detail_keperluan');

    // Foto Identitas (KTP)
    $fotoIdentitas  = $this->request->getFile('foto_identitas');
    $fotoIdentitasName = $fotoIdentitas->getRandomName();

    // Foto Selfie
    $fotoSelfie     = $this->request->getFile('foto_selfie');
    $fotoSelfieName = $fotoSelfie->getRandomName();

    // Proses upload foto identitas (KTP)
    if ($fotoIdentitas->isValid() && !$fotoIdentitas->hasMoved()) {
        if (!is_dir('public/uploads/identitas/')) {
            mkdir('public/uploads/identitas/', 0777, true); // Membuat folder jika belum ada
        }
        $fotoIdentitas->move('public/uploads/identitas/', $fotoIdentitasName);
    }

    // Proses upload foto selfie
    if ($fotoSelfie->isValid() && !$fotoSelfie->hasMoved()) {
        if (!is_dir('public/uploads/selfie/')) {
            mkdir('public/uploads/selfie/', 0777, true); // Membuat folder jika belum ada
        }
        $fotoSelfie->move('public/uploads/selfie/', $fotoSelfieName);
    }

    // Simpan data pengguna ke database
    $usersModel = new UsersModel();
    $insertData = [
        'nama'             => $nama,
        'asal_unit'        => $asal_unit,
        'asal_unit_lain'   => $instansi,
        'alamat'           => $alamat,
        'no_hp'            => $no_hp,
        'email'            => $email,
        'password'         => $password,
        'no_kendaraan'     => $no_kendaraan,
        'keperluan'        => $keperluan,
        'detail_keperluan' => $detail,
        'foto_identitas'   => $fotoIdentitasName,  // Simpan nama file foto KTP
        'foto_selfie'      => $fotoSelfieName,  // Simpan nama file foto selfie
        'created_at'       => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s'),
    ];

    $usersModel->insert($insertData);
    $user_id = $usersModel->getInsertID(); // Mendapatkan ID pengguna yang baru saja disimpan


    // Tambahkan data kunjungan otomatis setelah registrasi berhasil
    $kunjunganModel = new KunjunganModel();
    $kunjunganData = [
        'user_id'          => $user_id,
        'gi_id'            => 1,  // GITET New Ujung Berung
        'no_kendaraan'     => $no_kendaraan,
        'keperluan'        => $keperluan,
        'status'           => 'pending',  // Status awal adalah pending
        'created_at'       => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s'),
    ];

    // Simpan data kunjungan ke tabel kunjungan
    $kunjunganModel->insert($kunjunganData);

    // Log pesan untuk debugging
    log_message('debug', 'ID Pengguna yang baru disimpan: ' . $user_id);

    session()->setFlashdata('success', 'Registrasi berhasil. Silakan login.');
    return redirect()->to(base_url('login'));
}


    // Fungsi untuk membandingkan foto KTP dan foto live menggunakan API Face++
    function compare_faces($ktp_image_path, $live_image_path) {
    $api_key = 'wAmOrD6C3BwvEuQPFHFsSL3xj7h8JzvG';  // Ganti dengan API Key Anda
    $api_secret = 'ihok6x2aOXneQDcEnJ6av-KAcNnbUwn_';  // Ganti dengan API Secret Anda
    $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

    $data = [
        'api_key' => $api_key,
        'api_secret' => $api_secret,
    ];

    $files = [
        'image_file1' => new \CURLFile($ktp_image_path),
        'image_file2' => new \CURLFile($live_image_path),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data + $files);

    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response from Face++
    $result = json_decode($response, true);

    if (isset($result['error_message'])) {
        log_message('error', 'Error Face++: ' . $result['error_message']);
        return false;
    }

    $confidence = $result['confidence'];
    log_message('debug', 'Confidence level: ' . $confidence);

    // If confidence is above 70, we consider the faces to be matched
    return $confidence >= 70;
}
}
