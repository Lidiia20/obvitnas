<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Satpam - GITET New Ujung Berung</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #f8fafc;
            --accent-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }

        /* Sidebar Enhancement */
        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--sahadow-heavy);
            z-index: 1000;
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-header h4 {
            font-weight: 800;
            font-size: 1.4rem;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .sidebar-header small {
            opacity: 0.8;
            font-weight: 500;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            padding: 1rem 1.5rem;
            margin: 0.25rem 0.75rem;
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateX(8px);
        }

        .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: white;
            border-radius: 2px 0 0 2px;
        }

        /* Content Enhancement */
        .content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Header Enhancement */
        .header-section {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .dashboard-title {
            background: linear-gradient(135deg, var(--primary-color), #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.2rem;
            margin: 0;
            letter-spacing: -1px;
        }

        .dashboard-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
            font-weight: 500;
        }

        /* Stats Cards Enhancement */
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 20px 20px 0 0;
        }

        .stats-card.primary::before { background: linear-gradient(90deg, var(--primary-color), #8b5cf6); }
        .stats-card.success::before { background: linear-gradient(90deg, var(--accent-color), #34d399); }
        .stats-card.warning::before { background: linear-gradient(90deg, var(--warning-color), #fbbf24); }
        .stats-card.danger::before { background: linear-gradient(90deg, var(--danger-color), #f87171); }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-heavy);
        }

        .stats-card .card-body {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Enhancement */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .table-header small {
            opacity: 0.8;
            font-weight: 500;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 700;
            color: var(--text-secondary);
            padding: 1.2rem 1rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            border: none;
            padding: 1.2rem 1rem;
            vertical-align: middle;
            font-weight: 500;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
            transform: scale(1.005);
        }

        .badge-status {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Zone Cards Enhancement */
        .zone-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .zone-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .zone-header {
            padding: 1.2rem 1.5rem;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .zone-body {
            padding: 1.5rem;
        }

        /* Button Enhancement */
        .btn {
            border-radius: 50px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--danger-color), #b91c1c);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, var(--danger-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .refresh-btn {
            background: linear-gradient(135deg, var(--info-color), #0891b2);
            color: white;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        }

        .refresh-btn:hover {
            background: linear-gradient(135deg, #0e7490, var(--info-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
        }

        /* Alert Enhancement */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-success::before {
            background: linear-gradient(90deg, var(--accent-color), #34d399);
        }

        /* Empty State Enhancement */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
            color: var(--text-secondary);
        }

        .empty-state h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        /* Zone Section Enhancement */
        .zone-section-header {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .zone-section-title {
            background: linear-gradient(135deg, var(--primary-color), #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }

            .main-container {
                padding: 1.5rem;
                border-radius: 15px;
            }

            .dashboard-title {
                font-size: 1.8rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .header-section {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card .card-body {
                padding: 1.5rem 1rem;
            }

            .stats-number {
                font-size: 1.8rem;
            }

            .zone-body {
                padding: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>

<!-- Sidebar Navigasi -->
<div class="sidebar d-flex flex-column">
    <div class="sidebar-header">
        <h4 class="mb-1">
            <i class="fas fa-shield-alt me-2"></i>
            Satpam Panel
        </h4>
        <small class="text-light">GITET New Ujung Berung</small>
    </div>
    
    <ul class="nav flex-column flex-grow-1 p-3">
        <li class="nav-item">
            <a class="nav-link active" href="<?= base_url('satpam/dashboard') ?>">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('satpam/checkin') ?>">
                <i class="fas fa-sign-in-alt me-2"></i>Check-In
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('satpam/checkout') ?>">
                <i class="fas fa-sign-out-alt me-2"></i>Check-Out
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('satpam/historyKunjungan') ?>">
                <i class="fas fa-history me-2"></i>History Kunjungan
            </a>
        <li class="nav-item">
    <a class="nav-link" href="<?= base_url('satpam/barangmasuk') ?>">
        <i class="fas fa-box-open me-2"></i>Barang Masuk
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('satpam/barangkeluar') ?>">
        <i class="fas fa-truck-loading me-2"></i>Barang Keluar
    </a>
</li>
    </ul>
    
    <div class="p-3 border-top border-light border-opacity-25">
        <a href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin logout?')" class="btn btn-logout w-100">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="content">
    <div class="main-container animate-fade-up">
        
        <!-- Header Section -->
        <div class="header-section animate-fade-up animate-delay-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="dashboard-title">
                        <i class="fas fa-tachometer-alt me-3"></i>Dashboard Satpam
                    </h2>
                    <p class="dashboard-subtitle">Selamat datang di panel kontrol satpam GITET New Ujung Berung</p>
                </div>
                <button class="btn refresh-btn" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Success Alert -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show animate-fade-up animate-delay-2" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card primary animate-fade-up animate-delay-1">
                    <div class="card-body">
                        <div class="stats-icon text-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-number"><?= $total_count ?></div>
                        <div class="stats-label">Total Tamu</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success animate-fade-up animate-delay-2">
                    <div class="card-body">
                        <div class="stats-icon text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-number"><?= $approved_count ?></div>
                        <div class="stats-label">Tamu Check-In</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card warning animate-fade-up animate-delay-3">
                    <div class="card-body">
                        <div class="stats-icon text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-number"><?= $pending_count ?></div>
                        <div class="stats-label">Tamu Pending</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card danger animate-fade-up animate-delay-4">
                    <div class="card-body">
                        <div class="stats-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="stats-number"><?= $checkout_count ?></div>
                        <div class="stats-label">Tamu Check-Out</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Tamu Pending -->
        <div class="table-container animate-fade-up animate-delay-2">
            <div class="table-header">
                <h5 class="mb-1">
                    <i class="fas fa-list me-2"></i>
                    Daftar Tamu Pending Check-In
                </h5>
                <small>Tamu yang menunggu proses check-in</small>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-user me-1"></i>Nama Tamu</th>
                            <th><i class="fas fa-car me-1"></i>No Kendaraan</th>
                            <th><i class="fas fa-tasks me-1"></i>Keperluan</th>
                            <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kunjungan)): ?>
                        <?php foreach ($kunjungan as $k): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary px-3 py-2">
                                        #<?= $k['id'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder me-3">
                                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                                        </div>
                                        <div class="fw-semibold"><?= esc($k['nama_tamu']) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark px-3 py-2">
                                        <i class="fas fa-car me-1"></i>
                                        <?= esc($k['no_kendaraan']) ?>
                                    </span>
                                </td>
                                <td><?= esc($k['keperluan']) ?></td>
                                <td>
                                    <small class="text-muted fw-medium">
                                        <?= date('d/m/Y H:i', strtotime($k['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge-status bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>
                                        PENDING
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>Tidak ada tamu pending</h5>
                                        <p class="mb-0">Semua tamu telah diproses atau belum ada kunjungan baru</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Keterangan Zona -->
        <div class="zone-section-header animate-fade-up animate-delay-3">
            <h4 class="zone-section-title">
                <i class="fas fa-map-marker-alt me-2"></i>
                Keterangan Warna Kartu Zona
            </h4>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="zone-card animate-fade-up animate-delay-3">
                    <div class="zone-header bg-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Zona Tertutup (Merah)
                    </div>
                    <div class="zone-body">
                        <p class="mb-2"><strong>Deskripsi:</strong> Daerah sangat vital, gangguan dapat menghentikan aktivitas perusahaan.</p>
                        <p class="mb-0"><strong>Risiko:</strong> <span class="badge bg-danger">Sangat Tinggi / Ekstrem</span></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="zone-card animate-fade-up animate-delay-4">
                    <div class="zone-header bg-warning text-dark">
                        <i class="fas fa-exclamation me-2"></i>
                        Zona Terlarang (Kuning)
                    </div>
                    <div class="zone-body">
                        <p class="mb-2"><strong>Deskripsi:</strong> Gangguan dapat mempengaruhi aktivitas instalasi.</p>
                        <p class="mb-0"><strong>Risiko:</strong> <span class="badge bg-warning text-dark">Tinggi</span></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="zone-card animate-fade-up animate-delay-3">
                    <div class="zone-header bg-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Zona Terbatas (Biru)
                    </div>
                    <div class="zone-body">
                        <p class="mb-2"><strong>Deskripsi:</strong> Gangguan tidak menghentikan aktivitas instalasi.</p>
                        <p class="mb-0"><strong>Risiko:</strong> <span class="badge bg-primary">Sedang</span></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="zone-card animate-fade-up animate-delay-4">
                    <div class="zone-header bg-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Zona Bebas Terbatas (Hijau)
                    </div>
                    <div class="zone-body">
                        <p class="mb-2"><strong>Deskripsi:</strong> Gangguan tidak menghambat aktivitas instalasi.</p>
                        <p class="mb-0"><strong>Risiko:</strong> <span class="badge bg-success">Rendah</span></p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto refresh setiap 30 detik
    setInterval(function() {
        // Hanya refresh jika tidak ada modal yang terbuka
        if (!document.querySelector('.modal.show')) {
            location.reload();
        }
    }, 30000);

    // Tambahkan animasi loading saat refresh
    document.querySelector('.refresh-btn').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
        this.disabled = true;
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        });
    }, 5000);

    // Add ripple effect to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add ripple animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Mobile sidebar toggle functionality
    const toggleSidebar = () => {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    };

    // Add mobile menu button if needed
    if (window.innerWidth <= 768) {
        const mobileMenuBtn = document.createElement('button');
        mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        mobileMenuBtn.className = 'btn btn-primary position-fixed';
        mobileMenuBtn.style.cssText = 'top: 1rem; left: 1rem; z-index: 1001; border-radius: 50%; width: 50px; height: 50px;';
        mobileMenuBtn.onclick = toggleSidebar;
        document.body.appendChild(mobileMenuBtn);
    }