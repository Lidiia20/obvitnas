<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - GITET New Ujung Berung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-reset {
            max-width: 450px;
            margin: 10vh auto;
            padding: 2rem;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-reset h4 {
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-reset">
        <h4 class="mb-3 text-center">Reset Password</h4>
        <p class="text-muted text-center mb-4">Masukkan nomor WhatsApp Anda untuk menerima password baru</p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('send-reset-password') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor WhatsApp</label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Contoh: 6281234567890" required>
                <div class="form-text">Gunakan format internasional, misal: <strong>628xxxxxxxxxx</strong></div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Kirim Password Baru</button>
            </div>

            <div class="mt-3 text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none">Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
