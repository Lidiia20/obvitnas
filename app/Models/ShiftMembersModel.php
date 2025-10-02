<?php

namespace App\Models;

use CodeIgniter\Model;

class ShiftMembersModel extends Model
{
    protected $table = 'shift_members';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jadwal_id', 'satpam_id', 'is_available', 'replacement_for', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getShiftMembersWithNames($jadwalId)
    {
        try {
            return $this->select('shift_members.*, satpam.nama, satpam.regu_number, satpam.is_koordinator')
                       ->join('satpam', 'satpam.id = shift_members.satpam_id')
                       ->where('shift_members.jadwal_id', $jadwalId)
                       ->orderBy('satpam.is_koordinator', 'DESC')
                       ->orderBy('shift_members.is_available', 'DESC')
                       ->orderBy('satpam.nama', 'ASC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getShiftMembersWithNames error: ' . $e->getMessage());
            return [];
        }
    }

    public function getAvailableMembers($jadwalId)
    {
        try {
            return $this->select('shift_members.*, satpam.nama, satpam.regu_number')
                       ->join('satpam', 'satpam.id = shift_members.satpam_id')
                       ->where('shift_members.jadwal_id', $jadwalId)
                       ->where('shift_members.is_available', 1)
                       ->orderBy('satpam.nama', 'ASC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getAvailableMembers error: ' . $e->getMessage());
            return [];
        }
    }

    public function getUnavailableMembers($jadwalId)
    {
        try {
            return $this->select('shift_members.*, satpam.nama, satpam.regu_number')
                       ->join('satpam', 'satpam.id = shift_members.satpam_id')
                       ->where('shift_members.jadwal_id', $jadwalId)
                       ->where('shift_members.is_available', 0)
                       ->orderBy('satpam.nama', 'ASC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getUnavailableMembers error: ' . $e->getMessage());
            return [];
        }
    }
}