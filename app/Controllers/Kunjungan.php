<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\KunjunganModel;

class Kunjungan extends BaseController
{
    public function form()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new UsersModel();
        $user = $model->find($session->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Data user tidak ditemukan. Silakan login ulang.');
        }

        return view('kunjungan/form', [
            'user' => $user,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ]);
    }

    public function submit()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'no_kendaraan' => 'required',
            'keperluan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $validation->getErrors()));
        }

        $user_id = $session->get('user_id');
        $keperluan = $this->request->getPost('keperluan');
        $detail_keperluan = $this->request->getPost('detail_keperluan');
        $no_kendaraan = $this->request->getPost('no_kendaraan');
        $undanganFile = $this->request->getFile('undangan');

        // Upload file undangan jika keperluan adalah rapat
        $undanganName = null;
        if ($keperluan === 'Rapat' && $undanganFile && $undanganFile->isValid()) {
            $undanganName = $undanganFile->getRandomName();
            $undanganFile->move('public/uploads/undangan', $undanganName);
        }

        // Simpan ke tabel kunjungan
        $kunjunganModel = new KunjunganModel();
        $kunjunganModel->insert([
            'user_id' => $user_id,
            'gi_id' => 1,
            'no_kendaraan' => $no_kendaraan,
            'keperluan' => $keperluan,
            'undangan' => $undanganName,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/kunjungan/form')->with('success', 'Data kunjungan berhasil dikirim.');
    }

    public function scanWajah($id)
    {
        return view('kunjungan/scan_wajah', ['user_id' => $id]);
    }

    public function scanProcess()
    {
        $base64 = $this->request->getPost('image_data');
        $user_id = session()->get('user_id');

        // Simpan gambar webcam sementara
        $imageName = uniqid() . '.jpg';
        $imagePath = FCPATH . 'uploads/temp/' . $imageName;

        // Mengkonversi data base64 menjadi file gambar
        file_put_contents($imagePath, file_get_contents(str_replace('data:image/jpeg;base64,', '', $base64)));

        $users = new UsersModel();
        $user = $users->find($user_id);
        $fotoSelfiePath = FCPATH . 'public/uploads/selfie/' . $user['foto_selfie'];

        // Verifikasi wajah menggunakan Face++ API
        $result = $this->verifyFace($imagePath, $fotoSelfiePath);

        unlink($imagePath); // hapus foto webcam setelah digunakan

        if ($result && isset($result['confidence']) && $result['confidence'] > 80) {
            return redirect()->to('/kunjungan/form')->with('success', 'Verifikasi wajah berhasil.');
        } else {
            return redirect()->to('/kunjungan/form')->with('error', 'Verifikasi wajah gagal. Coba lagi.');
        }
    }

    private function verifyFace($image1, $image2)
    {
        $api_key = 'wAmOrD6C3BwvEuQPFHFsSL3xj7h8JzvG';
        $api_secret = 'ihok6x2aOXneQDcEnJ6av-KAcNnbUwn_';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-us.faceplusplus.com/facepp/v3/compare',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'image_file1' => new CURLFile($image1),
                'image_file2' => new CURLFile($image2),
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
    