<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\SatpamModel;
use App\Models\ShiftMembersModel;
use CodeIgniter\Controller;

class KoordinatorSatpam extends BaseController
{
    protected $jadwalModel;
    protected $satpamModel;
    protected $shiftMembersModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->satpamModel = new SatpamModel();
        $this->shiftMembersModel = new ShiftMembersModel();
    }

    public function index()
    {
        // Redirect ke dashboard jika index diakses
        return redirect()->to('/koordinator/dashboard');
    }

    public function dashboard()
    {
        try {
            // Debug session
            log_message('debug', 'Dashboard - Session data: ' . json_encode(session()->get()));
            
            // Pastikan data tidak null
            $jadwal_today = $this->jadwalModel->getTodaySchedule();
            if ($jadwal_today === null) {
                $jadwal_today = [];
            }

            $data = [
                'jadwal_today' => $jadwal_today,
                'total_regu' => 4,
                'total_satpam' => $this->satpamModel->where('gi_id', 1)->where('is_koordinator', 0)->countAllResults(),
                'emergency_count' => 0
            ];

            log_message('debug', 'Dashboard data prepared successfully');
            return view('koordinator/dashboard', $data);
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            log_message('error', 'Dashboard error trace: ' . $e->getTraceAsString());
            
            // Redirect dengan pesan error
            return redirect()->to('/login')
                   ->with('error', 'Terjadi kesalahan sistem. Silakan login kembali.');
        }
    }

    public function jadwal()
    {
        try {
            $jadwal = $this->jadwalModel->select('jadwal_satpam.*, GROUP_CONCAT(satpam.nama) as anggota_names')
                ->join('shift_members', 'shift_members.jadwal_id = jadwal_satpam.id', 'left')
                ->join('satpam', 'satpam.id = shift_members.satpam_id AND shift_members.is_available = 1', 'left')
                ->groupBy('jadwal_satpam.id')
                ->orderBy('jadwal_satpam.tanggal', 'DESC')
                ->findAll();

            $data = [
                'jadwal' => $jadwal,
                'regu_list' => [1, 2, 3, 4],
            ];

            return view('koordinator/jadwal', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Jadwal error: ' . $e->getMessage());
            return redirect()->to('/koordinator/dashboard')
                   ->with('error', 'Terjadi kesalahan saat memuat jadwal.');
        }
    }

    public function createJadwal()
    {
        // Debug untuk melihat request
        log_message('debug', 'createJadwal called with method: ' . $this->request->getMethod());
        
        // Cek apakah file view ada
        if (!file_exists(APPPATH . 'Views/koordinator/create_jadwal.php')) {
            log_message('error', 'View file create_jadwal.php tidak ditemukan!');
            return redirect()->to('/koordinator/dashboard')
                   ->with('error', 'Template halaman tidak ditemukan.');
        }

        if ($this->request->getMethod() === 'POST') {
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
            
            $regu_number = $this->request->getPost('regu_number');
            $tanggal = $this->request->getPost('tanggal');
            $shift = $this->request->getPost('shift');
            $anggota_ids = $this->request->getPost('anggota_ids');

            // Validasi input
            if (!$regu_number || !$tanggal || !$shift) {
                return redirect()->back()->with('error', 'Semua field wajib diisi');
            }

            try {
                // Save jadwal
                $jadwal_data = [
                    'regu_number' => $regu_number,
                    'tanggal' => $tanggal,
                    'shift' => $shift,
                    'nama_satpam' => "Regu {$regu_number}",
                    'status' => 'normal',
                    'created_by' => session('satpam_user_id'),
                    'jam_mulai' => $this->getShiftStartTime($shift),
                    'jam_selesai' => $this->getShiftEndTime($shift),
                ];

                $jadwal_id = $this->jadwalModel->insert($jadwal_data);

                if ($jadwal_id && !empty($anggota_ids)) {
                    // Save shift members
                    $members_data = [];
                    foreach ($anggota_ids as $satpam_id) {
                        $members_data[] = [
                            'jadwal_id' => $jadwal_id,
                            'satpam_id' => $satpam_id,
                            'is_available' => 1,
                        ];
                    }
                    $this->shiftMembersModel->insertBatch($members_data);
                }

                return redirect()->to('/koordinator/jadwal')->with('success', 'Jadwal berhasil dibuat');
                
            } catch (\Exception $e) {
                log_message('error', 'Error creating jadwal: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jadwal');
            }
        }

        // GET request - tampilkan form
        try {
            $data = [
                'satpam_per_regu' => $this->getSatpamPerRegu(),
            ];

            log_message('debug', 'createJadwal view data: ' . json_encode($data));
            return view('koordinator/create_jadwal', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading create jadwal form: ' . $e->getMessage());
            return redirect()->to('/koordinator/dashboard')
                   ->with('error', 'Terjadi kesalahan saat memuat form.');
        }
    }

    public function editJadwal($id)
{
    $jadwal = $this->jadwalModel->find($id);
    if (!$jadwal) {
        log_message('error', "editJadwal: Jadwal with ID $id not found");
        return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
    }

    if ($this->request->getMethod() === 'POST') {
        $action = $this->request->getPost('action');

        if ($action === 'update_members') {
            try {
                // Update member availability
                $members_data = [];
                $available_members = $this->request->getPost('available_members') ?? [];
                $replacement_members = $this->request->getPost('replacement_members') ?? [];

                // Get original regu members
                $original_members = $this->satpamModel->where('regu_number', $jadwal['regu_number'])
                    ->where('gi_id', 1)
                    ->where('is_koordinator', 0)
                    ->findAll();
                log_message('debug', "editJadwal: Found " . count($original_members) . " original members for regu " . $jadwal['regu_number']);

                foreach ($original_members as $member) {
                    $is_available = in_array($member['id'], $available_members) ? 1 : 0;
                    $members_data[] = [
                        'jadwal_id' => $id,
                        'satpam_id' => $member['id'],
                        'is_available' => $is_available,
                        'notes' => $is_available ? null : 'Tidak hadir',
                    ];
                }

                // Add replacement members
                foreach ($replacement_members as $replacement_id) {
                    if (!empty($replacement_id)) {
                        $members_data[] = [
                            'jadwal_id' => $id,
                            'satpam_id' => $replacement_id,
                            'is_available' => 1,
                            'replacement_for' => $jadwal['regu_number'],
                            'notes' => 'Pengganti',
                        ];
                    }
                }
                log_message('debug', "editJadwal: Prepared " . count($members_data) . " members for update");

                // Hapus semua member yang ada terlebih dahulu
                $this->shiftMembersModel->where('jadwal_id', $id)->delete();
                // Kemudian masukkan data yang baru
                if (!empty($members_data)) {
                    $this->shiftMembersModel->insertBatch($members_data);
                }

                // Update jadwal status
                $this->jadwalModel->update($id, [
                    'status' => 'edited',
                    'notes' => $this->request->getPost('notes'),
                ]);

                log_message('info', "editJadwal: Successfully updated jadwal ID $id");
                return redirect()->to('/koordinator/jadwal')->with('success', 'Jadwal berhasil diupdate');
                
            } catch (\Exception $e) {
                log_message('error', "editJadwal: Error updating jadwal ID $id: " . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate jadwal');
            }
        }
    }

    try {
        $replacement_options = $this->satpamModel->where('status', 'active')
            ->where('gi_id', 1)
            ->orderBy('regu_number ASC, nama ASC')
            ->findAll();
        log_message('debug', "editJadwal: Found " . count($replacement_options) . " active satpam for replacement options");

        // Fallback data if no satpam found
        if (empty($replacement_options)) {
            $replacement_options = [
                ['id' => 1, 'nama' => 'DIAN H', 'regu_number' => 1, 'is_koordinator' => 1],
                ['id' => 2, 'nama' => 'DIDIN S', 'regu_number' => 1, 'is_koordinator' => 0],
                ['id' => 3, 'nama' => 'SANDI PURNAMA', 'regu_number' => 1, 'is_koordinator' => 0],
                ['id' => 4, 'nama' => 'DIK DIK', 'regu_number' => 1, 'is_koordinator' => 0],
                ['id' => 6, 'nama' => 'ARI H', 'regu_number' => 2, 'is_koordinator' => 0],
                ['id' => 7, 'nama' => 'M. WIJATHMINTA', 'regu_number' => 2, 'is_koordinator' => 0],
                ['id' => 8, 'nama' => 'AHMAD RIFA F', 'regu_number' => 2, 'is_koordinator' => 0],
                ['id' => 10, 'nama' => 'AAN', 'regu_number' => 3, 'is_koordinator' => 0],
                ['id' => 11, 'nama' => 'ATIF HIDAYAT', 'regu_number' => 3, 'is_koordinator' => 0],
                ['id' => 12, 'nama' => 'DADANG S', 'regu_number' => 3, 'is_koordinator' => 0],
                ['id' => 14, 'nama' => 'TAUFIK Z', 'regu_number' => 4, 'is_koordinator' => 0],
                ['id' => 15, 'nama' => 'ABDUL AZIZ', 'regu_number' => 4, 'is_koordinator' => 0],
                ['id' => 16, 'nama' => 'DIAN EFFENDI', 'regu_number' => 4, 'is_koordinator' => 0]
            ];
            log_message('info', "editJadwal: Using fallback satpam data due to empty database results");
        }

        $data = [
            'jadwal' => $jadwal,
            'current_members' => $this->shiftMembersModel->where('jadwal_id', $id)->findAll(),
            'regu_members' => $this->satpamModel->where('regu_number', $jadwal['regu_number'])
                ->where('gi_id', 1)
                ->where('is_koordinator', 0)
                ->findAll(),
            'replacement_options' => $replacement_options,
        ];
        log_message('debug', "editJadwal: Data prepared for view - jadwal ID: $id, regu_members: " . count($data['regu_members']) . ", replacement_options: " . count($data['replacement_options']));

        return view('koordinator/edit_jadwal', $data);
        
    } catch (\Exception $e) {
        log_message('error', "editJadwal: Error loading edit jadwal ID $id: " . $e->getMessage());
        return redirect()->to('/koordinator/jadwal')
               ->with('error', 'Terjadi kesalahan saat memuat halaman edit.');
    }
}

    public function deleteJadwal($id)
    {
        try {
            // Hapus shift members dulu
            $this->shiftMembersModel->where('jadwal_id', $id)->delete();
            
            // Hapus jadwal
            $this->jadwalModel->delete($id);
            
            return redirect()->to('/koordinator/jadwal')
                   ->with('success', 'Jadwal berhasil dihapus');
                   
        } catch (\Exception $e) {
            log_message('error', 'Error deleting jadwal: ' . $e->getMessage());
            return redirect()->to('/koordinator/jadwal')
                   ->with('error', 'Terjadi kesalahan saat menghapus jadwal');
        }
    }

    public function shiftDetails($id)
    {
        try {
            $jadwal = $this->jadwalModel->find($id);
            $members = $this->shiftMembersModel->getShiftMembersWithNames($id);
            
            $data = [
                'jadwal' => $jadwal,
                'members' => $members
            ];
            
            return view('koordinator/shift_details', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading shift details: ' . $e->getMessage());
            return '<div class="alert alert-danger">Error loading shift details</div>';
        }
    }

    private function getSatpamPerRegu()
    {
        $result = [];
        try {
            for ($i = 1; $i <= 4; $i++) {
                $members = $this->satpamModel->where('regu_number', $i)
                    ->where('gi_id', 1)
                    ->findAll(); // Ambil semua termasuk koordinator
                
                // Format untuk JavaScript
                $result[$i] = [];
                foreach ($members as $member) {
                    $result[$i][] = [
                        'id' => (int)$member['id'],
                        'nama' => $member['nama'],
                        'is_koordinator' => (bool)$member['is_koordinator']
                    ];
                }
            }
            
            log_message('debug', 'getSatpamPerRegu result: ' . json_encode($result));
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting satpam per regu: ' . $e->getMessage());
            // Return empty array if error
            for ($i = 1; $i <= 4; $i++) {
                $result[$i] = [];
            }
        }
        
        return $result;
    }

    // Helper methods untuk waktu shift
    private function getShiftStartTime($shift)
    {
        $times = [
            'P' => '08:00:00',
            'S' => '16:00:00', 
            'M' => '00:00:00',
            'L' => '00:00:00'
        ];
        return $times[$shift] ?? '08:00:00';
    }

    private function getShiftEndTime($shift)
    {
        $times = [
            'P' => '16:00:00',
            'S' => '00:00:00',
            'M' => '08:00:00', 
            'L' => '00:00:00'
        ];
        return $times[$shift] ?? '16:00:00';
    }
}