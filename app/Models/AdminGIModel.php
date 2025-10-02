<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminGIModel extends Model
{
    protected $table = 'admin-gi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'gi_id', 'created_at'];
    protected $useTimestamps = false;
}


class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'password', 'role', 'gi_id'];
    protected $useTimestamps = false;
}

class KunjunganModel extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'gi_id', 'status', 'jam_masuk', 'jam_keluar',
        'nama_satpam_checkin', 'nama_satpam_checkout',
        'nomor_kartu_visitor', 'warna_kartu_visitor', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
}


class JadwalModel extends Model
{
    protected $table = 'jadwal_satpam';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_satpam', 'tanggal', 'shift'];
    protected $useTimestamps = false;
}
