<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan GI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-4">
    <h3 class="mb-4">Laporan Kunjungan GI</h3>

    <form method="get">
    <select name="upt_id" onchange="this.form.submit()">
        <option value="">-- Pilih UPT --</option>
        <?php foreach ($upt_list as $upt): ?>
            <option value="<?= $upt['id']; ?>" <?= ($upt_id == $upt['id']) ? 'selected' : ''; ?>>
                <?= $upt['nama_upt']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="ultg_id" onchange="this.form.submit()">
        <option value="">-- Pilih ULTG --</option>
        <?php foreach ($ultg_list as $ultg): ?>
            <option value="<?= $ultg['id']; ?>" <?= ($ultg_id == $ultg['id']) ? 'selected' : ''; ?>>
                <?= $ultg['nama_ultg']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="gi_id" onchange="this.form.submit()">
        <option value="">-- Pilih GI --</option>
        <?php foreach ($gi_list as $gi): ?>
            <option value="<?= $gi['id']; ?>" <?= ($gi_id == $gi['id']) ? 'selected' : ''; ?>>
                <?= $gi['nama_gi']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>


    <?php if (!empty($kunjungan)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Tamu</th>
                    <th>No Kendaraan</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kunjungan as $k): ?>
                    <tr>
                        <td><?= $k['id'] ?></td>
                        <td><?= $k['nama'] ?? '-' ?></td>
                        <td><?= $k['no_kendaraan'] ?></td>
                        <td><?= $k['keperluan'] ?></td>
                        <td><?= ucfirst($k['status']) ?></td>
                        <td><?= $k['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Tidak ada data kunjungan.</div>
    <?php endif; ?>
    <form action="<?= base_url('admink3l/export-excel') ?>" method="get" class="mb-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="upt_id">UPT</label>
            <select name="upt_id" id="upt_id" class="form-control">
                <option value="">Semua</option>
                <?php foreach ($upt_list as $upt): ?>
                    <option value="<?= $upt['id'] ?>"><?= $upt['nama_upt'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="ultg_id">ULTG</label>
            <select name="ultg_id" id="ultg_id" class="form-control">
                <option value="">Semua</option>
                <!-- Akan diisi pakai JS atau PHP -->
            </select>
        </div>
        <div>
            <label for="gi_id">GI</label>
            <select name="gi_id" id="gi_id" class="form-control">
                <option value="">Semua</option>
                <!-- Akan diisi pakai JS atau PHP -->
            </select>
        </div>
        <div>
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control">
        </div>
    </div>
    <div class="mt-4">
        
    </div>
    <form action="<?= base_url('admink3l/export-excel') ?>" method="get" class="d-inline">
    <input type="hidden" name="upt_id" value="<?= $upt_id ?>">
    <input type="hidden" name="ultg_id" value="<?= $ultg_id ?>">
    <input type="hidden" name="gi_id" value="<?= $gi_id ?>">
    <button type="submit" class="btn btn-success">Export Excel</button>
</form>

<form action="<?= base_url('admink3l/export-pdf') ?>" method="get" class="d-inline">
    <input type="hidden" name="upt_id" value="<?= $upt_id ?>">
    <input type="hidden" name="ultg_id" value="<?= $ultg_id ?>">
    <input type="hidden" name="gi_id" value="<?= $gi_id ?>">
    <button type="submit" class="btn btn-danger">Export PDF</button>
</form>

</form>

    <a href="<?= base_url('/admink3l/') ?>" class="btn btn-secondary">Kembali</a>
</body>
</html>
        