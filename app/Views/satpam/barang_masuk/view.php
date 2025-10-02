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
        
        .detail-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .section-title {
            color: #2d3748;
            font-weight: 700;
            border-bottom: 3px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            color: #007bff;
        }
        
        .detail-row {
            margin-bottom: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        
        .detail-value {
            color: #2d3748;
            font-size: 1rem;
            word-wrap: break-word;
        }
        
        .detail-value.empty {
            color: #6c757d;
            font-style: italic;
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
        
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .status-badge i {
            margin-right: 0.5rem;
        }
        
        .photo-container {
            text-align: center;
            padding: 1rem;
        }
        
        .photo-large {
            max-width: 100%;
            max-height: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .photo-large:hover {
            transform: scale(1.02);
        }
        
        .no-photo {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
        
        .timestamp-info {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        .print-section {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #dee2e6;
        }
        
        @media print {
            body {
                background: white !important;
            }
           
            .btn, .print-section {
                display: none !important;
            }
           
            .main-card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
           
            .card-header {
                background: #007bff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
       
        @media (max-width: 768px) {
            .detail-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }
           
            .section-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="main-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-file-alt me-2"></i>Detail Barang Masuk
                                    </h5>
                                    <small class="opacity-75">ID: <?= $barang['id'] ?> | Tercatat: <?= date('d/m/Y H:i', strtotime($barang['created_at'])) ?></small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="window.print()" class="btn btn-light">
                                        <i class="fas fa-print me-2"></i>Print
                                    </button>
                                    <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-light">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Bagian Pengirim/Pembawa Barang -->
                            <div class="detail-section">
                                <h6 class="section-title">
                                    <i class="fas fa-user-tag"></i>Data Pengirim/Pembawa Barang
                                </h6>
                               
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-day me-1"></i> Tanggal
                                            </div>
                                            <div class="detail-value">
                                                <?= date('d F Y', strtotime($barang['tanggal'])) ?>
                                                <small class="text-muted">(<?= date('l', strtotime($barang['tanggal'])) ?>)</small>
                                            </div>
                                        </div>
                                       
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-clock me-1"></i> Waktu
                                            </div>
                                            <div class="detail-value">
                                                <?= date('H:i', strtotime($barang['waktu'])) ?> WIB
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-building me-1"></i> Nama Instansi
                                            </div>
                                            <div class="detail-value">
                                                <strong><?= esc($barang['nama_instansi']) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-user-tie me-1"></i> Nama Petugas
                                            </div>
                                            <div class="detail-value <?= empty($barang['nama_petugas']) ? 'empty' : '' ?>">
                                                <?= esc($barang['nama_petugas']) ?: 'Tidak diisi' ?>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-phone me-1"></i> No. HP
                                            </div>
                                            <div class="detail-value <?= empty($barang['no_hp']) ? 'empty' : '' ?>">
                                                <?= esc($barang['no_hp']) ?: 'Tidak diisi' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pengendalian Barang Masuk -->
                            <div class="detail-section">
                                <h6 class="section-title">
                                    <i class="fas fa-clipboard-list"></i>Pengendalian Barang Masuk
                                </h6>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-user-tag me-1"></i> 1. Nama/PIC Tujuan
                                    </div>
                                    <div class="detail-value <?= empty($barang['nama_pic_tujuan']) ? 'empty' : '' ?>">
                                        <?= esc($barang['nama_pic_tujuan']) ?: 'Tidak diisi' ?>
                                    </div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-envelope-open-text me-1"></i> 2. No. Surat Pengantar
                                    </div>
                                    <div class="detail-value <?= empty($barang['no_surat_pengantar']) ? 'empty' : '' ?>">
                                        <?= esc($barang['no_surat_pengantar']) ?: 'Tidak diisi' ?>
                                    </div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-sticky-note me-1"></i> 3. Keterangan Barang
                                    </div>
                                    <div class="detail-value">
                                        <div style="white-space: pre-wrap;"><?= esc($barang['keterangan_barang']) ?></div>
                                    </div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-check-circle me-1"></i> 4. Paket/Barang Terkonfirmasi Diterima
                                    </div>
                                    <div class="detail-value">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama/PIC:</strong><br>
                                                <span class="<?= empty($barang['konfirmasi_nama_pic']) ? 'empty' : '' ?>">
                                                    <?= esc($barang['konfirmasi_nama_pic']) ?: 'Tidak diisi' ?>
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Jabatan:</strong><br>
                                                <span class="<?= empty($barang['konfirmasi_jabatan']) ? 'empty' : '' ?>">
                                                    <?= esc($barang['konfirmasi_jabatan']) ?: 'Tidak diisi' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-user-tag me-1"></i> 5. Nama/PIC Tujuan
                                    </div>
                                    <div class="detail-value <?= empty($barang['nama_pic_tujuan']) ? 'empty' : '' ?>">
                                        <?= esc($barang['nama_pic_tujuan']) ?: 'Tidak diisi' ?>
                                    </div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-handshake me-1"></i> 6. Barang Diserahkan Kepada
                                    </div>
                                    <div class="detail-value">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Nama:</strong><br>
                                                <span class="<?= empty($barang['serah_nama']) ? 'empty' : '' ?>">
                                                    <?= esc($barang['serah_nama']) ?: 'Tidak diisi' ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Bidang/Jabatan:</strong><br>
                                                <span class="<?= empty($barang['serah_jabatan']) ? 'empty' : '' ?>">
                                                    <?= esc($barang['serah_jabatan']) ?: 'Tidak diisi' ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Tanggal:</strong><br>
                                                <span class="<?= empty($barang['serah_tanggal']) ? 'empty' : '' ?>">
                                                    <?= $barang['serah_tanggal'] ? date('d F Y', strtotime($barang['serah_tanggal'])) : 'Tidak diisi' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Foto Barang -->
                            <div class="detail-section">
                                <h6 class="section-title">
                                    <i class="fas fa-camera"></i>Dokumentasi Foto Barang
                                </h6>
                               
                                <div class="photo-container">
                                    <?php if ($barang['foto_barang']): ?>
                                        <img src="<?= base_url('public/uploads/barang_masuk/' . $barang['foto_barang']) ?>"
                                             alt="Foto Barang Masuk" class="photo-large"
                                             onclick="showImageModal('<?= base_url('public/uploads/barang_masuk/' . $barang['foto_barang']) ?>')">
                                        <div class="mt-2">
                                            <small class="text-muted">Klik foto untuk memperbesar</small>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-photo">
                                            <i class="fas fa-image fa-3x mb-3 text-muted"></i>
                                            <p class="mb-0">Tidak ada foto yang diupload</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Petugas Satpam -->
                            <div class="detail-section">
                                <h6 class="section-title">
                                    <i class="fas fa-user-shield"></i>Petugas Satpam Penerima/Pemeriksa
                                </h6>
                               
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-user-shield me-1"></i> Nama Petugas
                                    </div>
                                    <div class="detail-value">
                                        <strong><?= esc($barang['satpam_pemeriksa']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <!-- Informasi Sistem -->
                            <div class="timestamp-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-plus-circle me-1"></i> Dicatat pada:</strong><br>
                                        <?= date('d F Y, H:i:s', strtotime($barang['created_at'])) ?> WIB
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-edit me-1"></i> Terakhir diperbarui:</strong><br>
                                        <?= date('d F Y, H:i:s', strtotime($barang['updated_at'])) ?> WIB
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small><i class="fas fa-lock me-1"></i> Data ini bersifat permanen dan tidak dapat diubah untuk menjaga integritas audit trail keamanan.</small>
                                </div>
                            </div>
                            <!-- Print Section -->
                            <div class="print-section text-center">
                                <button onclick="window.print()" class="btn btn-primary me-2">
                                    <i class="fas fa-print me-2"></i>Print Laporan
                                </button>
                                <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-image me-2"></i>Foto Barang Masuk - Detail
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" alt="Foto Barang" class="img-fluid" style="max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <small class="text-muted">ID: <?= $barang['id'] ?> | <?= esc($barang['nama_instansi']) ?> | <?= date('d/m/Y H:i', strtotime($barang['created_at'])) ?></small>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
        // Enhanced print functionality
        window.addEventListener('beforeprint', function() {
            document.title = 'Detail Barang Masuk - ID ' + <?= $barang['id'] ?> + ' - ' + '<?= esc($barang['nama_instansi']) ?>';
        });
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P for print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Escape to go back
            if (e.key === 'Escape') {
                window.location.href = '<?= base_url('satpam/barangmasuk') ?>';
            }
        });
    </script>
</body>
</html>