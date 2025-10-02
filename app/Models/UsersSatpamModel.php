<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersSatpamModel extends Model
{
    protected $table = 'users_satpam';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'password', 'role', 'regu_number', 'gi_id', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validasi login dengan time-based access
    public function validateLogin($username, $password)
    {
        // Pastikan timezone Asia/Jakarta
        $tz = new \DateTimeZone('Asia/Jakarta');

        $user = $this->where('username', $username)
                    ->where('is_active', 1)
                    ->first();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        // Jika koordinator, allow 24/7
        if ($user['role'] === 'koordinator') {
            return $user;
        }

        // Jika regu, cek jadwal shift
        $jadwalModel = new JadwalModel();
        $today = (new \DateTime('now', $tz))->format('Y-m-d');
        
        $jadwal = $jadwalModel->where('regu_number', $user['regu_number'])
                             ->where('tanggal', $today)
                             ->first();
        
        if (!$jadwal) {
            return ['error' => 'Tidak ada jadwal shift untuk hari ini'];
        }

        // Ambil waktu sekarang
        $now = new \DateTime('now', $tz);
        $current_timestamp = $now->getTimestamp();
        $current_time = $now->format('H:i:s');

        $shift_start = $jadwal['jam_mulai'];
        $shift_end   = $jadwal['jam_selesai'];

        // Buat objek DateTime untuk shift
        $shift_start_dt = new \DateTime($today . ' ' . $shift_start, $tz);
        $shift_end_dt   = new \DateTime($today . ' ' . $shift_end, $tz);

        // Izinkan login 15 menit sebelum shift
        $allowed_start_dt = clone $shift_start_dt;
        $allowed_start_dt->modify('-15 minutes');

        $shift_start_timestamp = $allowed_start_dt->getTimestamp();
        $shift_end_timestamp   = $shift_end_dt->getTimestamp();

        // Handle shift malam (melewati tengah malam, contoh 23:00 - 07:00)
        if ($shift_end_timestamp < $shift_start_timestamp) {
            if ($current_timestamp >= $shift_start_timestamp || $current_timestamp <= $shift_end_timestamp) {
                $user['jadwal'] = $jadwal;
                return $user;
            }
        } else {
            // Shift normal
            if ($current_timestamp >= $shift_start_timestamp && $current_timestamp <= $shift_end_timestamp) {
                $user['jadwal'] = $jadwal;
                return $user;
            }
        }

        // Kalau tidak sesuai waktu shift
        return [
            'error' => "Login tidak diizinkan. Shift: {$shift_start} - {$shift_end}. Anda dapat login mulai: " .
                      $allowed_start_dt->format('H:i') . ". Waktu sekarang: {$current_time}"
        ];
    }
}