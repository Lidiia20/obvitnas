<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-Out Tamu - GITET New Ujung Berung</title>
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

        .checkout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            height: 100%;
            position: relative;
        }

        .checkout-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .checkout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--success-color), var(--info-color), var(--purple-color));
            background-size: 200% 100%;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--success-color) 0%, #146c43 50%, var(--info-color) 100%);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .checkout-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .checkout-header-content {
            position: relative;
            z-index: 2;
        }

        .guest-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--purple-color), var(--pink-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .checkout-body {
            padding: 2rem;
        }

        .guest-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(255,255,255,0.5);
            transform: translateX(5px);
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-right: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #333;
            font-weight: 500;
            font-size: 1rem;
        }

        .duration-badge {
            background: linear-gradient(135deg, var(--info-color), var(--purple-color));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(13, 202, 240, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(5px);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(13, 110, 253, 0.15);
            background: white;
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.75rem;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--danger-color), #b02a37, var(--pink-color));
            border: none;
            border-radius: 25px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-checkout:hover::before {
            left: 100%;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, var(--secondary-color), #5a6268, var(--purple-color));
            border: none;
            border-radius: 25px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .empty-state i {
            font-size: 6rem;
            background: linear-gradient(135deg, var(--secondary-color), var(--purple-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-color), #146c43);
            color: white;
        }

        .alert-info {
            background: linear-gradient(135deg, var(--info-color), #0aa2c0);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-color), #b02a37);
            color: white;
        }

        .status-badge {
            background: linear-gradient(135deg, var(--success-color), var(--info-color));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        }

        .vehicle-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(13, 110, 253, 0.3);
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

        @media (max-width: 768px) {
            .header-section {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .checkout-card {
                margin-bottom: 1rem;
            }

            .floating-action {
                bottom: 1rem;
                right: 1rem;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Additional styles for satpam dropdown to match check-in form */
        .satpam-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(0,0,0,0.05);
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
                        <i class="fas fa-sign-out-alt me-2 text-success"></i>
                        Check-Out Tamu
                    </h2>
                    <p class="text-muted mb-0">Proses check-out untuk tamu yang telah selesai berkunjung</p>
                </div>
                <a href="<?= base_url('satpam/dashboard') ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Error Alert -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($kunjungan)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <h4 class="mb-3">Belum ada tamu yang siap check-out</h4>
                <p class="text-muted mb-4">Tamu yang telah check-in akan muncul di sini untuk proses check-out</p>
                <a href="<?= base_url('satpam/checkin') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Lihat Tamu Pending
                </a>
            </div>
        <?php else: ?>
            <!-- Checkout Cards -->
            <div class="row g-4">
                <?php foreach ($kunjungan as $k): ?>
                    <?php
                    // Hitung durasi kunjungan
                    $jam_masuk = new DateTime($k['jam_masuk']);
                    $sekarang = new DateTime();
                    $durasi = $jam_masuk->diff($sekarang);
                    
                    $durasi_text = '';
                    if ($durasi->h > 0) {
                        $durasi_text .= $durasi->h . ' jam ';
                    }
                    if ($durasi->i > 0) {
                        $durasi_text .= $durasi->i . ' menit';
                    }
                    if (empty($durasi_text)) {
                        $durasi_text = 'Baru saja';
                    }

                    // Ambil inisial nama untuk avatar
                    $nama_array = explode(' ', $k['nama_tamu']);
                    $inisial = '';
                    if (count($nama_array) >= 2) {
                        $inisial = strtoupper(substr($nama_array[0], 0, 1) . substr($nama_array[1], 0, 1));
                    } else {
                        $inisial = strtoupper(substr($k['nama_tamu'], 0, 2));
                    }
                    ?>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout-card">
                            <div class="checkout-header">
                                <div class="checkout-header-content">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="guest-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1 fw-bold"><?= esc($k['nama_tamu']) ?></h5>
                                            <span class="status-badge">
                                                <i class="fas fa-check-circle me-1"></i>
                                                CHECKED IN
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="opacity-75">ID: #<?= $k['id'] ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="checkout-body">
                                <!-- Informasi Tamu -->
                                <div class="guest-info">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Kendaraan</div>
                                            <div class="info-value">
                                                <span class="vehicle-badge">
                                                    <?= esc($k['no_kendaraan']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Keperluan</div>
                                            <div class="info-value"><?= esc($k['keperluan']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Check-in</div>
                                            <div class="info-value">
                                                <?= date('d/m/Y H:i', strtotime($k['jam_masuk'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-hourglass-half"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Durasi</div>
                                            <div class="info-value"><?= $durasi_text ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Checkout -->
                                <form action="<?= base_url('satpam/verifikasi_checkout') ?>" method="post" class="checkout-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($k['id']) ?>">
                                    
                                    <div class="mb-3">
                                        <label for="nama_satpam_<?= $k['id'] ?>" class="form-label">
                                            <i class="fas fa-user-shield me-1"></i>
                                            Nama Satpam
                                        </label>
                                        <select class="form-select" id="nama_satpam_<?= $k['id'] ?>" name="nama_satpam_checkout" required>
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
                                            Pilih satpam yang melakukan check-out
                                        </small>
                                    </div>

                                    <!-- Info Satpam yang dipilih -->
                                    <div class="satpam-info" id="satpamInfo_<?= $k['id'] ?>" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-shield me-2"></i>
                                            <strong>Informasi Satpam:</strong>
                                            <span id="satpamDetail_<?= $k['id'] ?>" class="ms-2"></span>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-checkout">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Proses Check-Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button class="btn floating-btn" onclick="location.reload()" title="Refresh Halaman">
        <i class="fas fa-sync-alt"></i>
    </button>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto refresh setiap 30 detik
setInterval(function() {
    location.reload();
}, 30000);

// Konfirmasi sebelum checkout dan tampilkan info satpam
document.querySelectorAll('.checkout-form').forEach(form => {
    const satpamSelect = form.querySelector('select[name="nama_satpam_checkout"]');
    const satpamInfo = form.querySelector('.satpam-info');
    const satpamDetail = satpamInfo.querySelector('span');
    const submitBtn = form.querySelector('.btn-checkout');

    // Fungsi untuk memperbarui info satpam
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

    // Event listener untuk perubahan dropdown satpam
    satpamSelect.addEventListener('change', updateSatpamInfo);

    // Trigger initial load jika ada opsi yang sudah dipilih
    if (satpamSelect.value) {
        updateSatpamInfo();
    }

    form.addEventListener('submit', function(e) {
        const namaSatpam = satpamSelect.value.trim();
        
        if (!namaSatpam) {
            e.preventDefault();
            alert('Mohon pilih nama satpam!');
            satpamSelect.focus();
            return false;
        }
        
        const selectedSatpam = satpamSelect.options[satpamSelect.selectedIndex].text;
        if (!confirm(`Apakah Anda yakin ingin melakukan check-out untuk tamu ini?\n\nSatpam: ${selectedSatpam}`)) {
            e.preventDefault();
            return false;
        }
        
        // Show loading overlay
        document.getElementById('loadingOverlay').classList.add('show');
        
        // Disable button dan ubah text
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    });
});

// Add entrance animation for cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.checkout-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// Hide loading overlay after page load
window.addEventListener('load', function() {
    document.getElementById('loadingOverlay').classList.remove('show');
});
</script>
</body>
</html>