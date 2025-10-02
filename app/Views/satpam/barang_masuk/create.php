<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #667eea 20%, #764ba2 80%);
            --card-bg: rgba(255, 255, 255, 0.98);
            --section-bg: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9));
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --success-color: #48bb78;
            --success-dark: #38a169;
            --danger-color: #f56565;
            --warning-color: #fbb12d;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border-light: #e2e8f0;
            --border-medium: #cbd5e0;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 30px 60px rgba(0, 0, 0, 0.15);
            --border-radius-sm: 8px;
            --border-radius-md: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            padding: 0;
            margin: 0;
        }
        
        .content {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            min-height: 100vh;
            padding: 1.5rem 0;
        }
        
        .main-card {
            background: var(--card-bg);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: var(--transition-base);
            position: relative;
        }
        
        .main-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            z-index: 1;
        }
        
        .main-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.15);
        }
        
        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2.5rem 2rem;
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
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 4s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { 
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
                opacity: 0;
            }
            50% { 
                transform: translateX(100%) translateY(100%) rotate(45deg);
                opacity: 1;
            }
        }
        
        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
            letter-spacing: -0.025em;
        }
        
        .header-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        
        .card-body {
            padding: 3rem 2.5rem;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), transparent);
        }
        
        .form-section {
            background: var(--section-bg);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--secondary-gradient);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }
        
        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .section-title {
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--primary-color);
            display: inline-flex;
            align-items: center;
            letter-spacing: -0.025em;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 30px;
            height: 3px;
            background: var(--success-color);
            border-radius: 2px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            letter-spacing: 0.025em;
        }
        
        .form-label i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            width: 20px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-control, .form-select {
            border: 2px solid var(--border-light);
            border-radius: var(--border-radius-md);
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
            transition: var(--transition-base);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            line-height: 1.5;
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.12);
            background: rgba(255, 255, 255, 0.98);
            transform: translateY(-1px);
            outline: none;
        }

        .form-control:hover:not(:focus), .form-select:hover:not(:focus) {
            border-color: var(--border-medium);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 0.25rem rgba(245, 101, 101, 0.12);
        }
        
        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
            padding-left: 0.25rem;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .form-check-input {
            margin-right: 0.5rem;
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-check {
            margin-bottom: 0.5rem;
        }
        
        .btn {
            border-radius: 50px;
            padding: 0.875rem 2.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-base);
            border: none;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.025em;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), var(--success-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.25);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, var(--success-dark), #2f855a);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.35);
            color: white;
        }

        .btn-success:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #a0aec0, #718096);
            color: white;
            box-shadow: 0 4px 15px rgba(160, 174, 192, 0.25);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #718096, #4a5568);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(160, 174, 192, 0.35);
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .alert {
            border: none;
            border-radius: var(--border-radius-lg);
            padding: 1.25rem 1.75rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(20px);
            border-left: 4px solid;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 161, 105, 0.05));
            border-left-color: var(--success-color);
            color: #2f855a;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.05));
            border-left-color: var(--danger-color);
            color: #c53030;
        }
        
        .required::after {
            content: '*';
            color: var(--danger-color);
            margin-left: 0.25rem;
            font-weight: 700;
        }
        
        .file-input-wrapper {
            position: relative;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }
        
        .file-input-label {
            display: block;
            padding: 1.5rem 1.25rem;
            border: 2px dashed var(--border-light);
            border-radius: var(--border-radius-md);
            text-align: center;
            cursor: pointer;
            transition: var(--transition-base);
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-secondary);
            font-weight: 500;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        
        .file-input-label:hover {
            border-color: var(--primary-color);
            background: rgba(0, 123, 255, 0.05);
            transform: translateY(-2px);
            color: var(--primary-color);
        }

        .file-input-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .file-input-label:hover::before {
            left: 100%;
        }

        .file-input-label i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .img-thumbnail {
            border: 2px solid var(--border-light);
            border-radius: var(--border-radius-md);
            transition: var(--transition-base);
        }

        .img-thumbnail:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .content {
                padding: 1rem 0;
            }
            
            .card-body { 
                padding: 2rem 1.5rem; 
            }
            
            .card-header {
                padding: 2rem 1.5rem;
            }
            
            .form-section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .btn { 
                width: 100%; 
                margin-bottom: 0.75rem;
                padding: 1rem 2rem;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
            
            .section-title {
                font-size: 1.1rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.justify-content-between .btn {
                margin-bottom: 0;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem 1rem;
            }
            
            .card-header {
                padding: 1.5rem 1rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.875rem 1.5rem;
            }
        }

        .form-submitting {
            pointer-events: none;
            opacity: 0.7;
        }

        .form-submitting .btn {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .form-control:focus,
        .form-select:focus,
        .btn:focus,
        .file-input-label:focus-within {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.25rem rgba(245, 101, 101, 0.25);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
        }

        .form-control.is-valid:focus {
            border-color: var(--success-color);
            box-shadow: 0 0 0 0.25rem rgba(72, 187, 120, 0.12);
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
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

                    <div class="main-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="header-title">
                                    <i class="fas fa-box-open me-2"></i>FORMULIR PENGENDALIAN BARANG MASUK
                                </h5>
                                <span class="header-badge">
                                    <i class="fas fa-clipboard-check me-1"></i>Form Aktif
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <?= form_open_multipart(base_url('satpam/barangmasuk/store'), ['id' => 'barangMasukForm']) ?>
                                <!-- Bagian Pengirim/Pembawa Barang -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-user-tag me-2"></i>Bagian Pengirim/Pembawa Barang
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal" class="form-label required">
                                                <i class="fas fa-calendar-day"></i>Tanggal
                                            </label>
                                            <input type="date" name="tanggal" 
                                                   class="form-control <?= $validation && $validation->hasError('tanggal') ? 'is-invalid' : '' ?>" 
                                                   id="tanggal" value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                                            <?php if ($validation && $validation->hasError('tanggal')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('tanggal') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu" class="form-label required">
                                                <i class="fas fa-clock"></i>Waktu (Jam)
                                            </label>
                                            <input type="time" name="waktu" 
                                                   class="form-control <?= $validation && $validation->hasError('waktu') ? 'is-invalid' : '' ?>" 
                                                   id="waktu" value="<?= old('waktu', date('H:i')) ?>" required>
                                            <?php if ($validation && $validation->hasError('waktu')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('waktu') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_instansi" class="form-label required">
                                            <i class="fas fa-building"></i>Nama Instansi
                                        </label>
                                        <input type="text" name="nama_instansi" 
                                               class="form-control <?= $validation && $validation->hasError('nama_instansi') ? 'is-invalid' : '' ?>" 
                                               id="nama_instansi" placeholder="Masukkan nama instansi" 
                                               value="<?= old('nama_instansi') ?>" required>
                                        <?php if ($validation && $validation->hasError('nama_instansi')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('nama_instansi') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_petugas" class="form-label">
                                                <i class="fas fa-user-tie"></i>Nama Petugas
                                            </label>
                                            <input type="text" name="nama_petugas" class="form-control" 
                                                   id="nama_petugas" placeholder="Masukkan nama petugas" 
                                                   value="<?= old('nama_petugas') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="no_hp" class="form-label">
                                                <i class="fas fa-phone"></i>No. HP
                                            </label>
                                            <input type="text" name="no_hp" class="form-control" 
                                                   id="no_hp" placeholder="Contoh: 081234567890" 
                                                   value="<?= old('no_hp') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengendalian Barang Masuk -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-clipboard-list me-2"></i>Pengendalian Barang Masuk
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_pic_tujuan" class="form-label">
                                            <i class="fas fa-user-tag"></i>1. Nama/PIC Tujuan
                                        </label>
                                        <input type="text" name="nama_pic_tujuan" class="form-control" 
                                               id="nama_pic_tujuan" placeholder="Masukkan nama/PIC tujuan" 
                                               value="<?= old('nama_pic_tujuan') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_surat_pengantar" class="form-label">
                                            <i class="fas fa-envelope-open-text"></i>2. No. Surat Pengantar
                                        </label>
                                        <input type="text" name="no_surat_pengantar" class="form-control" 
                                               id="no_surat_pengantar" placeholder="Masukkan nomor surat pengantar" 
                                               value="<?= old('no_surat_pengantar') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan_barang" class="form-label required">
                                            <i class="fas fa-sticky-note"></i>3. Keterangan Barang
                                        </label>
                                        <textarea name="keterangan_barang" 
                                                  class="form-control <?= $validation && $validation->hasError('keterangan_barang') ? 'is-invalid' : '' ?>" 
                                                  id="keterangan_barang" rows="4" required
                                                  placeholder="Deskripsikan barang yang masuk secara detail..."><?= old('keterangan_barang') ?></textarea>
                                        <?php if ($validation && $validation->hasError('keterangan_barang')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('keterangan_barang') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-check-circle"></i>4. Paket/Barang sudah terkonfirmasi diterima dengan:
                                        </label>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="konfirmasi_nama_pic" class="form-label">
                                                    <i class="fas fa-user-check"></i>Nama/PIC Tujuan
                                                </label>
                                                <input type="text" name="konfirmasi_nama_pic" class="form-control" 
                                                       id="konfirmasi_nama_pic" placeholder="Nama PIC yang menerima" 
                                                       value="<?= old('konfirmasi_nama_pic') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="konfirmasi_jabatan" class="form-label">
                                                    <i class="fas fa-briefcase"></i>Jabatan
                                                </label>
                                                <input type="text" name="konfirmasi_jabatan" class="form-control" 
                                                       id="konfirmasi_jabatan" placeholder="Jabatan PIC yang menerima" 
                                                       value="<?= old('konfirmasi_jabatan') ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-balance-scale"></i>5. Kesesuaian Barang dengan Dokumen
                                        </label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kesesuaian" 
                                                       id="sesuai" value="sesuai" <?= old('kesesuaian') == 'sesuai' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="sesuai">Sesuai</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kesesuaian" 
                                                       id="tidak_sesuai" value="tidak sesuai" <?= old('kesesuaian') == 'tidak sesuai' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="tidak_sesuai">Tidak Sesuai</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-handshake"></i>6. Barang sudah diserahkan ke:
                                        </label>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="serah_nama" class="form-label">
                                                    <i class="fas fa-user-edit"></i>Nama
                                                </label>
                                                <input type="text" name="serah_nama" class="form-control" 
                                                       id="serah_nama" placeholder="Nama penerima" 
                                                       value="<?= old('serah_nama') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="serah_jabatan" class="form-label">
                                                    <i class="fas fa-briefcase"></i>Bidang/Jabatan
                                                </label>
                                                <input type="text" name="serah_jabatan" class="form-control" 
                                                       id="serah_jabatan" placeholder="Bidang/jabatan penerima" 
                                                       value="<?= old('serah_jabatan') ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="serah_tanggal" class="form-label">
                                                <i class="fas fa-calendar-check"></i>Tanggal
                                            </label>
                                            <input type="date" name="serah_tanggal" class="form-control" 
                                                   id="serah_tanggal" value="<?= old('serah_tanggal', date('Y-m-d')) ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="foto_barang" class="form-label required">
                                            <i class="fas fa-camera"></i>Foto Barang Masuk (tempel foto/unggah file)
                                        </label>
                                        <div class="file-input-wrapper">
                                            <input type="file" name="foto_barang" id="foto_barang" 
                                                   accept="image/*" onchange="handleFileSelect(this, 'preview_foto')" required>
                                            <label for="foto_barang" class="file-input-label">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                Pilih atau seret file foto barang
                                                <small class="d-block text-muted mt-1">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                            </label>
                                        </div>
                                        <?php if ($validation && $validation->hasError('foto_barang')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('foto_barang') ?></div>
                                        <?php endif; ?>
                                        <div id="preview_foto" class="mt-2"></div>
                                    </div>
                                </div>

                                <!-- Petugas Satpam Penerima/Pemeriksa -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-user-shield me-2"></i>Petugas Satpam Penerima/Pemeriksa
                                    </div>

                                    <div class="mb-3">
                                        <label for="satpam_pemeriksa" class="form-label required">
                                            <i class="fas fa-user-shield"></i>Nama
                                        </label>
                                        <input type="text" name="satpam_pemeriksa" 
                                               class="form-control <?= $validation && $validation->hasError('satpam_pemeriksa') ? 'is-invalid' : '' ?>" 
                                               id="satpam_pemeriksa" placeholder="Masukkan nama satpam pemeriksa" 
                                               value="<?= old('satpam_pemeriksa') ?>" required>
                                        <?php if ($validation && $validation->hasError('satpam_pemeriksa')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('satpam_pemeriksa') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-secondary px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success px-4" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>Simpan Data
                                    </button>
                                </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleFileSelect(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const label = input.parentElement.querySelector('.file-input-label');
            
            if (!file) {
                resetFileInput(input, previewId);
                return;
            }
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Harap pilih file gambar yang valid (JPG, PNG, GIF)');
                input.value = '';
                resetFileInput(input, previewId);
                return;
            }
            
            // Validate file size (4MB max - sesuai dengan controller)
            if (file.size > 4 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 4MB.');
                input.value = '';
                resetFileInput(input, previewId);
                return;
            }
            
            // Create file reader
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="position-relative d-inline-block mt-3">
                        <img src="${e.target.result}" alt="Preview" 
                             class="img-thumbnail shadow-sm" 
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" 
                                style="transform: translate(50%, -50%); width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeFile('${input.id}', '${previewId}')"
                                title="Hapus foto">
                            <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            ${file.name} (${(file.size / 1024).toFixed(1)} KB)
                        </small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
            
            // Update label
            label.innerHTML = `
                <i class="fas fa-check-circle text-success"></i>
                <strong>File terpilih:</strong> ${file.name}
                <small class="d-block text-muted mt-1">Klik untuk mengganti file</small>
            `;
            label.style.borderColor = 'var(--success-color)';
            label.style.background = 'rgba(72, 187, 120, 0.1)';
            label.style.color = 'var(--success-color)';
        }

        function removeFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.value = '';
            preview.innerHTML = '';
            
            resetFileInput(input, previewId);
        }

        function resetFileInput(input, previewId) {
            const label = input.parentElement.querySelector('.file-input-label');
            label.innerHTML = `
                <i class="fas fa-cloud-upload-alt"></i>
                Pilih atau seret file foto barang
                <small class="d-block text-muted mt-1">Format: JPG, PNG, GIF (Max: 4MB)</small>
            `;
            label.style.borderColor = 'var(--border-light)';
            label.style.background = 'rgba(255, 255, 255, 0.9)';
            label.style.color = 'var(--text-secondary)';
        }

        // Enhanced form submission
        document.getElementById('barangMasukForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const form = this;
            
            // Add form submitting class
            form.classList.add('form-submitting');
            
            // Update button state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan Data...';
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                form.classList.remove('form-submitting');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Data';
                
                // Scroll to first invalid field
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
                
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return false;
            }
        });

        // Phone number formatting
        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Auto format Indonesian phone numbers
            if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            } else if (!value.startsWith('0') && value.length > 0) {
                value = '0' + value;
            }
            
            // Limit length
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            
            e.target.value = value;
            
            // Validate format
            if (value && (value.length < 10 || value.length > 15)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });

        // Real-time validation for required fields
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            });
        });

        // Enhanced file drag and drop
        document.querySelectorAll('.file-input-label').forEach(label => {
            const input = label.parentElement.querySelector('input[type="file"]');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                label.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                label.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight(e) {
                label.style.borderColor = 'var(--primary-color)';
                label.style.background = 'rgba(0, 123, 255, 0.1)';
                label.style.transform = 'scale(1.02)';
            }
            
            function unhighlight(e) {
                label.style.borderColor = 'var(--border-light)';
                label.style.background = 'rgba(255, 255, 255, 0.9)';
                label.style.transform = 'scale(1)';
            }
            
            label.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    input.files = files;
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+S to save (prevent default browser save)
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                document.getElementById('submitBtn').click();
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                if (confirm('Apakah Anda yakin ingin keluar? Data yang belum disimpan akan hilang.')) {
                    window.location.href = '<?= base_url('satpam/barangmasuk') ?>';
                }
            }
        });
    </script>
</body>
</html>