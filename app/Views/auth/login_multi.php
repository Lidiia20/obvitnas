<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pengguna - GITET New Ujung Berung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: url("<?= base_url('uploads/Ujung-Berung.jpg') ?>") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .login-container {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: 5vh auto;
        }

        .card-header {
            background: #0d6efd;
        }

        .role-option {
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-option:hover,
        .role-option.selected {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .login-info {
            display: none;
            margin-top: 15px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <div class="card border-0 shadow">
            <div class="card-header text-center text-white">
                <h4><i class="fas fa-sign-in-alt me-2"></i>Login Pengguna</h4>
                <small>GITET New Ujung Berung</small>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-users me-1"></i>Login Sebagai
                        </label>
                        <div class="role-selection">
                            <div class="role-option" data-role="tamu">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user fa-lg me-3 text-primary"></i>
                                    <div>
                                        <strong>Tamu</strong>
                                        <br><small class="text-muted">Visitor yang berkunjung</small>
                                    </div>
                                </div>
                            </div>
                            <div class="role-option" data-role="satpam">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt fa-lg me-3 text-success"></i>
                                    <div>
                                        <strong>Regu Satpam</strong>
                                        <br><small class="text-muted">Login per regu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="role-option" data-role="koordinator_satpam">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie fa-lg me-3 text-warning"></i>
                                    <div>
                                        <strong>Koordinator Satpam</strong>
                                        <br><small class="text-muted">Pengelola jadwal shift</small>
                                    </div>
                                </div>
                            </div>
                            <div class="role-option" data-role="admin_gi">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-cog fa-lg me-3 text-info"></i>
                                    <div>
                                        <strong>Admin UPT</strong>
                                        <br><small class="text-muted">Administrator UPT Bandung</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="role" id="selectedRole" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-user me-1"></i><span id="email-label">Email/Username</span>
                        </label>
                        <input type="text" name="email" class="form-control" id="email-input"
                               placeholder="Masukkan email atau username..." required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Password
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="loginBtn" disabled>
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center bg-light">
                <div class="d-flex justify-content-center flex-wrap">
                    <a href="<?= site_url('register') ?>" class="me-3">
                        <i class="fas fa-user-plus me-1"></i>Daftar Sebagai Tamu
                    </a>
                    
                </div>
            </div>
        </div>

        <!-- Current Time -->
        <div class="text-center mt-3 text-white">
            <small>
                <i class="fas fa-clock me-1"></i>
                Waktu Sekarang: <span id="current-time"><?= date('d/m/Y H:i:s') ?></span>
            </small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleOptions = document.querySelectorAll('.role-option');
    const selectedRoleInput = document.getElementById('selectedRole');
    const loginBtn = document.getElementById('loginBtn');
    const emailInput = document.getElementById('email-input');
    const emailLabel = document.getElementById('email-label');

    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            roleOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            const role = this.getAttribute('data-role');
            selectedRoleInput.value = role;
            loginBtn.disabled = false;

            switch(role) {
                case 'koordinator_satpam':
                    emailLabel.textContent = 'Email';
                    emailInput.placeholder = 'Masukkan email koordinator...';
                    break;
                case 'satpam':
                    emailLabel.textContent = 'Username';
                    emailInput.placeholder = 'Masukkan username regu (regu1, regu2, dst)...';
                    break;
                default:
                    emailLabel.textContent = 'Email';
                    emailInput.placeholder = 'Masukkan email...';
            }
        });
    });

    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('id-ID', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        document.getElementById('current-time').textContent = timeString;
    }
    setInterval(updateTime, 1000);

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        if (!selectedRoleInput.value) {
            e.preventDefault();
            alert('Silakan pilih role terlebih dahulu!');
        }
    });
});
</script>
</body>
</html>
