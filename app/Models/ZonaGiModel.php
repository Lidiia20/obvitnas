<?php

namespace App\Models;

use CodeIgniter\Model;

class ZonaGiModel extends Model
{
    protected $table = 'zona_gi';
    protected $allowedFields = ['gi_nama', 'ultg_id'];
}


