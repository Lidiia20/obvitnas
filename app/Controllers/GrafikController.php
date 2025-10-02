<?php

namespace App\Controllers;

use App\Models\KunjunganModel;
use CodeIgniter\Controller;

class GrafikController extends BaseController
{
    public function get()
    {
        $upt = $this->request->getGet('upt');
        $ultg = $this->request->getGet('ultg');
        $gi = $this->request->getGet('gi');

        $kunjunganModel = new KunjunganModel(); // Model untuk tabel kunjungan
        $builder = $kunjunganModel;

        if ($gi) {
            $builder = $builder->where('gi_id', $gi);
        } elseif ($ultg) {
            $builder = $builder->where('ultg_id', $ultg);
        } elseif ($upt) {
            $builder = $builder->where('upt_id', $upt);
        }

        // Harian (7 hari terakhir)
        $harian = $builder
            ->select("DATE(tanggal_kunjungan) as tgl, COUNT(*) as total")
            ->groupBy("DATE(tanggal_kunjungan)")
            ->orderBy("tgl", "DESC")
            ->limit(7)
            ->findAll();

        // Mingguan (by WEEK of YEAR)
        $mingguan = $builder
            ->select("WEEK(tanggal_kunjungan) as minggu, COUNT(*) as total")
            ->groupBy("minggu")
            ->orderBy("minggu", "DESC")
            ->limit(4)
            ->findAll();

        // Bulanan
        $bulanan = $builder
            ->select("MONTH(tanggal_kunjungan) as bulan, COUNT(*) as total")
            ->groupBy("bulan")
            ->orderBy("bulan", "ASC")
            ->findAll();

        return $this->response->setJSON([
            'harian' => [
                'labels' => array_column($harian, 'tgl'),
                'values' => array_column($harian, 'total'),
            ],
            'mingguan' => [
                'labels' => array_map(fn($m) => "Minggu ke-" . $m['minggu'], $mingguan),
                'values' => array_column($mingguan, 'total'),
            ],
            'bulanan' => [
                'labels' => array_map(fn($b) => date('M', mktime(0, 0, 0, $b['bulan'], 10)), $bulanan),
                'values' => array_column($bulanan, 'total'),
            ]
        ]);
    }
}
