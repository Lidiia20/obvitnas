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
            --card-bg: rgba(255, 255, 255, 0.95);
            --section-bg: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            --edit-color: #f5576c;
            --warning-color: #fbb12d;
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
        
        .main-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.3);
        }
        
        .card-header {
            background: linear-gradient(135deg, #f093fb 0%, var(--edit-color) 100%);
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
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            color: var(--edit-color);
            margin-right: 0.5rem;
            width: 18px;
        }
        
        .form-control, .form-select {
            border: 2px solid var(--border-light);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--edit-color);
            box-shadow: 0 0 0 0.25rem rgba(245, 87, 108, 0.15);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #f6ad55);
            color: white;
            box-shadow: 0 4px 15px rgba(251, 177, 45, 0.3);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #f6ad55, #ed8936);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 177, 45, 0.4);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #a0aec0, #718096);
            color: white;
            box-shadow: 0 4px 15px rgba(160, 174, 192, 0.3);
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
            border-left: 4px solid #48bb78;
            color: #2f855a;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.1));
            border-left: 4px solid #f56565;
            color: #c53030;
        }
        
        .form-section {
            background: var(--section-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .section-title {
            color: var(--text-dark);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--edit-color);
            display: inline-flex;
            align-items: center;
        }
        
        .required::after {
            content: '*';
            color: #e53e3e;
            margin-left: 0.25rem;
        }
        
        .file-input-wrapper {
            position: relative;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-input-label {
            display: block;
            padding: 0.75rem 1rem;
            border: 2px dashed var(--border-light);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            color: #4a5568;
        }
        
        .file-input-label:hover {
            border-color: var(--edit-color);
            background: rgba(245, 87, 108, 0.05);
            transform: translateY(-2px);
        }
        
        .current-image {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .current-image img {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .current-image:hover img {
            transform: scale(1.05);
        }
        
        .remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--edit-color);
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.3s ease;
        }
        
        .remove-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
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
                                    <i class="fas fa-edit me-2"></i>Edit Data Barang Masuk
                                </h5>
                                <span class="header-badge">
                                    <i class="fas fa-pencil-alt me-1"></i>Mode Edit
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <?= form_open_multipart('satpam/barangmasuk/edit/' . $barang['id'], ['id' => 'editBarangMasukForm']) ?>
                                <!-- Basic Information Section -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal" class="form-label required">
                                                <i class="fas fa-calendar-day"></i>Tanggal
                                            </label>
                                            <input type="date" name="tanggal" 
                                                   class="form-control <?= $validation && $validation->hasError('tanggal') ? 'is-invalid' : '' ?>" 
                                                   id="tanggal" value="<?= old('tanggal', $barang['tanggal']) ?>" required>
                                            <?php if ($validation && $validation->hasError('tanggal')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('tanggal') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu" class="form-label required">
                                                <i class="fas fa-clock"></i>Waktu
                                            </label>
                                            <input type="time" name="waktu" 
                                                   class="form-control <?= $validation && $validation->hasError('waktu') ? 'is-invalid' : '' ?>" 
                                                   id="waktu" value="<?= old('waktu', $barang['waktu']) ?>" required>
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
                                               id="nama_instansi" value="<?= old('nama_instansi', $barang['nama_instansi']) ?>" required>
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
                                                   id="nama_petugas" value="<?= old('nama_petugas', $barang['nama_petugas']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="no_hp" class="form-label">
                                                <i class="fas fa-phone"></i>Nomor HP
                                            </label>
                                            <input type="text" name="no_hp" class="form-control" 
                                                   id="no_hp" value="<?= old('no_hp', $barang['no_hp']) ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_pic_tujuan" class="form-label">
                                            <i class="fas fa-user-tag"></i>Nama/PIC Tujuan
                                        </label>
                                        <input type="text" name="nama_pic_tujuan" class="form-control" 
                                               id="nama_pic_tujuan" value="<?= old('nama_pic_tujuan', $barang['nama_pic_tujuan']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_surat_pengantar" class="form-label">
                                            <i class="fas fa-envelope-open-text"></i>No. Surat Pengantar
                                        </label>
                                        <input type="text" name="no_surat_pengantar" class="form-control" 
                                               id="no_surat_pengantar" value="<?= old('no_surat_pengantar', $barang['no_surat_pengantar']) ?>">
                                    </div>
                                </div>

                                <!-- Goods Information Section -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-box-open me-2"></i>Informasi Barang
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan_barang" class="form-label required">
                                            <i class="fas fa-sticky-note"></i>Keterangan Barang
                                        </label>
                                        <textarea name="keterangan_barang" 
                                                  class="form-control <?= $validation && $validation->hasError('keterangan_barang') ? 'is-invalid' : '' ?>" 
                                                  id="keterangan_barang" rows="4" required><?= old('keterangan_barang', $barang['keterangan_barang']) ?></textarea>
                                        <?php if ($validation && $validation->hasError('keterangan_barang')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('keterangan_barang') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="foto_barang" class="form-label">
                                            <i class="fas fa-image"></i>Foto Barang
                                        </label>
                                        
                                        <?php if ($barang['foto_barang']): ?>
                                            <div class="current-image" id="currentImage">
                                                <img src="<?= base_url('writable/uploads/barang_masuk/' . $barang['foto_barang']) ?>" 
                                                     alt="Foto Barang" width="150" height="100" style="object-fit: cover;">
                                                <div class="remove-btn" onclick="removeCurrentImage()">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            </div>
                                            <br>
                                        <?php endif; ?>
                                        
                                        <div class="file-input-wrapper">
                                            <input type="file" name="foto_barang" id="foto_barang" 
                                                   accept="image/*" onchange="handleFileSelect(this, 'preview1')">
                                            <label for="foto_barang" class="file-input-label">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                Pilih file gambar baru (opsional)
                                            </label>
                                        </div>
                                        <?php if ($validation && $validation->hasError('foto_barang')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('foto_barang') ?></div>
                                        <?php endif; ?>
                                        <div id="preview1" class="mt-2"></div>
                                    </div>
                                </div>

                                <!-- Confirmation Section -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-check-circle me-2"></i>Konfirmasi & Verifikasi
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="konfirmasi_nama_pic" class="form-label">
                                                <i class="fas fa-user-check"></i>Konfirmasi Nama PIC
                                            </label>
                                            <input type="text" name="konfirmasi_nama_pic" class="form-control" 
                                                   id="konfirmasi_nama_pic" value="<?= old('konfirmasi_nama_pic', $barang['konfirmasi_nama_pic']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="konfirmasi_jabatan" class="form-label">
                                                <i class="fas fa-briefcase"></i>Konfirmasi Jabatan
                                            </label>
                                            <input type="text" name="konfirmasi_jabatan" class="form-control" 
                                                   id="konfirmasi_jabatan" value="<?= old('konfirmasi_jabatan', $barang['konfirmasi_jabatan']) ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="kesesuaian" class="form-label">
                                            <i class="fas fa-balance-scale"></i>Kesesuaian
                                        </label>
                                        <select class="form-select" name="kesesuaian" id="kesesuaian">
                                            <option value="">Pilih Kesesuaian</option>
                                            <option value="sesuai" <?= old('kesesuaian', $barang['kesesuaian']) == 'sesuai' ? 'selected' : '' ?>>Sesuai</option>
                                            <option value="tidak sesuai" <?= old('kesesuaian', $barang['kesesuaian']) == 'tidak sesuai' ? 'selected' : '' ?>>Tidak Sesuai</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Handover Section -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-handshake me-2"></i>Serah Terima
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="serah_nama" class="form-label">
                                                <i class="fas fa-user-edit"></i>Serah Nama
                                            </label>
                                            <input type="text" name="serah_nama" class="form-control" 
                                                   id="serah_nama" value="<?= old('serah_nama', $barang['serah_nama']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="serah_jabatan" class="form-label">
                                                <i class="fas fa-briefcase"></i>Serah Jabatan
                                            </label>
                                            <input type="text" name="serah_jabatan" class="form-control" 
                                                   id="serah_jabatan" value="<?= old('serah_jabatan', $barang['serah_jabatan']) ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="serah_tanggal" class="form-label">
                                            <i class="fas fa-calendar-check"></i>Serah Tanggal
                                        </label>
                                        <input type="date" name="serah_tanggal" class="form-control" 
                                               id="serah_tanggal" value="<?= old('serah_tanggal', $barang['serah_tanggal']) ?>">
                                    </div>
                                </div>

                                <!-- Security Section -->
                                <div class="form-section">
                                    <div class="section-title">
                                        <i class="fas fa-user-shield me-2"></i>Verifikasi Keamanan
                                    </div>

                                    <div class="mb-3">
                                        <label for="satpam_pemeriksa" class="form-label required">
                                            <i class="fas fa-user-shield"></i>Satpam Pemeriksa
                                        </label>
                                        <input type="text" name="satpam_pemeriksa" 
                                               class="form-control <?= $validation && $validation->hasError('satpam_pemeriksa') ? 'is-invalid' : '' ?>" 
                                               id="satpam_pemeriksa" value="<?= old('satpam_pemeriksa', $barang['satpam_pemeriksa']) ?>" required>
                                        <?php if ($validation && $validation->hasError('satpam_pemeriksa')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('satpam_pemeriksa') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-secondary px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning px-4" id="updateBtn">
                                        <i class="fas fa-save me-2"></i>Update Data
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
            
            if (!file) return;
            
            if (file.type.startsWith('image/')) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="position-relative d-inline-block mt-2">
                            <img src="${e.target.result}" alt="Preview" 
                                 class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                    style="transform: translate(50%, -50%); border-radius: 50%; width: 24px; height: 24px; padding: 0;"
                                    onclick="removeFile('${input.id}', '${previewId}')">
                                <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                
                const label = input.parentElement.querySelector('.file-input-label');
                label.innerHTML = `<i class="fas fa-check text-success"></i> ${file.name}`;
                label.style.borderColor = 'var(--edit-color)';
                label.style.background = 'rgba(245, 87, 108, 0.1)';
            } else {
                alert('Harap pilih file gambar yang valid (JPG, PNG, GIF)');
                input.value = '';
            }
        }

        function removeFile(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
            
            const input = document.getElementById(inputId);
            const label = input.parentElement.querySelector('.file-input-label');
            label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Pilih file gambar baru (opsional)';
            label.style.borderColor = 'var(--border-light)';
            label.style.background = 'rgba(255, 255, 255, 0.8)';
        }

        function removeCurrentImage() {
            if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                document.getElementById('currentImage').style.display = 'none';
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_current_image';
                hiddenInput.value = '1';
                document.getElementById('editBarangMasukForm').appendChild(hiddenInput);
            }
        }

        document.getElementById('editBarangMasukForm').addEventListener('submit', function(e) {
            const updateBtn = document.getElementById('updateBtn');
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
        });

        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            } else if (!value.startsWith('0') && value.length > 0) {
                value = '0' + value;
            }
            e.target.value = value;
        });

        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
</body>
</html>