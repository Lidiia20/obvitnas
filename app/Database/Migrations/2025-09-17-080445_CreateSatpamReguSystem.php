<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSatpamReguSystem extends Migration
{
    public function up()
    {
        // 1. Tabel users_satpam untuk login per regu
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['koordinator', 'regu'],
                'default' => 'regu',
            ],
            'regu_number' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],
            'gi_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users_satpam');

        // 2. Modifikasi tabel satpam existing untuk add regu info
        $fields = [
            'regu_number' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],
            'is_koordinator' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'replacement'],
                'default' => 'active',
            ],
        ];
        $this->forge->addColumn('satpam', $fields);

        // 3. Modifikasi tabel jadwal_satpam existing
        $jadwalFields = [
            'regu_number' => [
                'type' => 'INT',
                'constraint' => 1,
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'jam_selesai' => [
                'type' => 'TIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['normal', 'edited', 'emergency'],
                'default' => 'normal',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('jadwal_satpam', $jadwalFields);

        // 4. Tabel shift_members untuk handle replacement
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'jadwal_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'satpam_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'is_available' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'replacement_for' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('shift_members');

        // 5. Modifikasi tabel kunjungan untuk tracking verifikator
        $kunjunganFields = [
            'verified_by_satpam_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'shift_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'verification_method' => [
                'type' => 'ENUM',
                'constraint' => ['face_recognition', 'manual'],
                'default' => 'face_recognition',
            ],
        ];
        $this->forge->addColumn('kunjungan', $kunjunganFields);
    }

    public function down()
    {
        $this->forge->dropTable('users_satpam');
        $this->forge->dropTable('shift_members');
        $this->forge->dropColumn('satpam', ['regu_number', 'is_koordinator', 'status']);
        $this->forge->dropColumn('jadwal_satpam', ['regu_number', 'jam_mulai', 'jam_selesai', 'status', 'created_by', 'notes', 'created_at', 'updated_at']);
        $this->forge->dropColumn('kunjungan', ['verified_by_satpam_id', 'shift_id', 'verification_method']);
    }
}