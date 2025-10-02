<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'tanggal',
        'waktu', 
        'nama_instansi',
        'nama_petugas',
        'no_hp',
        'nama_pic_tujuan',
        'no_surat_pengantar',
        'keterangan_barang',
        'konfirmasi_nama_pic',
        'konfirmasi_jabatan',
        'kesesuaian',
        'serah_nama',
        'serah_jabatan',
        'serah_tanggal',
        'foto_barang',
        'satpam_pemeriksa'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
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

    // Override save method untuk debugging
    public function save($data): bool
    {
        log_message('debug', '=== MODEL SAVE DEBUG ===');
        log_message('debug', 'Table: ' . $this->table);
        log_message('debug', 'Data received: ' . json_encode($data));
        log_message('debug', 'Allowed fields: ' . json_encode($this->allowedFields));
        
        // Cek apakah semua field yang diberikan diizinkan
        if (is_array($data)) {
            $invalidFields = array_diff(array_keys($data), $this->allowedFields);
            if (!empty($invalidFields)) {
                log_message('error', 'Invalid fields detected: ' . json_encode($invalidFields));
            }
        }

        try {
            $result = parent::save($data);
            log_message('debug', 'Parent save result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if (!$result) {
                log_message('error', 'Save failed. Model errors: ' . json_encode($this->errors()));
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception in model save: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Method untuk debugging database connection
    public function testConnection(): bool
    {
        try {
            $db = \Config\Database::connect();
            log_message('debug', 'Database connection test');
            
            // Test query
            $query = $db->query("SELECT 1 as test");
            $result = $query->getRow();
            
            log_message('debug', 'Connection test result: ' . json_encode($result));
            
            // Check if table exists
            if ($db->tableExists($this->table)) {
                log_message('debug', 'Table "' . $this->table . '" exists');
                
                // Get table structure
                $fields = $db->getFieldData($this->table);
                log_message('debug', 'Table structure: ' . json_encode($fields));
                
                return true;
            } else {
                log_message('error', 'Table "' . $this->table . '" does not exist');
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Database connection failed: ' . $e->getMessage());
            return false;
        }
    }

    // Method untuk cek struktur tabel
    public function checkTableStructure(): array
    {
        try {
            $db = \Config\Database::connect();
            $fields = $db->getFieldData($this->table);
            
            $fieldNames = [];
            foreach ($fields as $field) {
                $fieldNames[] = $field->name;
            }
            
            log_message('debug', 'Database fields: ' . json_encode($fieldNames));
            log_message('debug', 'Model allowed fields: ' . json_encode($this->allowedFields));
            
            // Check missing fields
            $missingInDb = array_diff($this->allowedFields, $fieldNames);
            $missingInModel = array_diff($fieldNames, $this->allowedFields);
            
            if (!empty($missingInDb)) {
                log_message('warning', 'Fields missing in database: ' . json_encode($missingInDb));
            }
            
            if (!empty($missingInModel)) {
                log_message('info', 'Fields missing in model (might be OK): ' . json_encode($missingInModel));
            }
            
            return [
                'db_fields' => $fieldNames,
                'model_fields' => $this->allowedFields,
                'missing_in_db' => $missingInDb,
                'missing_in_model' => $missingInModel
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error checking table structure: ' . $e->getMessage());
            return [];
        }
    }
}