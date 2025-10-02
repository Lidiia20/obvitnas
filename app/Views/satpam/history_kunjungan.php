<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kunjungan Tamu - GITET New Ujung Berung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --purple-color: #6f42c1;
            --pink-color: #e83e8c;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: 1;
        }

        .main-container {
            position: relative;
            z-index: 2;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            padding: 1.5rem;
        }

        .table {
            margin: 0;
        }

        .table th {
            border: none;
            font-weight: 600;
            color: var(--secondary-color);
            background: #f8f9fa;
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(13, 110, 253, 0.05);
            transform: translateX(5px);
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(5px);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(13, 110, 253, 0.15);
            background: white;
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), var(--purple-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .status-approved {
            background: linear-gradient(135deg, var(--success-color), #146c43);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: #000;
        }

        .status-checkout {
            background: linear-gradient(135deg, var(--danger-color), #b02a37);
            color: white;
        }

        .vehicle-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--secondary-color);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .floating-btn {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .filter-icon {
            background: linear-gradient(135deg, var(--info-color), var(--purple-color));
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        @media (max-width: 768px) {
            .header-section, .filter-section {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .table-responsive {
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Riwayat Kunjungan Tamu
                    </h2>
                    <p class="text-muted mb-0">Data lengkap riwayat kunjungan tamu GITET New Ujung Berung</p>
                </div>
                <a href="<?= base_url('satpam/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <?php
            $total_kunjungan = count($kunjungan);
            $approved_count = 0;
            $pending_count = 0;
            $checkout_count = 0;
            
            foreach ($kunjungan as $k) {
                switch ($k['status']) {
                    case 'approved':
                        $approved_count++;
                        break;
                    case 'pending':
                        $pending_count++;
                        break;
                    case 'checkout':
                        $checkout_count++;
                        break;
                }
            }
            ?>
            
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?= $total_kunjungan ?></div>
                    <div class="stats-label">Total Kunjungan</div>
                </div>
                </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number text-success"><?= $approved_count ?></div>
                    <div class="stats-label">Check-In</div>
                </div>
                        </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number text-warning"><?= $pending_count ?></div>
                    <div class="stats-label">Pending</div>
                </div>
                        </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number text-danger"><?= $checkout_count ?></div>
                    <div class="stats-label">Check-Out</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="d-flex align-items-center mb-3">
                <div class="filter-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <h5 class="mb-0">Filter Data</h5>
                        </div>

            <form action="<?= base_url('satpam/historyKunjungan') ?>" method="get" class="row g-3">
                <div class="col-md-4">
                    <label for="nama" class="form-label">
                        <i class="fas fa-user me-1"></i>Nama Tamu
                    </label>
                    <input type="text" 
                           id="nama"
                           name="nama" 
                           class="form-control" 
                           value="<?= $filters['nama'] ?? '' ?>" 
                           placeholder="Cari berdasarkan nama tamu">
                </div>

                <div class="col-md-4">
                    <label for="keperluan" class="form-label">
                        <i class="fas fa-tasks me-1"></i>Keperluan
                    </label>
                    <input type="text" 
                           id="keperluan"
                           name="keperluan" 
                           class="form-control" 
                           value="<?= $filters['keperluan'] ?? '' ?>" 
                           placeholder="Cari berdasarkan keperluan">
                </div>

                <div class="col-md-4">
                    <label for="tanggal" class="form-label">
                        <i class="fas fa-calendar me-1"></i>Tanggal
                    </label>
                    <input type="date" 
                           id="tanggal"
                           name="tanggal" 
                           class="form-control" 
                           value="<?= $filters['tanggal'] ?? '' ?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter Data
                    </button>
                    <a href="<?= base_url('satpam/historyKunjungan') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Reset Filter
                    </a>
                </div>
                    </form>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Data Riwayat Kunjungan
                </h5>
                <small class="opacity-75">Total <?= count($kunjungan) ?> data kunjungan</small>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-user me-1"></i>Nama Tamu</th>
                            <th><i class="fas fa-car me-1"></i>No Kendaraan</th>
                            <th><i class="fas fa-tasks me-1"></i>Keperluan</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                            <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                            <th><i class="fas fa-clock me-1"></i>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($kunjungan)): ?>
                            <?php foreach ($kunjungan as $data): ?>
                                <tr>
                                    <td><strong>#<?= $data['id'] ?></strong></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($data['nama_tamu']) ?></div>
                                    </td>
                                    <td>
                                        <span class="vehicle-badge">
                                            <i class="fas fa-car me-1"></i>
                                            <?= esc($data['no_kendaraan']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($data['keperluan']) ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        $status_icon = '';
                                        switch ($data['status']) {
                                            case 'approved':
                                                $status_class = 'status-approved';
                                                $status_icon = 'fas fa-check-circle';
                                                break;
                                            case 'pending':
                                                $status_class = 'status-pending';
                                                $status_icon = 'fas fa-clock';
                                                break;
                                            case 'checkout':
                                                $status_class = 'status-checkout';
                                                $status_icon = 'fas fa-sign-out-alt';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <i class="<?= $status_icon ?> me-1"></i>
                                            <?= ucfirst($data['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($data['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($data['created_at'])) ?> WIB
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>Tidak ada data kunjungan</h5>
                                        <p class="mb-0">Belum ada riwayat kunjungan yang tersedia</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button class="btn floating-btn" onclick="location.reload()" title="Refresh Halaman">
        <i class="fas fa-sync-alt"></i>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto refresh setiap 60 detik
setInterval(function() {
    location.reload();
}, 60000);

// Add entrance animation for cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stats-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// Add table row animation
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.table tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 100);
    });
});
</script>
</body>
</html>
