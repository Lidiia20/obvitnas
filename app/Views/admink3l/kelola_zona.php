<!DOCTYPE html>
<html>
<head>
    <title>Kelola Zona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h3 class="mb-4">Kelola Zona UPT / ULTG / GI</h3>

    <!-- Tambah UPT -->
    <form method="post" action="<?= base_url('/admink3l/simpan-upt') ?>" class="mb-4">
        <h5>Tambah UPT</h5>
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="nama" class="form-control" placeholder="Nama UPT" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>

    <!-- Tambah ULTG -->
    <form method="post" action="<?= base_url('/admink3l/simpan-ultg') ?>" class="mb-4">
        <h5>Tambah ULTG</h5>
        <div class="row g-2">
            <div class="col-md-3">
                <select name="upt_id" class="form-select" required>
                    <option value="">Pilih UPT</option>
                    <?php foreach ($upt as $row): ?>
                    <?= $row['nama_upt'] ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="nama" class="form-control" placeholder="Nama ULTG" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>

    <!-- Tambah GI -->
    <form method="post" action="<?= base_url('/admink3l/simpan-gi') ?>" class="mb-4">
        <h5>Tambah GI</h5>
        <div class="row g-2">
            <div class="col-md-3">
                <select name="ultg_id" class="form-select" required>
                    <option value="">Pilih ULTG</option>
                    <?php foreach ($ultg as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= $g['nama_ultg'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="nama" class="form-control" placeholder="Nama GI" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>

    <a href="<?= base_url('/admink3l/') ?>" class="btn btn-secondary">Kembali</a>

</body>
</html>
