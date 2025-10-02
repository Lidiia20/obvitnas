<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthKoordinatorSatpam implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login dan punya role yang benar
        if (!session()->get('isLoggedIn') || 
            session()->get('role') !== 'koordinator_satpam') {
            
            // Hapus session yang ada
            session()->destroy();
            
            return redirect()->to('/login')
                   ->with('error', 'Akses ditolak. Login sebagai koordinator diperlukan.');
        }
        
        // Tidak ada masalah, lanjutkan
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak melakukan apa-apa
    }
}