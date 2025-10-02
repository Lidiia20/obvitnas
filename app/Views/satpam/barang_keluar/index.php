<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --section-bg: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            --primary-color: #007bff;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #fbb12d;
            --info-color: #38b2ac;
            --text-dark: #2d3748;
            --border-light: #e2e8f0;
            --shadow-medium: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        body {
            background: var(--primary-gradient);
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
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-medium), 0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .stats-card {
            background: var(--section-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #f6ad55);
            color: white;
            box-shadow: 0 4px 15px rgba(251, 177, 45, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #e53e3e);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
        }
        
        .btn-info {
            background: linear-gradient(135deg, var(--info-color), #2c7a7b);
            color: white;
            box-shadow: 0 4px 15px rgba(56, 178, 172, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 161, 105, 0.1));
            border-left: 4px solid var(--success-color);
            color: #2f855a;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.1));
            border-left: 4px solid var(--danger-color);
            color: #c53030;
        }
        
        .table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .table tbody tr:hover {
            background: rgba(0, 123, 255, 0.05);
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .badge-success {
            background: linear-gradient(135deg, var(--success-color), #38a169);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color), #f6ad55);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, var(--danger-color), #e53e3e);
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #a0aec0, #718096);
            color: white;
        }
        
        .search-filter-section {
            background: var(--section-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .form-control, .form-select {
            border: 2px solid var(--border-light);
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .image-preview {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.1);
        }
        
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-bottom: none;
            border-radius: 20px 20px 0 0;
        }
        
        .export-section {
            background: var(--section-bg);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .dt-buttons {
            margin-bottom: 1rem;
        }
        
        .dt-button {
            background: linear-gradient(135deg, var(--info-color), #2c7a7b) !important;
            color: white !important;
            border: none !important;
            border-radius: 25px !important;
            padding: 0.5rem 1rem !important;
            margin: 0.25rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }
        
        .dt-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(56, 178, 172, 0.4) !important;
        }
        
        @media (max-width: 768px) {
            .card-body { padding: 1rem; }
            .table-responsive { font-size: 0.8rem; }
            .btn { font-size: 0.75rem; padding: 0.4rem 0.8rem; }
            .stats-card { padding: 1rem; }
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<!-- ... (bagian head dan style tetap sama) ... -->

<body>
    <div class="content">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <!-- Success Alert -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Alert -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Statistics Cards -->
                    <?php if (isset($statistics)): ?>
                    <div class="stats-card">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-primary"><?= $statistics['total_all'] ?? 0 ?></div>
                                    <div class="stat-label">Total Barang</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-success"><?= $statistics['today'] ?? 0 ?></div>
                                    <div class="stat-label">Hari Ini</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-warning"><?= $statistics['this_month'] ?? 0 ?></div>
                                    <div class="stat-label">Bulan Ini</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-info"><?= $statistics['this_year'] ?? 0 ?></div>
                                    <div class="stat-label">Tahun Ini</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Main Card -->
                    <div class="main-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="header-title">
                                        <i class="fas fa-shipping-fast me-2"></i>Data Barang Keluar
                                    </h5>
                                    <small class="opacity-75">Kelola dan pantau barang yang keluar</small>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-md-0">
                                    <a href="<?= base_url('satpam/dashboard') ?>" class="btn btn-outline-light">
                                        <i class="fas fa-home me-2"></i>Dashboard
                                    </a>
                                    <a href="<?= route_to('barang_keluar_create') ?>" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Search and Filter Section -->
                            <div class="search-filter-section">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Dari</label>
                                        <input type="date" class="form-control" id="tanggal_dari" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Sampai</label>
                                        <input type="date" class="form-control" id="tanggal_sampai" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Instansi</label>
                                        <input type="text" class="form-control" id="filter_instansi" placeholder="Cari instansi...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Satpam</label>
                                        <input type="text" class="form-control" id="filter_satpam" placeholder="Cari satpam...">
                                    </div>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" onclick="filterData()">
                                            <i class="fas fa-search me-2"></i>Filter Data
                                        </button>
                                        <button type="button" class="btn btn-secondary ms-2" onclick="resetFilter()">
                                            <i class="fas fa-refresh me-2"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Export Section -->
                            <div class="export-section">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Export Data:</strong>
                                        <small class="text-muted">Download data dalam berbagai format</small>
                                    </div>
                                    <div id="exportButtons"></div>
                                </div>
                            </div>

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="barangKeluarTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Tanggal</th>
                                            <th width="8%">Waktu</th>
                                            <th width="15%">Instansi</th>
                                            <th width="12%">Petugas</th>
                                            <th width="12%">Pemilik Barang</th>
                                            <th width="15%">Keterangan</th>
                                            <th width="8%">Foto</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($barangKeluar)): ?>
                                            <?php foreach ($barangKeluar as $index => $item): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                                    <td><?= date('H:i', strtotime($item['waktu'])) ?></td>
                                                    <td>
                                                        <strong><?= esc($item['nama_instansi']) ?></strong>
                                                        <?php if ($item['nama_petugas']): ?>
                                                            <br><small class="text-muted"><?= esc($item['nama_petugas']) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($item['nama_petugas'] ?? '-') ?>
                                                        <?php if ($item['no_hp']): ?>
                                                            <br><small class="text-muted"><?= esc($item['no_hp']) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($item['pemilik_barang']) ?></td>
                                                    <td>
                                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                            <?= esc(substr($item['keterangan_barang'], 0, 100)) ?>
                                                            <?= strlen($item['keterangan_barang']) > 100 ? '...' : '' ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1 flex-wrap">
                                                            <?php if ($item['foto_surat_jalan']): ?>
                                                                <img src="<?= base_url('public/uploads/barang_keluar/' . $item['foto_surat_jalan']) ?>" 
                                                                     alt="Surat" class="image-preview" title="Foto Surat Jalan"
                                                                     onclick="showImageModal('<?= base_url('public/uploads/barang_keluar/' . $item['foto_surat_jalan']) ?>', 'Foto Surat Jalan')">
                                                            <?php endif; ?>
                                                            <?php if ($item['foto_barang']): ?>
                                                                <img src="<?= base_url('public/uploads/barang_keluar/' . $item['foto_barang']) ?>" 
                                                                     alt="Barang" class="image-preview" title="Foto Barang"
                                                                     onclick="showImageModal('<?= base_url('public/uploads/barang_keluar/' . $item['foto_barang']) ?>', 'Foto Barang')">
                                                            <?php endif; ?>
                                                            <?php if (!$item['foto_surat_jalan'] && !$item['foto_barang']): ?>
                                                                <span class="text-muted small">Tidak ada foto</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm" 
                                                                onclick="showDetail(<?= $item['id'] ?>)" 
                                                                title="Lihat Detail">
                                                            <i class="fas fa-eye me-1"></i>Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <br>Belum ada data barang keluar
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
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
                    <h5 class="modal-title" id="imageModalTitle">
                        <i class="fas fa-image me-2"></i>Preview Foto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Barang Keluar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="detailContent">
            <p class="text-center text-muted">Memuat data...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#barangKeluarTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-1"></i>Excel',
                        className: 'btn btn-success btn-sm',
                        title: 'Data Barang Keluar'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                        className: 'btn btn-danger btn-sm',
                        title: 'Data Barang Keluar',
                        orientation: 'landscape'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i>Print',
                        className: 'btn btn-info btn-sm',
                        title: 'Data Barang Keluar'
                    }
                ],
                order: [[1, 'desc']],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
            });

            // Move buttons to export section
            $('.dt-buttons').appendTo('#exportButtons');
        });

        // PERBAIKAN: Fungsi showDetail yang benar
        function showDetail(id) {
            const url = '<?= base_url('satpam/barangkeluar/view/') ?>' + id;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById("detailContent").innerHTML = html;
                    let modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById("detailContent").innerHTML =
                        '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data. Silakan coba lagi.</div>';
                });
        }

        // Fungsi untuk menampilkan gambar di modal
        function showImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalTitle').innerHTML = '<i class="fas fa-image me-2"></i>' + title;
            let modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        // Fungsi filter data
        function filterData() {
            const tanggalDari = document.getElementById('tanggal_dari').value;
            const tanggalSampai = document.getElementById('tanggal_sampai').value;
            const instansi = document.getElementById('filter_instansi').value;
            const satpam = document.getElementById('filter_satpam').value;

            // Rebuild table dengan filter
            table.search('').columns().search('').draw();
            
            // Apply custom filtering jika diperlukan
            if (instansi) {
                table.column(3).search(instansi).draw();
            }
            if (satpam) {
                table.column(8).search(satpam).draw();
            }
        }

        // Fungsi reset filter
        function resetFilter() {
            document.getElementById('tanggal_dari').value = '<?= date('Y-m-01') ?>';
            document.getElementById('tanggal_sampai').value = '<?= date('Y-m-d') ?>';
            document.getElementById('filter_instansi').value = '';
            document.getElementById('filter_satpam').value = '';
            
            table.search('').columns().search('').draw();
        }

        // Auto hide alerts
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