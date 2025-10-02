<!DOCTYPE html>
<html>
<head>
    <title>Kelola Admin GI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Kelola Admin GI</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>GI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admin_gi as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= esc($a['nama']) ?></td>
                    <td><?= esc($a['email']) ?></td>
                    <td><?= esc($a['gi_nama'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('/admink3l/') ?>" class="btn btn-secondary">Kembali</a>
</body>
</html>
