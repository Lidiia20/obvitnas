<?php

namespace App\Controllers;
use App\Models\UsersModel;

class Checkin extends BaseController
{
    public function scan($userId)
    {
        return view('checkin_camera', ['user_id' => $userId]);
    }

    public function verifikasiWajah()
    {
        $userId = $this->request->getPost('user_id');
        $imageData = $this->request->getPost('image_data');

        // Decode base64 to image file
        $imageParts = explode(";base64,", $imageData);
        $imageBase64 = base64_decode($imageParts[1]);
        $filename = 'capture.jpg';
        $filepath = FCPATH . 'uploads/live/' . $filename;

        file_put_contents($filepath, $imageBase64);

        // Ambil data selfie dari database
        $usersModel = new UsersModel();
        $user = $usersModel->find($userId);
        $selfieFile = $user['foto_selfie'];

        $pathSelfie = FCPATH . 'uploads/selfie/' . $selfieFile;

        // Jalankan Python face recognition
        $cmd = escapeshellcmd("python3 python/face_match.py \"$pathSelfie\" \"$filepath\"");
        $result = trim(shell_exec($cmd));

        if ($result === 'match') {
            return redirect()->to('/satpam/dashboard')->with('success', 'Verifikasi wajah berhasil ✅');
        } elseif ($result === 'no_match') {
            return redirect()->back()->with('error', 'Wajah tidak cocok ❌');
        } elseif (str_contains($result, 'error')) {
            return redirect()->back()->with('error', 'Gagal membaca wajah. Coba ulangi.');
        } else {
            return redirect()->back()->with('error', 'Kesalahan sistem.');
        }
    }
}
