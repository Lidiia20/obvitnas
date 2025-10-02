<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .content {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-bottom: none;
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #2d3748;
            font-weight: 600;
        }
        
        .btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem 0.75rem;
        }
        
        .table tbody tr:hover {
            background: rgba(0, 123, 255, 0.05);
        }
        
        .image-preview {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
        }
        
        .action-buttons .btn {
            min-width: 80px;
            padding: 0.4rem 0.8rem;
        }
        
        .security-notice {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <!-- Security Notice -->
                    <div class="security-notice">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Sistem Keamanan:</strong> Data yang telah diinput tidak dapat diubah atau dihapus untuk menjaga integritas log keamanan dan audit trail.
                    </div>

                    <!-- Statistics Cards -->
                    <div class="stats-card">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-primary"><?= $stats['total'] ?></div>
                                    <div class="stat-label">Total Barang</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-success"><?= $stats['today'] ?></div>
                                    <div class="stat-label">Hari Ini</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-warning"><?= $stats['thisWeek'] ?></div>
                                    <div class="stat-label">Minggu Ini</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-info"><?= $stats['thisMonth'] ?></div>
                                    <div class="stat-label">Bulan Ini</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Card -->
                    <div class="main-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-box-open me-2"></i>Data Barang Masuk
                                    </h5>
                                    <small class="opacity-75">Log pengendalian barang masuk - Data permanen</small>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-md-0">
                                    <!-- Tombol Kembali ke Dashboard -->
                                    <a href="<?= base_url('satpam/dashboard') ?>" class="btn btn-outline-light">
                                        <i class="fas fa-home me-2"></i>Dashboard
                                    </a>
                                    <!-- Tombol Tambah Data -->
                                    <a href="<?= route_to('barang_masuk_create') ?>" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Search Form -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <form method="GET" action="<?= base_url('satpam/barangmasuk') ?>">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?= $q ?>" 
                                                   placeholder="Cari berdasarkan instansi, petugas, atau keterangan barang...">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                            <?php if($q): ?>
                                                <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times"></i> Reset
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Flash Messages -->
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Tanggal</th>
                                            <th width="8%">Waktu</th>
                                            <th width="18%">Instansi</th>
                                            <th width="15%">Petugas</th>
                                            <th width="20%">Keterangan Barang</th>
                                            <th width="8%">Foto</th>
                                            <th width="10%">Kesesuaian</th>
                                            <th width="6%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($barangMasuk)): ?>
                                            <?php foreach ($barangMasuk as $index => $item): ?>
                                                <tr>
                                                    <td><?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + $index + 1 ?></td>
                                                    <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                                    <td><?= date('H:i', strtotime($item['waktu'])) ?></td>
                                                    <td>
                                                        <strong><?= esc($item['nama_instansi']) ?></strong>
                                                        <?php if ($item['nama_petugas']): ?>
                                                            <br><small class="text-muted"><?= esc($item['nama_petugas']) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($item['nama_petugas']) ?: '-' ?>
                                                        <?php if ($item['no_hp']): ?>
                                                            <br><small class="text-muted"><?= esc($item['no_hp']) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                            <?php 
                                                            $keterangan = esc($item['keterangan_barang']);
                                                            echo strlen($keterangan) > 100 ? substr($keterangan, 0, 100) . '...' : $keterangan;
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($item['foto_barang']): ?>
                                                            <img src="<?= base_url('public/uploads/barang_masuk/' . $item['foto_barang']) ?>" 
                                                                 alt="Foto Barang" class="image-preview" 
                                                                 onclick="showImage('<?= base_url('public/uploads/barang_masuk/' . $item['foto_barang']) ?>')">
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak ada foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($item['kesesuaian'] == 'sesuai'): ?>
                                                            <span class="badge bg-success">Sesuai</span>
                                                        <?php elseif ($item['kesesuaian'] == 'tidak_sesuai'): ?>
                                                            <span class="badge bg-danger">Tidak Sesuai</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Belum Dicek</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <!-- SIMPLIFIED: Only Detail Button -->
                                                        <div class="action-buttons">
                                                            <a href="<?= base_url('satpam/barangmasuk/view/' . $item['id']) ?>" 
                                                               class="btn btn-info btn-sm" title="Lihat Detail">
                                                                <i class="fas fa-eye me-1"></i>Detail
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">
                                                        <?= $q ? 'Tidak ada data yang sesuai dengan pencarian "' . esc($q) . '"' : 'Tidak ada data barang masuk' ?>
                                                    </p>
                                                    <?php if ($q): ?>
                                                        <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-outline-primary">
                                                            <i class="fas fa-arrow-left me-1"></i> Kembali ke semua data
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if (!empty($barangMasuk)): ?>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Menampilkan <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> - 
                                        <?= min($pager->getCurrentPage() * $pager->getPerPage(), $total) ?> dari <?= $total ?> data
                                    </div>
                                    <div>
                                        <?= $pager->links() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-image me-2"></i>Preview Foto Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Barang" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview function
        function showImage(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>