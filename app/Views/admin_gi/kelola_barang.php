<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang Masuk/Keluar - Admin UPT Bandung</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-primary);
        }

        .main-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            margin: 2rem auto;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 100%;
        }

        .header-section {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(79, 70, 229, 0.1);
        }

        .header-title {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .filter-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.8));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            box-shadow: var(--shadow-md);
        }

        .filter-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-select, .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .export-wrapper {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .export-title {
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .export-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .export-group {
            text-align: center;
        }

        .export-label {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(79, 70, 229, 0.1);
        }

        .export-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-export {
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            flex: 1;
            justify-content: center;
            min-width: 80px;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-export.pdf {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .btn-export.pdf:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        .btn-export.excel {
            background: linear-gradient(135deg, var(--accent-color), #059669);
        }

        .btn-export.excel:hover {
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .table-section {
            margin-bottom: 3rem;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
            border-bottom: 2px solid rgba(79, 70, 229, 0.1);
        }

        .table-title {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .table {
            margin-bottom: 0;
            background: white;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
            text-align: center;
            vertical-align: middle;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 500;
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.02);
        }

        .img-thumbnail {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .img-thumbnail:hover {
            transform: scale(1.1);
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .text-center {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
                border-radius: 16px;
            }

            .filter-card {
                padding: 1.5rem;
            }

            .export-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .export-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-export {
                font-size: 0.8rem;
                padding: 0.7rem 1rem;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .table thead th {
                font-size: 0.8rem;
                padding: 0.75rem 0.5rem;
            }

            .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.9rem;
            }

            .img-thumbnail {
                width: 40px;
                height: 40px;
                object-fit: cover;
            }
        }

        @media (max-width: 576px) {
            .export-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem 0.3rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>

<div class="container-fluid px-3">
    <div class="main-container">
        <!-- HEADER -->
        <div class="header-section">
            <h1 class="header-title">Kelola Barang Masuk/Keluar</h1>
            <p class="header-subtitle">Pantau dan kelola data barang masuk dan keluar dengan mudah</p>
        </div>

        <!-- FILTER SECTION -->
        <div class="filter-card">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Filter Data
            </div>
            <form method="get" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" id="month" class="form-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m) ?>" <?= $selected_month == $m ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Tahun</label>
                    <select name="year" id="year" class="form-select">
                        <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                            <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter Data
                    </button>
                </div>
            </form>
            
            <!-- Export Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="export-wrapper">
                        <h6 class="export-title">
                            <i class="fas fa-download me-2"></i>Export Data
                        </h6>
                        <div class="export-grid">
                            <!-- Barang Masuk -->
                            <div class="export-group">
                                <div class="export-label">Barang Masuk</div>
                                <div class="export-buttons">
                                    <a href="<?= base_url('admin-gi/export-barang-masuk/pdf?month=' . $selected_month . '&year=' . $selected_year) ?>" class="btn-export pdf">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    <a href="<?= base_url('admin-gi/export-barang-masuk/excel?month=' . $selected_month . '&year=' . $selected_year) ?>" class="btn-export excel">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Barang Keluar -->
                            <div class="export-group">
                                <div class="export-label">Barang Keluar</div>
                                <div class="export-buttons">
                                    <a href="<?= base_url('admin-gi/export-barang-keluar/pdf?month=' . $selected_month . '&year=' . $selected_year) ?>" class="btn-export pdf">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    <a href="<?= base_url('admin-gi/export-barang-keluar/excel?month=' . $selected_month . '&year=' . $selected_year) ?>" class="btn-export excel">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL BARANG MASUK -->
        <div class="table-section">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-arrow-down text-success"></i>
                    Data Barang Masuk
                </h2>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Instansi</th>
                            <th>Petugas</th>
                            <th>Keterangan Barang</th>
                            <th>Foto</th>
                            <th>Satpam Pemeriksa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($barangMasuk)): ?>
                            <?php foreach ($barangMasuk as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $item['tanggal'] ?? '-' ?></td>
                                    <td><?= $item['waktu'] ?? '-' ?></td>
                                    <td><?= $item['nama_instansi'] ?? '-' ?></td>
                                    <td><?= $item['nama_petugas'] ?? '-' ?></td>
                                    <td><?= $item['keterangan_barang'] ?? '-' ?></td>
                                    <td>
                                        <?php if ($item['foto_barang']): ?>
                                            <a href="<?= base_url('writable/uploads/barang_masuk/' . $item['foto_barang']) ?>" target="_blank">
                                                <img src="<?= base_url('writable/uploads/barang_masuk/' . $item['foto_barang']) ?>" alt="Foto" width="50" class="img-thumbnail">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['satpam_pemeriksa'] ?? 'Belum Diverifikasi' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <i class="fas fa-inbox fa-2x mb-3" style="opacity: 0.3;"></i>
                                    <div>Tidak ada data barang masuk</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL BARANG KELUAR -->
        <div class="table-section">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-arrow-up text-warning"></i>
                    Data Barang Keluar
                </h2>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Instansi</th>
                            <th>Petugas</th>
                            <th>Pemilik Barang</th>
                            <th>Keterangan</th>
                            <th>Foto Surat</th>
                            <th>Foto Barang</th>
                            <th>Satpam Pemeriksa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($barangKeluar)): ?>
                            <?php foreach ($barangKeluar as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $item['tanggal'] ?? '-' ?></td>
                                    <td><?= $item['waktu'] ?? '-' ?></td>
                                    <td><?= $item['nama_instansi'] ?? '-' ?></td>
                                    <td><?= $item['nama_petugas'] ?? '-' ?></td>
                                    <td><?= $item['pemilik_barang'] ?? '-' ?></td>
                                    <td><?= $item['keterangan_barang'] ?? '-' ?></td>
                                    <td>
                                        <?php if ($item['foto_surat_jalan']): ?>
                                            <a href="<?= base_url('writable/uploads/barang_keluar/' . $item['foto_surat_jalan']) ?>" target="_blank">
                                                <img src="<?= base_url('writable/uploads/barang_keluar/' . $item['foto_surat_jalan']) ?>" alt="Surat" width="50" class="img-thumbnail">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['foto_barang']): ?>
                                            <a href="<?= base_url('writable/uploads/barang_keluar/' . $item['foto_barang']) ?>" target="_blank">
                                                <img src="<?= base_url('writable/uploads/barang_keluar/' . $item['foto_barang']) ?>" alt="Barang" width="50" class="img-thumbnail">
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['satpam_pemeriksa'] ?? 'Belum Diverifikasi' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">
                                    <i class="fas fa-inbox fa-2x mb-3" style="opacity: 0.3;"></i>
                                    <div>Tidak ada data barang keluar</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.img-thumbnail').forEach(img => {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                    cursor: pointer;
                `;
                
                const modalImg = document.createElement('img');
                modalImg.src = this.src;
                modalImg.style.cssText = `
                    max-width: 90%;
                    max-height: 90%;
                    border-radius: 12px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                `;
                
                modal.appendChild(modalImg);
                document.body.appendChild(modal);
                
                modal.addEventListener('click', () => {
                    document.body.removeChild(modal);
                });
            });
        });
    });
</script>
</body>
</html>