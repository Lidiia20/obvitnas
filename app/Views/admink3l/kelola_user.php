<!DOCTYPE html>
<html>
<head>
    <title>Kelola Tamu - Admin K3L</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h3>Kelola Tamu</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Unit</th>
           
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <?php if ($u['role'] === 'tamu'): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= esc($u['nama']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><?= esc($u['no_hp'] ?? '-') ?></td>
                        <td><?= esc($u['unit'] ?? '-') ?></td>
                        
                        <td>
                            <a href="<?= base_url('/admink3l/hapus-user/' . $u['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= base_url('/admink3l') ?>" class="btn btn-secondary">Kembali</a>

</body>
</html>
