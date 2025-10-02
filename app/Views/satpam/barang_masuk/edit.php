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
        
        .form-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
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
        
        .required::after {
            content: " *";
            color: #dc3545;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            margin-top: 10px;
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
                                        <i class="fas fa-box-open me-2"></i>
                                        <?= isset($barang) ? 'Edit' : 'Tambah' ?> Data Barang Masuk
                                    </h5>
                                    <small class="opacity-75">Formulir Pengendalian Barang Masuk</small>
                                </div>
                                <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-light">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Validation Errors -->
                            <?php if (isset($validation) && $validation->getErrors()): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Terjadi kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        <?php foreach ($validation->getErrors() as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Flash Messages -->
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?= isset($barang) ? base_url('satpam/barangmasuk/update/' . $barang['id']) : base_url('satpam/barangmasuk/store') ?>" enctype="multipart/form-data">
                                
                                <!-- Bagian Pengirim/Pembawa Barang -->
                                <div class="form-section">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-tag me-2"></i>Bagian Pengirim/Pembawa Barang
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal" class="form-label required">
                                                <i class="fas fa-calendar-day"></i> Tanggal
                                            </label>
                                            <input type="date" name="tanggal" class="form-control" id="tanggal" 
                                                   value="<?= old('tanggal', isset($barang) ? $barang['tanggal'] : date('Y-m-d')) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="waktu" class="form-label required">
                                                <i class="fas fa-clock"></i> Waktu (Jam)
                                            </label>
                                            <input type="time" name="waktu" class="form-control" id="waktu" 
                                                   value="<?= old('waktu', isset($barang) ? $barang['waktu'] : date('H:i')) ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_instansi" class="form-label required">
                                            <i class="fas fa-building"></i> Nama Instansi
                                        </label>
                                        <input type="text" name="nama_instansi" class="form-control" id="nama_instansi" 
                                               placeholder="Masukkan nama instansi" 
                                               value="<?= old('nama_instansi', isset($barang) ? $barang['nama_instansi'] : '') ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_petugas" class="form-label">
                                                <i class="fas fa-user-tie"></i> Nama Petugas
                                            </label>
                                            <input type="text" name="nama_petugas" class="form-control" id="nama_petugas" 
                                                   placeholder="Masukkan nama petugas"
                                                   value="<?= old('nama_petugas', isset($barang) ? $barang['nama_petugas'] : '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="no_hp" class="form-label">
                                                <i class="fas fa-phone"></i> No. HP
                                            </label>
                                            <input type="text" name="no_hp" class="form-control" id="no_hp" 
                                                   placeholder="Contoh: 081234567890"
                                                   value="<?= old('no_hp', isset($barang) ? $barang['no_hp'] : '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengendalian Barang Masuk -->
                                <div class="form-section">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-clipboard-list me-2"></i>Pengendalian Barang Masuk
                                    </h6>

                                    <div class="mb-3">
                                        <label for="nama_pic_tujuan" class="form-label">
                                            <i class="fas fa-user-tag"></i> 1. Nama/PIC Tujuan
                                        </label>
                                        <input type="text" name="nama_pic_tujuan" class="form-control" id="nama_pic_tujuan" 
                                               placeholder="Masukkan nama/PIC tujuan"
                                               value="<?= old('nama_pic_tujuan', isset($barang) ? $barang['nama_pic_tujuan'] : '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_surat_pengantar" class="form-label">
                                            <i class="fas fa-envelope-open-text"></i> 2. No. Surat Pengantar
                                        </label>
                                        <input type="text" name="no_surat_pengantar" class="form-control" id="no_surat_pengantar" 
                                               placeholder="Masukkan nomor surat pengantar"
                                               value="<?= old('no_surat_pengantar', isset($barang) ? $barang['no_surat_pengantar'] : '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan_barang" class="form-label required">
                                            <i class="fas fa-sticky-note"></i> 3. Keterangan Barang
                                        </label>
                                        <textarea name="keterangan_barang" class="form-control" id="keterangan_barang" rows="4" 
                                                  placeholder="Deskripsikan barang yang masuk secara detail..." required><?= old('keterangan_barang', isset($barang) ? $barang['keterangan_barang'] : '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-check-circle"></i> 4. Paket/Barang sudah terkonfirmasi diterima dengan:
                                        </label>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="konfirmasi_nama_pic" class="form-label">
                                                    <i class="fas fa-user-check"></i> Nama/PIC Tujuan
                                                </label>
                                                <input type="text" name="konfirmasi_nama_pic" class="form-control" id="konfirmasi_nama_pic" 
                                                       placeholder="Nama PIC yang menerima"
                                                       value="<?= old('konfirmasi_nama_pic', isset($barang) ? $barang['konfirmasi_nama_pic'] : '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="konfirmasi_jabatan" class="form-label">
                                                    <i class="fas fa-briefcase"></i> Jabatan
                                                </label>
                                                <input type="text" name="konfirmasi_jabatan" class="form-control" id="konfirmasi_jabatan" 
                                                       placeholder="Jabatan PIC yang menerima"
                                                       value="<?= old('konfirmasi_jabatan', isset($barang) ? $barang['konfirmasi_jabatan'] : '') ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-balance-scale"></i> 5. Kesesuaian Barang dengan Dokumen
                                        </label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kesesuaian" id="sesuai" value="sesuai"
                                                       <?= old('kesesuaian', isset($barang) ? $barang['kesesuaian'] : '') == 'sesuai' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="sesuai">Sesuai</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kesesuaian" id="tidak_sesuai" value="tidak_sesuai"
                                                       <?= old('kesesuaian', isset($barang) ? $barang['kesesuaian'] : '') == 'tidak_sesuai' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="tidak_sesuai">Tidak Sesuai</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-handshake"></i> 6. Barang sudah diserahkan ke:
                                        </label>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="serah_nama" class="form-label">
                                                    <i class="fas fa-user-edit"></i> Nama
                                                </label>
                                                <input type="text" name="serah_nama" class="form-control" id="serah_nama" 
                                                       placeholder="Nama penerima"
                                                       value="<?= old('serah_nama', isset($barang) ? $barang['serah_nama'] : '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="serah_jabatan" class="form-label">
                                                    <i class="fas fa-briefcase"></i> Bidang/Jabatan
                                                </label>
                                                <input type="text" name="serah_jabatan" class="form-control" id="serah_jabatan" 
                                                       placeholder="Bidang/jabatan penerima"
                                                       value="<?= old('serah_jabatan', isset($barang) ? $barang['serah_jabatan'] : '') ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="serah_tanggal" class="form-label">
                                                <i class="fas fa-calendar-check"></i> Tanggal
                                            </label>
                                            <input type="date" name="serah_tanggal" class="form-control" id="serah_tanggal"
                                                   value="<?= old('serah_tanggal', isset($barang) ? $barang['serah_tanggal'] : '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="foto_barang" class="form-label">
                                            <i class="fas fa-camera"></i> Foto Barang Masuk
                                        </label>
                                        <input type="file" name="foto_barang" id="foto_barang" class="form-control" 
                                               accept="image/*" onchange="previewImage(this)">
                                        <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 4MB.</small>
                                        
                                        <div id="imagePreview" class="mt-2"></div>
                                        
                                        <?php if (isset($barang) && $barang['foto_barang']): ?>
                                            <div class="mt-2">
                                                <p class="small text-muted">Foto saat ini:</p>
                                                <img src="<?= base_url('satpam/barangmasuk/viewFile/' . $barang['foto_barang']) ?>" 
                                                     alt="Foto Barang" class="image-preview">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Petugas Satpam Penerima/Pemeriksa -->
                                <div class="form-section">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-shield me-2"></i>Petugas Satpam Penerima/Pemeriksa
                                    </h6>

                                    <div class="mb-3">
                                        <label for="satpam_pemeriksa" class="form-label required">
                                            <i class="fas fa-user-shield"></i> Nama
                                        </label>
                                        <input type="text" name="satpam_pemeriksa" class="form-control" id="satpam_pemeriksa" 
                                               placeholder="Masukkan nama satpam pemeriksa" 
                                               value="<?= old('satpam_pemeriksa', isset($barang) ? $barang['satpam_pemeriksa'] : '') ?>" required>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('satpam/barangmasuk') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i><?= isset($barang) ? 'Update Data' : 'Simpan Data' ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Phone number formatting
        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value;
            } else if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            e.target.value = value;
        });

        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="position-relative d-inline-block">
                            <img src="${e.target.result}" alt="Preview" class="image-preview">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                    style="transform: translate(50%, -50%); border-radius: 50%;"
                                    onclick="removePreview()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('foto_barang').value = '';
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