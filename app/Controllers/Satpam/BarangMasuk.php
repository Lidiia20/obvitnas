<?php

namespace App\Controllers\Satpam;

use App\Controllers\BaseController;
use App\Models\BarangMasukModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BarangMasuk extends BaseController
{
    protected $barangMasukModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
    }

    public function index()
    {
        helper(['form', 'url']);

        $q = $this->request->getGet('q');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        try {
            $today = date('Y-m-d');
            $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
            $thisMonthStart = date('Y-m-01');

            $total = $this->barangMasukModel->countAll();
            $todayCount = $this->barangMasukModel->where('tanggal', $today)->countAllResults();
            $thisWeekCount = $this->barangMasukModel->where('tanggal >=', $thisWeekStart)->countAllResults();
            $thisMonthCount = $this->barangMasukModel->where('tanggal >=', $thisMonthStart)->countAllResults();

            if ($q) {
                $barang = $this->barangMasukModel
                    ->like('nama_instansi', (string)$q)
                    ->orLike('nama_petugas', (string)$q)
                    ->orLike('keterangan_barang', (string)$q)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                $pager = $this->barangMasukModel->pager;
            } else {
                $barang = $this->barangMasukModel
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                $pager = $this->barangMasukModel->pager;
            }

            $data = [
                'title' => 'Data Barang Masuk - Sistem Informasi Obvitnas',
                'barangMasuk' => $barang,
                'q' => esc($q),
                'pager' => $pager,
                'total' => $total,
                'current_page' => 'barangmasuk',
                'stats' => [
                    'total' => $total,
                    'today' => $todayCount,
                    'thisWeek' => $thisWeekCount,
                    'thisMonth' => $thisMonthCount
                ]
            ];

            return view('satpam/barang_masuk/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat memuat index barang masuk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function create()
    {
        helper(['form', 'url']);

        $data = [
            'title' => 'Tambah Barang Masuk - Sistem Informasi Obvitnas',
            'validation' => null,
            'current_page' => 'barangmasuk',
        ];

        return view('satpam/barang_masuk/create', $data);
    }

    public function store()
    {
        helper(['form', 'url']);

        $rules = [
            'tanggal' => 'required|valid_date',
            'waktu' => 'required',
            'nama_instansi' => 'required|string|max_length[255]',
            'keterangan_barang' => 'required|string',
            'satpam_pemeriksa' => 'required|string|max_length[255]',
            'foto_barang' => 'permit_empty|mime_in[foto_barang,image/jpg,image/jpeg,image/png,image/gif]|max_size[foto_barang,4096]|is_image[foto_barang]',
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Barang Masuk - Sistem Informasi Obvitnas',
                'validation' => $this->validator,
                'current_page' => 'barangmasuk',
                'old_input' => $this->request->getPost()
            ];
            return view('satpam/barang_masuk/create', $data);
        }

        $file = $this->request->getFile('foto_barang');
        $fileName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $uploadPath = FCPATH . 'public/uploads/barang_masuk/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $fileName = $file->getRandomName();
                $file->move($uploadPath, $fileName);
                log_message('info', 'File diunggah: ' . $uploadPath . $fileName);
            } catch (\Exception $e) {
                log_message('error', 'Gagal mengunggah file: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        }

        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu' => $this->request->getPost('waktu'),
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'nama_petugas' => $this->request->getPost('nama_petugas'),
            'no_hp' => $this->request->getPost('no_hp'),
            'nama_pic_tujuan' => $this->request->getPost('nama_pic_tujuan'),
            'no_surat_pengantar' => $this->request->getPost('no_surat_pengantar'),
            'keterangan_barang' => $this->request->getPost('keterangan_barang'),
            'konfirmasi_nama_pic' => $this->request->getPost('konfirmasi_nama_pic'),
            'konfirmasi_jabatan' => $this->request->getPost('konfirmasi_jabatan'),
            'kesesuaian' => $this->request->getPost('kesesuaian'),
            'serah_nama' => $this->request->getPost('serah_nama'),
            'serah_jabatan' => $this->request->getPost('serah_jabatan'),
            'serah_tanggal' => $this->request->getPost('serah_tanggal'),
            'foto_barang' => $fileName,
            'satpam_pemeriksa' => $this->request->getPost('satpam_pemeriksa'),
        ];

        try {
            if ($this->barangMasukModel->save($data)) {
                return redirect()->to(base_url('satpam/barangmasuk'))->with('success', 'Data barang masuk berhasil disimpan.');
            } else {
                $errors = $this->barangMasukModel->errors();
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat menyimpan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit($id = null)
    {
        helper(['form', 'url']);

        $barang = $this->barangMasukModel->find($id);
        if (!$barang) {
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Barang Masuk - Sistem Informasi Obvitnas',
            'barang' => $barang,
            'validation' => null,
            'current_page' => 'barangmasuk',
        ];

        return view('satpam/barang_masuk/edit', $data);
    }

    public function update($id = null)
    {
        helper(['form', 'url']);

        $barang = $this->barangMasukModel->find($id);
        if (!$barang) {
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        $rules = [
            'tanggal' => 'required|valid_date',
            'waktu' => 'required',
            'nama_instansi' => 'required|string|max_length[255]',
            'keterangan_barang' => 'required|string',
            'satpam_pemeriksa' => 'required|string|max_length[255]',
            'foto_barang' => 'permit_empty|mime_in[foto_barang,image/jpg,image/jpeg,image/png,image/gif]|max_size[foto_barang,4096]|is_image[foto_barang]',
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Edit Barang Masuk - Sistem Informasi Obvitnas',
                'validation' => $this->validator,
                'barang' => $barang,
                'current_page' => 'barangmasuk',
            ];
            return view('satpam/barang_masuk/edit', $data);
        }

        $file = $this->request->getFile('foto_barang');
        $fileName = $barang['foto_barang'];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $uploadPath = FCPATH . 'public/uploads/barang_masuk/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                if ($barang['foto_barang'] && file_exists($uploadPath . $barang['foto_barang'])) {
                    unlink($uploadPath . $barang['foto_barang']);
                }
                $fileName = $file->getRandomName();
                $file->move($uploadPath, $fileName);
                log_message('info', 'File diperbarui: ' . $uploadPath . $fileName);
            } catch (\Exception $e) {
                log_message('error', 'Gagal mengunggah file: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        }

        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu' => $this->request->getPost('waktu'),
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'nama_petugas' => $this->request->getPost('nama_petugas'),
            'no_hp' => $this->request->getPost('no_hp'),
            'nama_pic_tujuan' => $this->request->getPost('nama_pic_tujuan'),
            'no_surat_pengantar' => $this->request->getPost('no_surat_pengantar'),
            'keterangan_barang' => $this->request->getPost('keterangan_barang'),
            'konfirmasi_nama_pic' => $this->request->getPost('konfirmasi_nama_pic'),
            'konfirmasi_jabatan' => $this->request->getPost('konfirmasi_jabatan'),
            'kesesuaian' => $this->request->getPost('kesesuaian'),
            'serah_nama' => $this->request->getPost('serah_nama'),
            'serah_jabatan' => $this->request->getPost('serah_jabatan'),
            'serah_tanggal' => $this->request->getPost('serah_tanggal'),
            'foto_barang' => $fileName,
            'satpam_pemeriksa' => $this->request->getPost('satpam_pemeriksa'),
        ];

        try {
            $this->barangMasukModel->update($id, $data);
            return redirect()->to(base_url('satpam/barangmasuk'))->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function view($id = null)
    {
        $barang = $this->barangMasukModel->find($id);
        if (!$barang) {
            throw new PageNotFoundException('Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Barang Masuk - Sistem Informasi Obvitnas',
            'barang' => $barang,
            'current_page' => 'barangmasuk',
        ];

        return view('satpam/barang_masuk/view', $data);
    }

    public function delete($id = null)
    {
        $barang = $this->barangMasukModel->find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        try {
            $uploadPath = FCPATH . 'public/uploads/barang_masuk/';
            if ($barang['foto_barang'] && file_exists($uploadPath . $barang['foto_barang'])) {
                unlink($uploadPath . $barang['foto_barang']);
            }

            $this->barangMasukModel->delete($id);
            return redirect()->to(base_url('satpam/barangmasuk'))->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    public function viewFile($filename)
    {
        if (!session()->get('isLoggedIn')) {
            throw new PageNotFoundException('Akses tidak diizinkan');
        }

        $filename = basename($filename);
        $filePath = FCPATH . 'public/uploads/barang_masuk/' . $filename;
        $realPath = realpath($filePath);
        $uploadRealPath = realpath(FCPATH . 'public/uploads/barang_masuk');

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