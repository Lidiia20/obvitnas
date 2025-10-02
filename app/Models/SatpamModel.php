<?php

namespace App\Models;

use CodeIgniter\Model;

class SatpamModel extends Model
{
    protected $table = 'satpam';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama', 'username', 'password', 'regu_number', 'gi_id', 
        'is_koordinator', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllAvailableForReplacement($excludeReguNumber)
    {
        try {
            return $this->where('gi_id', 1)
                       ->where('is_koordinator', 0)
                       ->where('is_active', 1)
                       ->where('regu_number !=', $excludeReguNumber)
                       ->orderBy('regu_number', 'ASC')
                       ->orderBy('nama', 'ASC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getAllAvailableForReplacement error: ' . $e->getMessage());
            return [];
        }
    }

    public function getSatpamByRegu($reguNumber, $includeKoordinator = true)
    {
        try {
            $builder = $this->where('regu_number', $reguNumber)
                           ->where('gi_id', 1)
                           ->where('is_active', 1);

            if (!$includeKoordinator) {
                $builder->where('is_koordinator', 0);
            }

            return $builder->orderBy('is_koordinator', 'DESC')
                          ->orderBy('nama', 'ASC')
                          ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getSatpamByRegu error: ' . $e->getMessage());
            return [];
        }
    }

    public function getKoordinator()
    {
        try {
            return $this->where('is_koordinator', 1)
                       ->where('gi_id', 1)
                       ->where('is_active', 1)
                       ->first();
        } catch (\Exception $e) {
            log_message('error', 'getKoordinator error: ' . $e->getMessage());
            return null;
        }
    }
}