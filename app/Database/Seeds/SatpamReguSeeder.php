<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SatpamReguSeeder extends Seeder
{
    public function run()
    {
        // 1. Insert users untuk login per regu + koordinator
        $users_satpam = [
            [
                'username' => 'koordinator',
                'password' => password_hash('koordinator2024', PASSWORD_DEFAULT),
                'role' => 'koordinator',
                'regu_number' => null,
                'gi_id' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'regu1',
                'password' => password_hash('regu1_2024', PASSWORD_DEFAULT),
                'role' => 'regu',
                'regu_number' => 1,
                'gi_id' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'regu2',
                'password' => password_hash('regu2_2024', PASSWORD_DEFAULT),
                'role' => 'regu',
                'regu_number' => 2,
                'gi_id' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'regu3',
                'password' => password_hash('regu3_2024', PASSWORD_DEFAULT),
                'role' => 'regu',
                'regu_number' => 3,
                'gi_id' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'regu4',
                'password' => password_hash('regu4_2024', PASSWORD_DEFAULT),
                'role' => 'regu',
                'regu_number' => 4,
                'gi_id' => 1,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users_satpam')->insertBatch($users_satpam);

        // 2. Clear existing data dan insert anggota satpam dengan regu
        $this->db->table('satpam')->truncate();
        
        $satpam_members = [
            // Koordinator - masuk semua regu
            [
                'nama' => 'DIAN H',
                'gi_id' => 1,
                'regu_number' => null,
                'is_koordinator' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // Regu 1
            [
                'nama' => 'DIDIN S',
                'gi_id' => 1,
                'regu_number' => 1,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'SANDI PURNAMA',
                'gi_id' => 1,
                'regu_number' => 1,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'DIK DIK',
                'gi_id' => 1,
                'regu_number' => 1,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // Regu 2
            [
                'nama' => 'ARI H',
                'gi_id' => 1,
                'regu_number' => 2,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'M. WIJATHMINTA',
                'gi_id' => 1,
                'regu_number' => 2,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'AHMAD RIFA F',
                'gi_id' => 1,
                'regu_number' => 2,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // Regu 3
            [
                'nama' => 'AAN',
                'gi_id' => 1,
                'regu_number' => 3,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'ATIF HIDAYAT',
                'gi_id' => 1,
                'regu_number' => 3,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'DADANG S',
                'gi_id' => 1,
                'regu_number' => 3,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // Regu 4
            [
                'nama' => 'TAUFIK Z',
                'gi_id' => 1,
                'regu_number' => 4,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'ABDUL AZIZ',
                'gi_id' => 1,
                'regu_number' => 4,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'DIAN EFFENDI',
                'gi_id' => 1,
                'regu_number' => 4,
                'is_koordinator' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('satpam')->insertBatch($satpam_members);
    }
}
