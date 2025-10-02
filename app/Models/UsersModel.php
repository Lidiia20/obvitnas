<?php
namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nama', 'asal_unit', 'asal_unit_lain', 'alamat', 'no_hp', 'email', 'password',
        'foto_identitas', 'foto_selfie', 'no_kendaraan', 'keperluan', 'detail_keperluan', 'file_undangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [
        'nama' => 'required',
        'asal_unit' => 'required',
        'alamat' => 'required',
        'no_hp' => 'required',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required',
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'asal_unit' => [
            'required' => 'Asal unit harus diisi',
            'max_length' => 'Asal unit maksimal 100 karakter'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all users with additional info
     */
    public function getAllUsersWithInfo()
    {
        return $this->select('
                id, nama, asal_unit, asal_unit_lain, alamat, no_hp, 
                email, no_kendaraan, keperluan, detail_keperluan, 
                foto_identitas, foto_selfie, created_at, updated_at
            ')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get users with date range filter
     */
    public function getUsersByDateRange($startDate, $endDate)
    {
        return $this->where('created_at >=', $startDate . ' 00:00:00')
                   ->where('created_at <=', $endDate . ' 23:59:59')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get users by unit
     */
    public function getUsersByUnit($unit)
    {
        return $this->where('asal_unit', $unit)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get users with filters
     */
    public function getUsersWithFilters($filters = [])
    {
        $builder = $this->builder();

        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        // Unit filter
        if (!empty($filters['unit'])) {
            $builder->where('asal_unit', $filters['unit']);
        }

        // Search filter
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('nama', $filters['search'])
                   ->orLike('email', $filters['search'])
                   ->orLike('asal_unit', $filters['search'])
                   ->orLike('asal_unit_lain', $filters['search'])
                   ->groupEnd();
        }

        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Get distinct units for filter dropdown
     */
    public function getDistinctUnits()
    {
        return $this->distinct()
                   ->select('asal_unit')
                   ->where('asal_unit IS NOT NULL')
                   ->where('asal_unit !=', '')
                   ->orderBy('asal_unit', 'ASC')
                   ->findColumn('asal_unit');
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $totalUsers = $this->countAll();
        $todayUsers = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults(false);
        $thisMonthUsers = $this->where('MONTH(created_at)', date('m'))
                               ->where('YEAR(created_at)', date('Y'))
                               ->countAllResults(false);

        return [
            'total' => $totalUsers,
            'today' => $todayUsers,
            'this_month' => $thisMonthUsers
        ];
    }

    /**
     * Get recent registrations
     */
    public function getRecentRegistrations($limit = 10)
    {
        return $this->select('id, nama, email, asal_unit, created_at')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Search users
     */
    public function searchUsers($keyword)
    {
        return $this->groupStart()
                   ->like('nama', $keyword)
                   ->orLike('email', $keyword)
                   ->orLike('asal_unit', $keyword)
                   ->orLike('no_hp', $keyword)
                   ->groupEnd()
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Update user data
     */
    public function updateUser($id, $data)
    {
        // Add updated_at timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($id, $data);
    }

    /**
     * Delete user and associated files
     */
    public function deleteUserComplete($id)
    {
        // Get user data first
        $user = $this->find($id);
        
        if (!$user) {
            return false;
        }

        // Delete associated files
        $this->deleteUserFiles($user);

        // Delete user record
        return $this->delete($id);
    }

    /**
     * Delete user files
     */
    private function deleteUserFiles($user)
    {
        // Delete foto identitas
        if (!empty($user['foto_identitas'])) {
            $identitasPath = FCPATH . 'uploads/identitas/' . $user['foto_identitas'];
            if (file_exists($identitasPath)) {
                unlink($identitasPath);
            }
        }

        // Delete foto selfie
        if (!empty($user['foto_selfie'])) {
            $selfiePath = FCPATH . 'uploads/selfie/' . $user['foto_selfie'];
            if (file_exists($selfiePath)) {
                unlink($selfiePath);
            }
        }
    }

    /**
     * Get units with user count
     */
    public function getUnitsWithCount()
    {
        return $this->select('asal_unit, COUNT(*) as user_count')
                   ->where('asal_unit IS NOT NULL')
                   ->where('asal_unit !=', '')
                   ->groupBy('asal_unit')
                   ->orderBy('user_count', 'DESC')
                   ->findAll();
    }

    /**
     * Get registration stats by date
     */
    public function getRegistrationStatsByDate($days = 30)
    {
        return $this->select('DATE(created_at) as date, COUNT(*) as count')
                   ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
                   ->groupBy('DATE(created_at)')
                   ->orderBy('date', 'ASC')
                   ->findAll();
    }
}