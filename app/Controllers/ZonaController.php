<?php

namespace App\Controllers;

use App\Models\ZonaUltgModel;
use App\Models\ZonaGiModel;

class ZonaController extends BaseController
{
    public function getUpt()
{
    $uptModel = new \App\Models\ZonaUptModel();
    $uptList = $uptModel->findAll();

    $output = '<option value="">Pilih UPT</option>';
    foreach ($uptList as $upt) {
        $output .= '<option value="' . $upt['id'] . '">' . $upt['nama_upt'] . '</option>';
    }

    return $this->response->setBody($output);
}

    public function getUltg($upt_id)
    {
        $ultgModel = new ZonaUltgModel();
        $ultgList = $ultgModel->where('upt_id', $upt_id)->findAll();

        $output = '<option value="">Pilih ULTG</option>';
        foreach ($ultgList as $ultg) {
            $output .= '<option value="' . $ultg['id'] . '">' . $ultg['nama_ultg'] . '</option>';
        }

        return $this->response->setBody($output);
    }

    public function getGi($ultg_id)
    {
        $giModel = new ZonaGiModel();
        $giList = $giModel->where('ultg_id', $ultg_id)->findAll();

        $output = '<option value="">Pilih GI</option>';
        foreach ($giList as $gi) {
            $output .= '<option value="' . $gi['id'] . '">' . $gi['nama_gi'] . '</option>';
        }

        return $this->response->setBody($output);
    }
}
