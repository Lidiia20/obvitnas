<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Tamu - Sistem Keamanan GI</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            padding: 2rem 0;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(79, 70, 229, 0.1);
            text-align: center;
        }

        .page-title {
            background: linear-gradient(135deg, var(--primary-color), #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.5rem;
            margin: 0;
            letter-spacing: -1px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Alert Enhancement */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-success::before {
            background: linear-gradient(90deg, var(--accent-color), #34d399);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-danger::before {
            background: linear-gradient(90deg, var(--danger-color), #f87171);
        }

        /* Table Enhancement */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }

        .table {
            margin: 0;
            font-size: 0.95rem;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            padding: 1.2rem 1rem;
            border: none;
            position: relative;
        }

        .table thead th:first-child {
            border-radius: 0;
        }

        .table thead th:last-child {
            border-radius: 0;
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            border-color: rgba(0, 0, 0, 0.05);
            vertical-align: middle;
            font-weight: 500;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
            transform: scale(1.01);
        }

        /* Button Enhancement */
        .btn {
            border-radius: 50px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, var(--warning-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
        }

        /* ID Badge */
        .id-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-block;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Action Column */
        .action-column {
            min-width: 200px;
            text-align: center;
        }

        .verification-note {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            padding: 0.4rem 0.8rem;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        /* Back Button Container */
        .back-button-container {
            text-align: center;
            padding-top: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .main-container {
                padding: 1.5rem;
                border-radius: 15px;
            }

            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .table-container {
                border-radius: 15px;
                overflow-x: auto;
            }

            .table {
                min-width: 600px;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .table thead th,
            .table tbody td {
                padding: 1rem 0.8rem;
            }

            .action-column {
                min-width: 180px;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }

        /* Loading Animation for Verification Button */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container animate-fade-up">
            
            <!-- Page Header -->
            <div class="page-header animate-fade-up animate-delay-1">
                <h1 class="page-title">
                    <i class="fas fa-user-check me-3"></i>Check-In Tamu
                </h1>
                <p class="page-subtitle">Kelola dan verifikasi kunjungan tamu dengan sistem keamanan terdepan</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success animate-fade-up animate-delay-2">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger animate-fade-up animate-delay-2">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Table Container -->
            <div class="table-container animate-fade-up animate-delay-3">
                <?php if (empty($kunjungan)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h4>Tidak Ada Tamu Pending</h4>
                        <p>Saat ini tidak ada tamu yang menunggu untuk di-check-in.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                    <th><i class="fas fa-user me-2"></i>Nama Tamu</th>
                                    <th><i class="fas fa-car me-2"></i>No Kendaraan</th>
                                    <th><i class="fas fa-clipboard-list me-2"></i>Keperluan</th>
                                    <th class="action-column"><i class="fas fa-cogs me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kunjungan as $k): ?>
                                    <tr>
                                        <td>
                                            <span class="id-badge">#<?= $k['id'] ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-placeholder me-3">
                                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?= esc($k['nama_tamu']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark px-3 py-2">
                                                <i class="fas fa-car me-1"></i>
                                                <?= esc($k['no_kendaraan']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="keperluan-text">
                                                <?= esc($k['keperluan']) ?>
                                            </div>
                                        </td>
                                        <td class="action-column">
                                            <a href="<?= base_url('satpam/scan-wajah/' . $k['id']) ?>" 
                                               class="btn btn-warning d-inline-flex align-items-center"
                                               onclick="handleVerification(this)">
                                                <i class="fas fa-face-grin-beam me-2"></i>
                                                Verifikasi Wajah
                                            </a>
                                            <div class="verification-note">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Check-in tersedia setelah verifikasi berhasil
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Back Button -->
            <div class="back-button-container animate-fade-up animate-delay-3">
                <a href="<?= base_url('satpam/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Handle verification button loading state
        function handleVerification(button) {
            button.classList.add('btn-loading');
            button.style.pointerEvents = 'none';
            
            // Remove loading state after 3 seconds (in case page doesn't redirect)
            setTimeout(() => {
                button.classList.remove('btn-loading');
                button.style.pointerEvents = 'auto';
            }, 3000);
        }

        // Add smooth scrolling for any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>