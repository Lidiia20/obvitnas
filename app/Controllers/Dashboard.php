<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function tamu()
    {
        // Pastikan hanya tamu yang bisa akses
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'tamu') {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('dashboard/tamu');
    }
}
