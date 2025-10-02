<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="text-end">
        <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a>
    </div>

    <h2>Selamat Datang, <?= session('nama') ?></h2>

    <div class="card mt-4">
        <div class="card-body">
            <p>Anda berhasil login sebagai <strong><?= session('role') ?></strong>.</p>
            <p>Silakan lanjutkan ke proses kunjungan atau pantau status kunjungan Anda.</p>

            <a href="<?= base_url('/kunjungan/form') ?>" class="btn btn-primary">Isi Formulir Kunjungan</a>
        </div>
    </div>

</body>
</html>
