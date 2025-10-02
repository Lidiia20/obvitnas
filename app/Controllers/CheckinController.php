<?php

namespace App\Controllers;

class CheckinController extends BaseController
{
    public function index()
    {
        return view('checkin_form');
    }

    public function submit()
    {
        helper('wa');

        $nama = $this->request->getPost('nama');
        $nomor = $this->request->getPost('nomor');
        $satpam = '6281234567890'; // nomor WA satpam

        send_wa($satpam, "Tamu *$nama* telah check-in. Nomor: $nomor");

        return view('checkin_sukses', ['nama' => $nama]);
    }

    public function approve()
    {
        helper('wa');

        $nama = $this->request->getGet('nama');
        $nomor = $this->request->getGet('nomor');

        send_wa($nomor, "Halo $nama, check-in Anda telah disetujui. Silakan masuk.");

        return 'Notifikasi approve terkirim!';
    }
}
