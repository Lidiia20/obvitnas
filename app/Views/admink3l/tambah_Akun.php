<?php
// === View: admink3l/tambah_akun.php === //
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Tambah Akun</h3>

    <form method="post" action="<?= base_url('/admink3l/tambah-akun') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="satpam">Satpam</option>
                <option value="admin_gi">Admin GI</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Gardu Induk (GI)</label>
            <select name="gi_id" class="form-select" required>
                <option value="">-- Pilih GI --</option>
                <?php foreach ($gi_list as $gi): ?>
                    <option value="<?= $gi['id'] ?>"><?= $gi['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</body>
</html>
