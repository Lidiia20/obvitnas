<?php
namespace App\Models;

use CodeIgniter\Model;

class KunjunganModel extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'gi_id', 'no_kendaraan', 'keperluan', 'undangan', 'status',
        'warna_kartu_visitor', 'nomor_kartu_visitor', 'nama_satpam_checkin',
        'nama_satpam_checkout', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;

    public function getKunjunganByStatus($gi_id, $status)
    {
        try {
            // Use raw SQL to avoid query builder issues
            $db = \Config\Database::connect();
            
            // First check if zona_gi table exists
            $checkQuery = $db->query("SHOW TABLES LIKE 'zona_gi'");
            
            if ($checkQuery->getNumRows() == 0) {
                // If zona_gi doesn't exist, return data without gi_nama
                $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan, NULL as gi_nama, kunjungan.undangan 
                        FROM kunjungan 
                        LEFT JOIN users ON users.id = kunjungan.user_id 
                        WHERE kunjungan.gi_id = ? AND status = ?";
            } else {
                // Normal query with zona_gi join
                $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan, zona_gi.nama_gi as gi_nama, kunjungan.undangan 
                        FROM kunjungan 
                        LEFT JOIN users ON users.id = kunjungan.user_id 
                        LEFT JOIN zona_gi ON zona_gi.id = kunjungan.gi_id 
                        WHERE kunjungan.gi_id = ? AND status = ?";
            }
            
            $result = $db->query($sql, [$gi_id, $status]);
            return $result->getResultArray();
                        
        } catch (\Exception $e) {
            // Log error and return empty array
            log_message('error', 'KunjunganModel::getKunjunganByStatus error: ' . $e->getMessage());
            return [];
        }
    }

    public function getKunjunganByStatusSimple($gi_id, $status)
    {
        // Use raw SQL to avoid query builder issues
        $db = \Config\Database::connect();
        
        $sql = "SELECT kunjungan.*, users.nama as nama_tamu, users.no_kendaraan, users.keperluan, kunjungan.undangan 
                FROM kunjungan 
                LEFT JOIN users ON users.id = kunjungan.user_id 
                WHERE kunjungan.gi_id = ? AND status = ?";
        
        $result = $db->query($sql, [$gi_id, $status]);
        return $result->getResultArray();
    }

    public function insertKunjungan($user_id, $no_kendaraan, $keperluan, $undangan = null)
    {
        return $this->insert([
            'user_id'       => $user_id,
            'gi_id'         => 1, // Fokus hanya di GITET New Ujung Berung
            'no_kendaraan'  => $no_kendaraan,
            'keperluan'     => $keperluan,
            'undangan'      => $undangan,
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s')
        ]);
    }

    public function getDailyKunjungan($upt_id = null, $ultg_id = null, $gi_id = null)
    {
        $builder = $this->db->table('kunjungan')
            ->select("DATE(kunjungan.created_at) as tanggal, COUNT(*) as total")
            ->join('zona_gi', 'zona_gi.id = kunjungan.gi_id', 'left')
            ->join('zona_ultg', 'zona_ultg.id = zona_gi.ultg_id', 'left')
            ->join('zona_upt', 'zona_upt.id = zona_ultg.upt_id', 'left');

        if ($upt_id) $builder->where('zona_upt.id', $upt_id);
        if ($ultg_id) $builder->where('zona_ultg.id', $ultg_id);
        if ($gi_id) $builder->where('zona_gi.id', $gi_id);

        return $builder->groupBy('tanggal')
                       ->orderBy('tanggal', 'ASC')
                       ->get()
                       ->getResult();
    }

    public function getWeeklyKunjungan($upt_id = null, $ultg_id = null, $gi_id = null)
    {
        $builder = $this->db->table('kunjungan')
            ->select("YEARWEEK(kunjungan.created_at, 1) as minggu_ke, COUNT(*) as total")
            ->join('zona_gi', 'zona_gi.id = kunjungan.gi_id', 'left')
            ->join('zona_ultg', 'zona_ultg.id = zona_gi.ultg_id', 'left')
            ->join('zona_upt', 'zona_upt.id = zona_ultg.upt_id', 'left');

        if ($upt_id) $builder->where('zona_upt.id', $upt_id);
        if ($ultg_id) $builder->where('zona_ultg.id', $ultg_id);
        if ($gi_id) $builder->where('zona_gi.id', $gi_id);

        return $builder->groupBy('minggu_ke')
                       ->orderBy('minggu_ke', 'ASC')
                       ->get()
                       ->getResult();
    }

    public function getMonthlyKunjungan($upt_id = null, $ultg_id = null, $gi_id = null)
    {
        $builder = $this->db->table('kunjungan')
            ->select("DATE_FORMAT(kunjungan.created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->join('zona_gi', 'zona_gi.id = kunjungan.gi_id', 'left')
            ->join('zona_ultg', 'zona_ultg.id = zona_gi.ultg_id', 'left')
            ->join('zona_upt', 'zona_upt.id = zona_ultg.upt_id', 'left');

        if ($upt_id) $builder->where('zona_upt.id', $upt_id);
        if ($ultg_id) $builder->where('zona_ultg.id', $ultg_id);
        if ($gi_id) $builder->where('zona_gi.id', $gi_id);

        return $builder->groupBy('bulan')
                       ->orderBy('bulan', 'ASC')
                       ->get()
                       ->getResult();
    }

    public function getUserPhoneByKunjunganId($id)
    {
        return $this->select('users.no_hp')
                    ->join('users', 'users.id = kunjungan.user_id')
                    ->where('kunjungan.id', $id)
                    ->first();
    }

    public function saveRiwayatKunjungan($user_id, $no_kendaraan, $keperluan, $detail_keperluan)
    {
        // TODO: Create RiwayatKunjunganModel if needed
        // $riwayatModel = new \App\Models\RiwayatKunjunganModel();
        
        // For now, we can save to kunjungan table or create a simple log
        log_message('info', "Riwayat kunjungan saved for user_id: $user_id");
        
        // Uncomment below when RiwayatKunjunganModel is created
        /*
        $riwayatModel->insert([
            'user_id'          => $user_id,
            'no_kendaraan'     => $no_kendaraan,
            'keperluan'        => $keperluan,
            'detail_keperluan' => $detail_keperluan,
            'waktu_kunjungan'  => date('Y-m-d H:i:s'),
        ]);
        */
    }
    public function getHistoryKunjungan($gi_id, $filters = [])
    {
        $builder = $this->builder();
        $builder->join('users', 'users.id = kunjungan.user_id');
        $builder->where('kunjungan.gi_id', $gi_id);

        if (!empty($filters['nama'])) {
            $builder->like('users.nama', $filters['nama']);
        }
        if (!empty($filters['keperluan'])) {
            $builder->like('kunjungan.keperluan', $filters['keperluan']);
        }
        if (!empty($filters['tanggal'])) {
            $builder->where('DATE(kunjungan.created_at)', $filters['tanggal']);
        }

        return $builder->get()->getResult();
    }
    public function filter($filters = [])
{
    if (!empty($filters['nama'])) {
        $this->like('users.nama', $filters['nama']);
    }
    
    if (!empty($filters['keperluan'])) {
        $this->like('kunjungan.keperluan', $filters['keperluan']);
    }
    
    if (!empty($filters['tanggal'])) {
        $this->where('DATE(kunjungan.created_at)', $filters['tanggal']);
    }

    return $this;
}

}

