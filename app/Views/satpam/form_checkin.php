<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Tamu - <?= $user['nama'] ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .verified-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .info-card {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-checkin {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .btn-checkin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .shift-info {
            background: linear-gradient(45deg, #17a2b8, #138496);
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-left: 8px;
        }

        .logged-satpam-badge {
            background: linear-gradient(45deg, #ffc107, #ffca2c);
            color: #212529;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Alert -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Verifikasi Wajah Berhasil!</h5>
                            <p class="mb-0">Silakan lengkapi form check-in di bawah ini.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <div class="card shadow">
                    <div class="card-header card-header-custom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-user-check me-2"></i>
                                Form Check-In Tamu
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="verified-badge">
                                    <i class="fas fa-shield-alt me-1"></i>Terverifikasi
                                </span>
                                <!-- Info Regu dan Shift yang sedang bertugas -->
                                <span class="shift-info">
                                    <i class="fas fa-users me-1"></i>
                                    Regu <?= $current_shift['regu_number'] ?? '1' ?> - 
                                    <?php 
                                    $shift_names = ['P' => 'Pagi', 'S' => 'Siang', 'M' => 'Malam'];
                                    echo $shift_names[$current_shift['shift']] ?? 'Pagi';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="row mb-4">
                            <!-- Informasi Tamu -->
                            <div class="col-md-6">
                                <div class="card info-card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-user me-2"></i>Informasi Tamu
                                        </h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="40%"><strong>Nama:</strong></td>
                                                <td><?= esc($user['nama'] ?? 'Tidak ada data') ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. HP:</strong></td>
                                                <td><?= esc($user['no_hp'] ?? 'Tidak ada data') ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kendaraan:</strong></td>
                                                <td><?= esc($user['no_kendaraan'] ?? 'Tidak ada') ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Keperluan:</strong></td>
                                                <td><?= esc($user['keperluan'] ?? 'Tidak ada data') ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informasi Kunjungan -->
                            <div class="col-md-6">
                                <div class="card info-card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-calendar-alt me-2"></i>Informasi Kunjungan
                                        </h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="50%"><strong>Tanggal Kunjungan:</strong></td>
                                                <td><?= isset($kunjungan['created_at']) ? date('d/m/Y', strtotime($kunjungan['created_at'])) : 'Tidak ada data' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jam Kunjungan:</strong></td>
                                                <td><?= isset($kunjungan['created_at']) ? date('H:i', strtotime($kunjungan['created_at'])) . ' WIB' : 'Tidak ada data' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span class="badge bg-warning">Pending Check-In</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Waktu Verifikasi:</strong></td>
                                                <td><?= date('d/m/Y H:i:s') ?> WIB</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Check-in -->
                        <form action="<?= base_url('satpam/verifikasi_checkin') ?>" method="post" id="checkinForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $kunjungan['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_satpam_checkin" class="form-label">
                                        <i class="fas fa-user-shield me-1"></i>Nama Satpam
                                    </label>
                                    <select class="form-select" id="nama_satpam_checkin" name="nama_satpam_checkin" required>
                                        <option value="">Pilih Satpam yang bertugas</option>
                                        <?php if (!empty($satpam_regu)): ?>
                                            <?php foreach ($satpam_regu as $satpam): ?>
                                                <option value="<?= esc($satpam['nama']) ?>" 
                                                        data-posisi="<?= esc($satpam['posisi']) ?>"
                                                        data-regu="<?= esc($satpam['regu_number']) ?>">
                                                    <?= esc($satpam['nama']) ?> 
                                                    (Regu <?= esc($satpam['regu_number']) ?> - <?= esc($satpam['posisi']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>Tidak ada satpam yang tersedia</option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pilih satpam yang melakukan check-in
                                    </small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="warna_kartu_visitor" class="form-label">
                                        <i class="fas fa-palette me-1"></i>Warna Kartu Visitor
                                    </label>
                                    <select class="form-select" id="warna_kartu_visitor" 
                                            name="warna_kartu_visitor" required>
                                        <option value="">Pilih warna kartu</option>
                                        <option value="Merah">🔴 Merah</option>
                                        <option value="Biru">🔵 Biru</option>
                                        <option value="Hijau">🟢 Hijau</option>
                                        <option value="Kuning">🟡 Kuning</option>
                                        <option value="Putih">⚪ Putih</option>
                                        <option value="Hitam">⚫ Hitam</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_kartu_visitor" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>Nomor Kartu Visitor
                                    </label>
                                    <input type="text" class="form-control" id="nomor_kartu_visitor" 
                                           name="nomor_kartu_visitor" required 
                                           placeholder="Contoh: V001">
                                    <small class="text-muted">Format: V + 3 digit angka (V001, V002, dst)</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-clock me-1"></i>Waktu Check-In
                                    </label>
                                    <input type="text" class="form-control" 
                                           value="<?= date('d/m/Y H:i:s') ?> WIB" readonly>
                                    <small class="text-muted">Waktu akan diisi otomatis saat check-in</small>
                                </div>
                            </div>

                            <!-- Info Satpam yang dipilih -->
                            <div class="row" id="satpamInfo" style="display: none;">
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-user-shield me-2"></i>
                                        <strong>Informasi Satpam:</strong>
                                        <span id="satpamDetail"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="catatan" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                                </label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                          placeholder="Catatan tambahan jika diperlukan (kondisi tamu, barang bawaan, dll)"></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('satpam/checkin') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-checkin" id="submitBtn">
                                    <i class="fas fa-check me-2"></i>Proses Check-In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkinForm');
            const submitBtn = document.getElementById('submitBtn');
            const satpamSelect = document.getElementById('nama_satpam_checkin');
            const satpamInfo = document.getElementById('satpamInfo');
            const satpamDetail = document.getElementById('satpamDetail');
            
            // Show satpam info when selected and trigger initial load
            function updateSatpamInfo() {
                const selectedOption = satpamSelect.options[satpamSelect.selectedIndex];
                if (selectedOption.value) {
                    const posisi = selectedOption.getAttribute('data-posisi');
                    const regu = selectedOption.getAttribute('data-regu');
                    
                    let infoText = `Nama: ${selectedOption.value}`;
                    if (regu) infoText += ` | Regu: ${regu}`;
                    if (posisi) infoText += ` | Posisi: ${posisi}`;
                    
                    satpamDetail.textContent = infoText;
                    satpamInfo.style.display = 'block';
                } else {
                    satpamInfo.style.display = 'none';
                }
            }
            
            satpamSelect.addEventListener('change', updateSatpamInfo);
            
            // Trigger initial load if there's a selected option
            if (satpamSelect.value) {
                updateSatpamInfo();
            }
            
            // Validasi form sebelum submit
            form.addEventListener('submit', function(e) {
                const namaSatpam = document.getElementById('nama_satpam_checkin').value;
                const warnaKartu = document.getElementById('warna_kartu_visitor').value;
                const nomorKartu = document.getElementById('nomor_kartu_visitor').value.trim();
                
                if (!namaSatpam || !warnaKartu || !nomorKartu) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return false;
                }
                
                // Validasi format nomor kartu
                const kartuRegex = /^V\d{3}$/;
                if (!kartuRegex.test(nomorKartu)) {
                    e.preventDefault();
                    alert('Format nomor kartu tidak valid! Gunakan format V001, V002, dst.');
                    document.getElementById('nomor_kartu_visitor').focus();
                    return false;
                }
                
                // Konfirmasi sebelum submit
                const selectedSatpam = satpamSelect.options[satpamSelect.selectedIndex].text;
                if (!confirm(`Apakah Anda yakin ingin melakukan check-in untuk tamu ini?\n\nSatpam: ${selectedSatpam}\nKartu: ${warnaKartu} ${nomorKartu}`)) {
                    e.preventDefault();
                    return false;
                }
                
                // Disable button dan ubah text saat submit
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Check-In...';
                
                // Re-enable form elements untuk memastikan data terkirim
                const formElements = form.querySelectorAll('input, select, textarea');
                formElements.forEach(element => {
                    element.disabled = false;
                });
                submitBtn.disabled = true; // Tetap disable submit button
            });
            
            // Auto format nomor kartu visitor
            document.getElementById('nomor_kartu_visitor').addEventListener('input', function(e) {
                let value = e.target.value.toUpperCase();
                
                // Hapus semua karakter yang bukan huruf atau angka
                value = value.replace(/[^A-Z0-9]/g, '');
                
                // Jika tidak dimulai dengan V, tambahkan V
                if (value && !value.startsWith('V')) {
                    if (value.match(/^\d/)) {
                        value = 'V' + value;
                    }
                }
                
                // Batasi panjang maksimal (V + 3 digit)
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                
                e.target.value = value;
                
                // Validasi real-time
                const isValid = /^V\d{3}$/.test(value);
                if (value.length === 4 && !isValid) {
                    e.target.classList.add('is-invalid');
                } else {
                    e.target.classList.remove('is-invalid');
                }
            });
            
            // Set focus ke field yang tepat
            if (!satpamSelect.value) {
                satpamSelect.focus();
            } else {
                document.getElementById('warna_kartu_visitor').focus();
            }
        });
    </script>
</body>
</html>