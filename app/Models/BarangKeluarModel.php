<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tanggal', 'waktu', 'nama_instansi', 'nama_petugas', 'no_hp',
        'pemilik_barang', 'pejabat_penerbit_surat', 'no_surat', 'keterangan_barang',
        'foto_surat_jalan', 'foto_barang', 'satpam_pemeriksa'
    ];
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get data with search functionality
    public function getWithSearch($search = null, $limit = 10, $offset = 0)
    {
        $builder = $this->builder();
        
        if ($search) {
            $builder->groupStart()
                    ->like('nama_instansi', $search)
                    ->orLike('nama_petugas', $search)
                    ->orLike('pemilik_barang', $search)
                    ->orLike('keterangan_barang', $search)
                    ->orLike('satpam_pemeriksa', $search)
                    ->groupEnd();
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    // Count data with search
    public function countWithSearch($search = null)
    {
        $builder = $this->builder();
        
        if ($search) {
            $builder->groupStart()
                    ->like('nama_instansi', $search)
                    ->orLike('nama_petugas', $search)
                    ->orLike('pemilik_barang', $search)
                    ->orLike('keterangan_barang', $search)
                    ->orLike('satpam_pemeriksa', $search)
                    ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    // Get statistics
    public function getStatistics()
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        $thisYear = date('Y');
        
        $stats = [
            'total_all' => $this->countAll(),
            'today' => $this->where('tanggal', $today)->countAllResults(false),
            'this_month' => $this->like('tanggal', $thisMonth, 'after')->countAllResults(false),
            'this_year' => $this->like('tanggal', $thisYear, 'after')->countAllResults(false),
        ];
        
        return $stats;
    }

    // Custom insert with file handling
    public function insertWithFile($data, $fileSurat = null, $fileBarang = null)
    {
        $uploadPath = WRITEPATH . 'uploads/barang_keluar';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Handle surat jalan upload
        if ($fileSurat && $fileSurat->isValid()) {
            $fileName = $fileSurat->getRandomName();
            if ($fileSurat->move($uploadPath, $fileName)) {
                $data['foto_surat_jalan'] = $fileName;
            }
        }
        
        // Handle foto barang upload
        if ($fileBarang && $fileBarang->isValid()) {
            $fileName = $fileBarang->getRandomName();
            if ($fileBarang->move($uploadPath, $fileName)) {
                $data['foto_barang'] = $fileName;
            }
        }
        
        return $this->insert($data);
    }

    // Custom update with file handling
    public function updateWithFile($id, $data, $fileSurat = null, $fileBarang = null)
    {
        $existing = $this->find($id);
        if (!$existing) {
            return false;
        }
        
        $uploadPath = WRITEPATH . 'uploads/barang_keluar';
        
        // Handle new surat jalan upload
        if ($fileSurat && $fileSurat->isValid()) {
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Remove old file if exists
            if ($existing['foto_surat_jalan']) {
                $oldFile = $uploadPath . '/' . $existing['foto_surat_jalan'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            $fileName = $fileSurat->getRandomName();
            if ($fileSurat->move($uploadPath, $fileName)) {
                $data['foto_surat_jalan'] = $fileName;
            }
        }
        
        // Handle new foto barang upload
        if ($fileBarang && $fileBarang->isValid()) {
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Remove old file if exists
            if ($existing['foto_barang']) {
                $oldFile = $uploadPath . '/' . $existing['foto_barang'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            $fileName = $fileBarang->getRandomName();
            if ($fileBarang->move($uploadPath, $fileName)) {
                $data['foto_barang'] = $fileName;
            }
        }
        
        return $this->update($id, $data);
    }

    // Delete with file cleanup
    public function deleteWithFile($id)
    {
        $existing = $this->find($id);
        if (!$existing) {
            return false;
        }
        
        $uploadPath = WRITEPATH . 'uploads/barang_keluar/';
        
        // Delete associated files
        if ($existing['foto_surat_jalan'] && file_exists($uploadPath . $existing['foto_surat_jalan'])) {
            unlink($uploadPath . $existing['foto_surat_jalan']);
        }
        
        if ($existing['foto_barang'] && file_exists($uploadPath . $existing['foto_barang'])) {
            unlink($uploadPath . $existing['foto_barang']);
        }
        
        return $this->delete($id);
    }
}