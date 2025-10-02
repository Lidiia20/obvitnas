<?php
echo "OBVITNAS - Final Database Setup\n";
echo "================================\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'obvitnas_db';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop and recreate database
    echo "Dropping and recreating database...\n";
    $pdo->exec("DROP DATABASE IF EXISTS $database");
    $pdo->exec("CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE $database");
    
    // Create zona_ultg table
    echo "Creating zona_ultg table...\n";
    $pdo->exec("
        CREATE TABLE zona_ultg (
            id int(11) NOT NULL AUTO_INCREMENT,
            nama_ultg varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
    
    // Insert zona_ultg data
    echo "Inserting zona_ultg data...\n";
    $pdo->exec("INSERT INTO zona_ultg (id, nama_ultg) VALUES (1, 'ULTG BANDUNG')");
    
    // Create zona_gi table
    echo "Creating zona_gi table...\n";
    $pdo->exec("
        CREATE TABLE zona_gi (
            id int(11) NOT NULL AUTO_INCREMENT,
            nama_gi varchar(100) NOT NULL,
            ultg_id int(11) DEFAULT NULL,
            PRIMARY KEY (id),
            KEY ultg_id (ultg_id),
            CONSTRAINT zona_gi_ibfk_1 FOREIGN KEY (ultg_id) REFERENCES zona_ultg (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
    
    // Insert zona_gi data
    echo "Inserting zona_gi data...\n";
    $pdo->exec("
        INSERT INTO zona_gi (id, nama_gi, ultg_id) VALUES 
        (1, 'GI 150KV BANDUNG UTARA', 1),
        (2, 'GI 150KV CIANJUR', 1),
        (3, 'GI 150KV CIBEUREUM BARU', 1),
        (4, 'GI 150KV CIGERELENG', 1),
        (5, 'GI 150KV PADALARANG BARU', 1),
        (6, 'GI 150KV PANASIA', 1)
    ");
    
    // Create users table
    echo "Creating users table...\n";
    $pdo->exec("
        CREATE TABLE users (
            id int(11) NOT NULL AUTO_INCREMENT,
            nama varchar(100) NOT NULL,
            no_identitas varchar(50) NOT NULL,
            no_hp varchar(20) NOT NULL,
            username varchar(50) NOT NULL,
            password varchar(255) NOT NULL,
            role enum('user','satpam','admin','admin_gi') NOT NULL DEFAULT 'user',
            no_kendaraan varchar(20) DEFAULT NULL,
            keperluan text DEFAULT NULL,
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id),
            UNIQUE KEY username (username),
            UNIQUE KEY no_identitas (no_identitas)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
    
    // Insert default users (password: password)
    echo "Creating default users...\n";
    $pdo->exec("
        INSERT INTO users (id, nama, no_identitas, no_hp, username, password, role, created_at) VALUES
        (1, 'Admin System', '1234567890', '081234567890', 'admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-01-01 00:00:00'),
        (2, 'Satpam 1', '0987654321', '089876543210', 'satpam1', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'satpam', '2025-01-01 00:00:00')
    ");
    
    // Create admin-gi table
    echo "Creating admin-gi table...\n";
    $pdo->exec("
        CREATE TABLE `admin-gi` (
            id int(11) NOT NULL AUTO_INCREMENT,
            nama varchar(100) DEFAULT NULL,
            username varchar(100) DEFAULT NULL,
            email varchar(100) DEFAULT NULL,
            password varchar(255) DEFAULT NULL,
            gi_id int(11) DEFAULT NULL,
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
    
    // Insert admin-gi data
    echo "Creating admin-gi users...\n";
    $pdo->exec("
        INSERT INTO `admin-gi` (id, nama, username, email, password, gi_id, created_at) VALUES
        (1, NULL, 'Admin GI 1', 'Admingi1@gmail.com', '\$2y\$10\$/6yrc0HY5NL0.6HGn8SNUu5FVnGqIONt6SBeaaXcaOKyb/MNjLQdi', 1, '2025-07-17 21:21:32'),
        (3, NULL, 'Admin GI 4', 'Admingi4@gmail.com', '\$2y\$10\$8DCg6Yyl1b2ZFMbZ84oT7u7Z4rJ3ohdzKkjoHDTl1ijVJuchuCW4u', 4, '2025-07-20 18:39:41')
    ");
    
    // Create kunjungan table
    echo "Creating kunjungan table...\n";
    $pdo->exec("
        CREATE TABLE kunjungan (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            gi_id int(11) NOT NULL,
            status enum('pending','checkin','checkout','cancelled') NOT NULL DEFAULT 'pending',
            jam_checkin datetime DEFAULT NULL,
            jam_checkout datetime DEFAULT NULL,
            undangan varchar(255) DEFAULT NULL,
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY gi_id (gi_id),
            CONSTRAINT kunjungan_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            CONSTRAINT kunjungan_ibfk_2 FOREIGN KEY (gi_id) REFERENCES zona_gi (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
    
    // Verify setup
    echo "\nVerifying database setup...\n";
    $result = $pdo->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables created:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Check zona_gi data
    echo "\nZona GI data:\n";
    $result = $pdo->query("SELECT * FROM zona_gi");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "- ID: {$row['id']}, Nama: {$row['nama_gi']}\n";
    }
    
    echo "\n=================================\n";
    echo "Database setup completed successfully!\n";
    echo "Database: $database\n";
    echo "Default credentials:\n";
    echo "- Admin: admin / password\n";
    echo "- Satpam: satpam1 / password\n";
    echo "\nYou can now run: php spark serve\n";
    echo "=================================\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 