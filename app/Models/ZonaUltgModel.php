<?php

namespace App\Models;

use CodeIgniter\Model;

class ZonaUltgModel extends Model
{
    protected $table = 'zona_ultg';
    protected $allowedFields = ['nama_ultg', 'upt_id'];
}
