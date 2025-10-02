<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator Satpam - DIAN H</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar Styles */
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }

        /* Card Styles */
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }
        .gradient-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .gradient-info {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        /* Schedule Card Styles */
        .schedule-card {
            border-left: 4px solid #007bff;
            background: white;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
        }
        .shift-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 12px;
        }

        /* Button Styles */
        .action-btn {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .emergency-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            .stat-card {
                margin-bottom: 15px;
            }
            .schedule-card {
                padding: 10px;
            }
            .action-btn {
                padding: 6px 15px;
                font-size: 0.9rem;
            }
        }

        /* Accessibility Enhancements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" aria-label="Dashboard Koordinator Satpam">
                <i class="fas fa-user-tie me-2"></i>Koordinator Satpam - DIAN H
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>DIAN H
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-1">Dashboard Koordinator</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i><?= date('l, d F Y') ?>
                            <span class="ms-3"><i class="fas fa-clock me-1"></i><span id="current-time"><?= date('H:i:s') ?></span></span>
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="<?= base_url('koordinator/create-jadwal') ?>" class="btn btn-primary action-btn me-2">
                            <i class="fas fa-plus me-1"></i>Buat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card gradient-primary text-white" aria-labelledby="total-regu-label">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title mb-0" id="total-regu-label"><?= $total_regu ?? 0 ?></h3>
                                <p class="card-text mb-0">Total Regu</p>
                            </div>
                            <i class="fas fa-users fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card stat-card gradient-success text-white" aria-labelledby="total-anggota-label">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title mb-0" id="total-anggota-label"><?= $total_satpam ?? 0 ?></h3>
                                <p class="card-text mb-0">Total Anggota</p>
                            </div>
                            <i class="fas fa-user-shield fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stat-card gradient-info text-white" aria-labelledby="shift-hari-ini-label">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title mb-0" id="shift-hari-ini-label"><?= count($jadwal_today ?? []) ?></h3>
                                <p class="card-text mb-0">Shift Hari Ini</p>
                            </div>
                            <i class="fas fa-calendar-check fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Today's Schedule -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Jadwal Hari Ini</h5>
                        <a href="<?= base_url('koordinator/jadwal') ?>" class="btn btn-sm btn-outline-primary action-btn">
                            <i class="fas fa-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($jadwal_today)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3" aria-hidden="true"></i>
                                <p class="text-muted">Tidak ada jadwal untuk hari ini</p>
                                <a href="<?= base_url('koordinator/create-jadwal') ?>" class="btn btn-primary action-btn">
                                    <i class="fas fa-plus me-1"></i>Buat Jadwal
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($jadwal_today as $jadwal): ?>
                                <div class="schedule-card" aria-labelledby="jadwal-<?= $jadwal['id'] ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <h6 class="mb-1" id="jadwal-<?= $jadwal['id'] ?>">Regu <?= esc($jadwal['regu_number']) ?></h6>
                                            <small class="text-muted"><?= esc($jadwal['nama_satpam']) ?></small>
                                        </div>
                                        <div class="col-md-2">
                                            <?php 
                                            $shift_labels = ['P' => 'Pagi', 'S' => 'Siang', 'M' => 'Malam', 'L' => 'Libur'];
                                            $shift_colors = ['P' => 'success', 'S' => 'warning', 'M' => 'info', 'L' => 'secondary'];
                                            ?>
                                            <span class="badge bg-<?= $shift_colors[$jadwal['shift']] ?? 'secondary' ?> shift-badge">
                                                <?= $shift_labels[$jadwal['shift']] ?? 'Unknown' ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                                <?= date('H:i', strtotime($jadwal['jam_mulai'])) ?> - 
                                                <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>
                                            </small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge bg-<?= $jadwal['status'] !== 'normal' ? 'danger' : 'success' ?>">
                                                <?= ucfirst($jadwal['status'] ?? 'normal') ?>
                                            </span>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="viewShiftDetails(<?= $jadwal['id'] ?>)" aria-label="Lihat detail jadwal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="<?= base_url('koordinator/edit-jadwal/' . $jadwal['id']) ?>" class="btn btn-outline-warning" aria-label="Edit jadwal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Regu Status -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Status Regu</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Assume $regu_status is provided by the controller
                        $regu_status = $regu_status ?? [
                            1 => ['active' => 3],
                            2 => ['active' => 3],
                            3 => ['active' => 3],
                            4 => ['active' => 3]
                        ];
                        $total_active = array_sum(array_column($regu_status, 'active'));
                        ?>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Regu <?= $i ?></span>
                                <div>
                                    <span class="badge bg-success me-1"><?= $regu_status[$i]['active'] ?? 0 ?></span>
                                    <small class="text-muted">aktif</small>
                                </div>
                            </div>
                        <?php endfor; ?>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total Aktif</strong>
                            <span class="badge bg-primary"><?= $total_active ?> orang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shift Details Modal -->
    <div class="modal fade" id="shiftDetailsModal" tabindex="-1" aria-labelledby="shiftDetailsModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftDetailsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Detail Shift
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="shiftDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Memuat...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Utility Functions
        const updateTime = () => {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
        };

        const viewShiftDetails = (jadwalId) => {
            const modal = new bootstrap.Modal(document.getElementById('shiftDetailsModal'));
            const content = document.getElementById('shiftDetailsContent');
            
            content.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Memuat...</span></div></div>';
            modal.show();

            fetch(`<?= base_url('koordinator/shift-details/') ?>${jadwalId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching shift details:', error);
                    content.innerHTML = '<div class="alert alert-danger">Gagal memuat detail shift. Silakan coba lagi.</div>';
                });
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Update time every second
            updateTime();
            setInterval(updateTime, 1000);

            // Auto-refresh every 5 minutes
            setInterval(() => {
                location.reload();
            }, 300000);
        });
    </script>
</body>
</html>