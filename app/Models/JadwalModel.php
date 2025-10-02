<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table = 'jadwal_satpam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;  // Tambahkan ini jika ID auto-increment
    protected $returnType = 'array';    // Tentukan return type agar konsisten
    protected $useSoftDeletes = false; // Atur sesuai kebutuhan
    protected $allowedFields = [
        'nama_satpam', 'regu_number', 'tanggal', 'shift',
        'jam_mulai', 'jam_selesai', 'status', 'created_by', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [];      // Tambahkan aturan validasi jika perlu
    protected $validationMessages = [];
    protected $skipValidation = false;

    // Get jadwal aktif untuk hari ini
    public function getTodaySchedule()
    {
        $today = date('Y-m-d');
        $result = $this->where('tanggal', $today)->findAll();
        return $result ?? [];
    }

    // Get jadwal untuk regu tertentu
    public function getReguSchedule(int $regu_number, string $tanggal = null): ?array
    {
        $tanggal = $tanggal ?? date('Y-m-d');

        return $this->where('regu_number', $regu_number)
            ->where('tanggal', $tanggal)
            ->first();
    }

    // Convert shift letter ke jam
    public function getShiftTimes(string $shift): array
    {
        switch ($shift) {
            case 'P': // Pagi
                return ['08:00:00', '16:00:00'];
            case 'S': // Siang
                return ['16:00:00', '00:00:00'];
            case 'M': // Malam
                return ['00:00:00', '08:00:00'];
            default:
                return ['08:00:00', '16:00:00'];
        }
    }

    // Save jadwal dengan auto-set jam
    public function saveWithShiftTimes(array $data)
    {
        if (isset($data['shift'])) {
            list($jam_mulai, $jam_selesai) = $this->getShiftTimes($data['shift']);
            $data['jam_mulai'] = $jam_mulai;
            $data['jam_selesai'] = $jam_selesai;
        }

        $this->db->transBegin();  // Mulai transaksi

        try {
            $this->insert($data); // Ubah ke insert
            $id = $this->insertID(); // Ambil ID setelah insert

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return false; // Atau lempar Exception
            } else {
                $this->db->transCommit();
                return $id; // Kembalikan ID yang baru di-insert
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', $e->getMessage()); // Log pesan kesalahan
            return false; // Atau lempar Exception
        }
    }
}
