<!DOCTYPE html>
<html>
<head>
    <title>Kelola Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h3>Kelola Akun</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- <div class="mb-4">
        <h5>Tambah Akun Baru</h5>
        <form method="post" action="<?= base_url('/admink3l/tambah-akun') ?>">
            <?= csrf_field() ?>
            <div class="row mb-2">
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email (digunakan sebagai username)" required>
                </div>
                <div class="col-md-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select" required>
                        <option value="">Pilih Role</option>
                        <option value="satpam">Satpam</option>
                        <option value="admin_gi">Admin GI</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>GI (Wajib untuk Satpam & Admin GI)</label>
                    <select name="gi_id" class="form-select" required>
                        <option value="">-- Pilih GI --</option>
                        <?php foreach ($gi_list as $gi): ?>
                            <option value="<?= $gi['id'] ?>"><?= esc($gi['nama_gi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Tambah Akun</button>
                </div>
            </div>
        </form>
    </div> -->

    <hr>

    <h5 class="mt-4">Daftar Admin GI</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th>Nama</th> -->
                <th>Email</th>
                <th>GI</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admin_gi as $a): ?>
                <tr>
                    <!-- <td><?= esc($a['nama']) ?></td> -->
                    <td><?= esc($a['email']) ?></td>
                    <td><?= esc($gi_nama[$a['gi_id']] ?? '-') ?></td>
                    <td>
                        <form action="<?= base_url('/admink3l/hapus-akun/' . $a['id']) ?>" method="post" onsubmit="return confirm('Hapus akun ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <h5 class="mt-4">Daftar Satpam</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th>Nama</th> -->
                <th>Email</th>
                <th>GI</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($satpam as $s): ?>
                <tr>
                    <!-- <td><?= esc($s['nama']) ?></td> -->
                    <td><?= esc($s['email']) ?></td>
                    <td><?= esc($gi_nama[$s['gi_id']] ?? '-') ?></td>
                    <td>
                        <form action="<?= base_url('/admink3l/hapus-akun/' . $s['id']) ?>" method="post" onsubmit="return confirm('Hapus akun ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="<?= base_url('/admink3l/') ?>" class="btn btn-secondary">Kembali</a>

</body>
</html>
