<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminK3LModel extends Model
{
    protected $table = 'admin_k3l';
    protected $primaryKey = 'id';

    protected $useTimestamps = true; // Jika kamu pakai created_at
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Kosongkan jika tidak pakai updated_at

    protected $allowedFields = ['nama', 'email', 'password'];
}
