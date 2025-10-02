<?php

namespace App\Controllers\Satpam;

use App\Controllers\BaseController;
use App\Models\BarangKeluarModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BarangKeluar extends BaseController
{
    protected $barangKeluarModel;

    public function __construct()
    {
        $this->barangKeluarModel = new BarangKeluarModel();
    }

    public function index()
    {
        helper(['form', 'url']);

        $search = $this->request->getGet('search') ?? $this->request->getGet('q');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        try {
            $barang = $this->barangKeluarModel->getWithSearch($search, $perPage, ($page - 1) * $perPage);
            $total = $this->barangKeluarModel->countWithSearch($search);
            $statistics = $this->barangKeluarModel->getStatistics();

            $pager = \Config\Services::pager();

            $data = [
                'title' => 'Data Barang Keluar - Sistem Informasi Obvitnas',
                'barangKeluar' => $barang,
                'search' => esc($search),
                'pager' => $pager,
                'total' => $total,
                'statistics' => $statistics,
                'current_page' => $page,
                'page_title' => 'Data Barang Keluar',
            ];

            return view('satpam/barang_keluar/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat memuat index barang keluar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function create()
    {
        helper(['form', 'url']);

        $data = [
            'title' => 'Tambah Barang Keluar - Sistem Informasi Obvitnas',
            'page_title' => 'Tambah Barang Keluar',
            'validation' => null,
        ];
        return view('satpam/barang_keluar/create', $data);
    }

    public function store()
    {
        helper(['form', 'url']);

        $rules = [
            'tanggal' => 'required|valid_date',
            'waktu' => 'required',
            'nama_instansi' => 'required|string|max_length[255]',
            'nama_petugas' => 'permit_empty|string|max_length[255]',
            'no_hp' => 'permit_empty|string|max_length[20]',
            'pemilik_barang' => 'required|string|max_length[255]',
            'pejabat_penerbit_surat' => 'permit_empty|string|max_length[255]',
            'no_surat' => 'permit_empty|string|max_length[255]',
            'keterangan_barang' => 'required|string',
            'foto_surat_jalan' => 'uploaded[foto_surat_jalan]|mime_in[foto_surat_jalan,image/jpg,image/jpeg,image/gif,image/png]|max_size[foto_surat_jalan,4096]|is_image[foto_surat_jalan]',
            'foto_barang' => 'uploaded[foto_barang]|mime_in[foto_barang,image/jpg,image/jpeg,image/gif,image/png]|max_size[foto_barang,4096]|is_image[foto_barang]',
            'satpam_pemeriksa' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Barang Keluar - Sistem Informasi Obvitnas',
                'page_title' => 'Tambah Barang Keluar',
                'validation' => $this->validator,
                'old_input' => $this->request->getPost()
            ];
            return view('satpam/barang_keluar/create', $data);
        }

        try {
            $fileSurat = $this->request->getFile('foto_surat_jalan');
            $fileBarang = $this->request->getFile('foto_barang');
            $fileSuratName = null;
            $fileBarangName = null;

            $uploadPath = FCPATH . 'public/uploads/barang_keluar/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
                $fileSuratName = $fileSurat->getRandomName();
                $fileSurat->move($uploadPath, $fileSuratName);
                log_message('info', 'Foto surat jalan diunggah: ' . $uploadPath . $fileSuratName);
            }

            if ($fileBarang && $fileBarang->isValid() && !$fileBarang->hasMoved()) {
                $fileBarangName = $fileBarang->getRandomName();
                $fileBarang->move($uploadPath, $fileBarangName);
                log_message('info', 'Foto barang diunggah: ' . $uploadPath . $fileBarangName);
            }

            $data = [
                'tanggal' => $this->request->getPost('tanggal'),
                'waktu' => $this->request->getPost('waktu'),
                'nama_instansi' => $this->request->getPost('nama_instansi'),
                'nama_petugas' => $this->request->getPost('nama_petugas'),
                'no_hp' => $this->request->getPost('no_hp'),
                'pemilik_barang' => $this->request->getPost('pemilik_barang'),
                'pejabat_penerbit_surat' => $this->request->getPost('pejabat_penerbit_surat'),
                'no_surat' => $this->request->getPost('no_surat'),
                'keterangan_barang' => $this->request->getPost('keterangan_barang'),
                'foto_surat_jalan' => $fileSuratName,
                'foto_barang' => $fileBarangName,
                'satpam_pemeriksa' => $this->request->getPost('satpam_pemeriksa'),
            ];

            if ($this->barangKeluarModel->insert($data)) {
                return redirect()->to(route_to('barang_keluar_index'))->with('success', 'Data barang keluar berhasil disimpan!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data barang keluar.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat menyimpan data barang keluar: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id = null)
    {
        helper(['form', 'url']);

        $barang = $this->barangKeluarModel->find($id);
        if (!$barang) {
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Barang Keluar - Sistem Informasi Obvitnas',
            'page_title' => 'Edit Barang Keluar',
            'barang' => $barang,
            'validation' => null,
        ];

        return view('satpam/barang_keluar/edit', $data);
    }

    public function update($id = null)
    {
        helper(['form', 'url']);

        $barang = $this->barangKeluarModel->find($id);
        if (!$barang) {
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        $rules = [
            'tanggal' => 'required|valid_date',
            'waktu' => 'required',
            'nama_instansi' => 'required|string|max_length[255]',
            'nama_petugas' => 'permit_empty|string|max_length[255]',
            'no_hp' => 'permit_empty|string|max_length[20]',
            'pemilik_barang' => 'required|string|max_length[255]',
            'pejabat_penerbit_surat' => 'permit_empty|string|max_length[255]',
            'no_surat' => 'permit_empty|string|max_length[255]',
            'keterangan_barang' => 'required|string',
            'foto_surat_jalan' => 'permit_empty|mime_in[foto_surat_jalan,image/jpg,image/jpeg,image/gif,image/png]|max_size[foto_surat_jalan,4096]|is_image[foto_surat_jalan]',
            'foto_barang' => 'permit_empty|mime_in[foto_barang,image/jpg,image/jpeg,image/gif,image/png]|max_size[foto_barang,4096]|is_image[foto_barang]',
            'satpam_pemeriksa' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Edit Barang Keluar - Sistem Informasi Obvitnas',
                'page_title' => 'Edit Barang Keluar',
                'validation' => $this->validator,
                'barang' => $barang,
            ];
            return view('satpam/barang_keluar/edit', $data);
        }

        try {
            $fileSurat = $this->request->getFile('foto_surat_jalan');
            $fileBarang = $this->request->getFile('foto_barang');
            $fileSuratName = $barang['foto_surat_jalan'];
            $fileBarangName = $barang['foto_barang'];

            $uploadPath = FCPATH . 'public/uploads/barang_keluar/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
                if ($barang['foto_surat_jalan'] && file_exists($uploadPath . $barang['foto_surat_jalan'])) {
                    unlink($uploadPath . $barang['foto_surat_jalan']);
                }
                $fileSuratName = $fileSurat->getRandomName();
                $fileSurat->move($uploadPath, $fileSuratName);
                log_message('info', 'Foto surat jalan diperbarui: ' . $uploadPath . $fileSuratName);
            }

            if ($fileBarang && $fileBarang->isValid() && !$fileBarang->hasMoved()) {
                if ($barang['foto_barang'] && file_exists($uploadPath . $barang['foto_barang'])) {
                    unlink($uploadPath . $barang['foto_barang']);
                }
                $fileBarangName = $fileBarang->getRandomName();
                $fileBarang->move($uploadPath, $fileBarangName);
                log_message('info', 'Foto barang diperbarui: ' . $uploadPath . $fileBarangName);
            }

            $data = [
                'tanggal' => $this->request->getPost('tanggal'),
                'waktu' => $this->request->getPost('waktu'),
                'nama_instansi' => $this->request->getPost('nama_instansi'),
                'nama_petugas' => $this->request->getPost('nama_petugas'),
                'no_hp' => $this->request->getPost('no_hp'),
                'pemilik_barang' => $this->request->getPost('pemilik_barang'),
                'pejabat_penerbit_surat' => $this->request->getPost('pejabat_penerbit_surat'),
                'no_surat' => $this->request->getPost('no_surat'),
                'keterangan_barang' => $this->request->getPost('keterangan_barang'),
                'foto_surat_jalan' => $fileSuratName,
                'foto_barang' => $fileBarangName,
                'satpam_pemeriksa' => $this->request->getPost('satpam_pemeriksa'),
            ];

            if ($this->barangKeluarModel->update($id, $data)) {
                return redirect()->to(route_to('barang_keluar_index'))->with('success', 'Data barang keluar berhasil diperbarui!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data barang keluar.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat memperbarui data barang keluar: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function view($id = null)
    {
        $barang = $this->barangKeluarModel->find($id);
        if (!$barang) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)
                    ->setBody('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Data tidak ditemukan</div>');
            }
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        if ($this->request->isAJAX()) {
            $html = '
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-info-circle me-2"></i>Informasi Umum
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Tanggal</strong></td>
                                        <td>' . date('d/m/Y', strtotime($barang['tanggal'])) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Waktu</strong></td>
                                        <td>' . date('H:i', strtotime($barang['waktu'])) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Instansi</strong></td>
                                        <td>' . esc($barang['nama_instansi']) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Petugas</strong></td>
                                        <td>' . esc($barang['nama_petugas'] ?: '-') . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP</strong></td>
                                        <td>' . esc($barang['no_hp'] ?: '-') . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-box me-2"></i>Informasi Barang
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Pemilik Barang</strong></td>
                                        <td>' . esc($barang['pemilik_barang']) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pejabat Penerbit</strong></td>
                                        <td>' . esc($barang['pejabat_penerbit_surat'] ?: '-') . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Surat</strong></td>
                                        <td>' . esc($barang['no_surat'] ?: '-') . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Satpam Pemeriksa</strong></td>
                                        <td>' . esc($barang['satpam_pemeriksa']) . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-sticky-note me-2"></i>Keterangan Barang
                            </div>
                            <div class="card-body">
                                <p class="mb-0">' . nl2br(esc($barang['keterangan_barang'])) . '</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <i class="fas fa-camera me-2"></i>Dokumentasi Foto
                            </div>
                            <div class="card-body">
                                <div class="row g-3">';
            if ($barang['foto_surat_jalan']) {
                $html .= '
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Foto Surat Jalan:</h6>
                                        <img src="' . base_url('public/uploads/barang_keluar/' . $barang['foto_surat_jalan']) . '" 
                                             class="img-fluid rounded shadow-sm" 
                                             alt="Foto Surat Jalan"
                                             style="max-height: 300px; object-fit: contain;">
                                    </div>';
            }
            if ($barang['foto_barang']) {
                $html .= '
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Foto Barang:</h6>
                                        <img src="' . base_url('public/uploads/barang_keluar/' . $barang['foto_barang']) . '" 
                                             class="img-fluid rounded shadow-sm" 
                                             alt="Foto Barang"
                                             style="max-height: 300px; object-fit: contain;">
                                    </div>';
            }
            if (!$barang['foto_surat_jalan'] && !$barang['foto_barang']) {
                $html .= '
                                    <div class="col-12">
                                        <p class="text-muted text-center mb-0">
                                            <i class="fas fa-image fa-2x mb-2"></i><br>
                                            Tidak ada foto yang tersedia
                                        </p>
                                    </div>';
            }
            $html .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            return $this->response->setBody($html);
        }

        $data = [
            'title' => 'Detail Barang Keluar - Sistem Informasi Obvitnas',
            'page_title' => 'Detail Barang Keluar',
            'barang' => $barang,
        ];

        return view('satpam/barang_keluar/view', $data);
    }

    public function delete($id = null)
    {
        $barang = $this->barangKeluarModel->find($id);
        if (!$barang) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        try {
            $uploadPath = FCPATH . 'public/uploads/barang_keluar/';
            if ($barang['foto_surat_jalan'] && file_exists($uploadPath . $barang['foto_surat_jalan'])) {
                unlink($uploadPath . $barang['foto_surat_jalan']);
            }
            if ($barang['foto_barang'] && file_exists($uploadPath . $barang['foto_barang'])) {
                unlink($uploadPath . $barang['foto_barang']);
            }

            $this->barangKeluarModel->delete($id);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus']);
            }
            return redirect()->to(route_to('barang_keluar_index'))->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat menghapus data barang keluar: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data']);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    public function export($format = 'excel')
    {
        $barangKeluar = $this->barangKeluarModel->orderBy('created_at', 'DESC')->findAll();

        if ($format === 'excel') {
            return $this->exportToExcel($barangKeluar);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($barangKeluar);
        }

        return redirect()->back()->with('error', 'Format ekspor tidak valid.');
    }

    private function exportToExcel($data)
    {
        $filename = 'barang_keluar_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'No', 'Tanggal', 'Waktu', 'Nama Instansi', 'Nama Petugas',
            'No HP', 'Pemilik Barang', 'Pejabat Penerbit Surat', 'No Surat',
            'Keterangan Barang', 'Satpam Pemeriksa'
        ]);

        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                $no++,
                $row['tanggal'],
                $row['waktu'],
                $row['nama_instansi'],
                $row['nama_petugas'],
                $row['no_hp'],
                $row['pemilik_barang'],
                $row['pejabat_penerbit_surat'],
                $row['no_surat'],
                $row['keterangan_barang'],
                $row['satpam_pemeriksa']
            ]);
        }

        fclose($output);
        exit;
    }

    private function exportToPdf($data)
    {
        $html = '
        <h2 style="text-align: center;">Data Barang Keluar</h2>
        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; font-size: 12px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Instansi</th>
                    <th>Pemilik Barang</th>
                    <th>No Surat</th>
                    <th>Satpam Pemeriksa</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($data as $row) {
            $html .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $row['tanggal'] . '</td>
                    <td>' . $row['nama_instansi'] . '</td>
                    <td>' . $row['pemilik_barang'] . '</td>
                    <td>' . $row['no_surat'] . '</td>
                    <td>' . $row['satpam_pemeriksa'] . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="barang_keluar_' . date('Y-m-d_H-i-s') . '.pdf"');
        echo $html;
        exit;
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) {
            throw new PageNotFoundException();
        }

        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 10;
        $offset = $this->request->getGet('offset') ?? 0;

        $data = $this->barangKeluarModel->getWithSearch($search, $limit, $offset);
        $total = $this->barangKeluarModel->countWithSearch($search);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public function viewFile($filename)
    {
        if (!session()->get('isLoggedIn')) {
            throw new PageNotFoundException('Akses tidak diizinkan');
        }

        $filename = basename($filename);
        $filePath = FCPATH . 'public/uploads/barang_keluar/' . $filename;
        $realPath = realpath($filePath);
        $uploadRealPath = realpath(FCPATH . 'public/uploads/barang_keluar');

        if (!$realPath || !$uploadRealPath || strpos($realPath, $uploadRealPath) !== 0) {
            throw new PageNotFoundException('File tidak ditemukan');
        }

        if (!file_exists($filePath)) {
            throw new PageNotFoundException('File tidak ditemukan');
        }

        $mimeType = mime_content_type($filePath);
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));

        if (strpos($mimeType, 'image/') === 0) {
            $this->response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        } else {
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        readfile($filePath);
        exit;
    }
}