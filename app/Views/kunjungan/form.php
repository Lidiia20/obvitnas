<!DOCTYPE html>
<html>
<head>
    <title>Form Kunjungan - GITET New Ujung Berung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Form Kunjungan Anda</h2>

    <!-- Flash Messages -->
     <?php if (!isset($user) || !$user): ?>
    <div class="alert alert-danger">Data user tidak tersedia.</div>
    <?php else: ?>
    <!-- Form kunjungan lanjut seperti biasa -->
    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
    
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="<?= base_url('/kunjungan/submit') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

        <!-- DATA USER -->
        <div class="mb-3"><label>Nama</label><input type="text" class="form-control" value="<?= esc($user['nama']) ?>" readonly></div>
        <div class="mb-3"><label>Asal Unit</label><input type="text" class="form-control" value="<?= esc($user['asal_unit']) ?> <?= esc($user['asal_unit_lain']) ?>" readonly></div>
        <div class="mb-3"><label>Alamat</label><textarea class="form-control" readonly><?= esc($user['alamat']) ?></textarea></div>
        <div class="mb-3"><label>No HP (WhatsApp)</label><input type="text" class="form-control" value="<?= esc($user['no_hp']) ?>" readonly></div>
        <div class="mb-3"><label>Email</label><input type="email" class="form-control" value="<?= esc($user['email']) ?>" readonly></div>

        <!-- FOTO -->
        <?php if (!empty($user['foto_identitas'])) : ?>
            <div class="mb-3">
                <label>Foto Identitas (KTP)</label><br>
                <img src="<?= base_url('public/uploads/identitas/' . $user['foto_identitas']) ?>" class="img-thumbnail" style="max-height: 200px;">
            </div>
        <?php endif; ?>

        <?php if (!empty($user['foto_selfie'])) : ?>
            <div class="mb-3">
                <label>Foto Selfie</label><br>
                <img src="<?= base_url('public/uploads/selfie/' . $user['foto_selfie']) ?>" class="img-thumbnail" style="max-height: 200px;">
            </div>
        <?php endif; ?>

        <!-- BOLEH DIUBAH -->
        <div class="mb-3">
            <label>No. Kendaraan</label>
            <input type="text" name="no_kendaraan" class="form-control" value="<?= esc($user['no_kendaraan']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Keperluan</label>
            <select name="keperluan" id="keperluan" class="form-control" required>
                <option value="">Pilih Keperluan</option>
                <option value="Rapat" <?= $user['keperluan'] === 'Rapat' ? 'selected' : '' ?>>Rapat</option>
                <option value="Kunjungan" <?= $user['keperluan'] === 'Kunjungan' ? 'selected' : '' ?>>Kunjungan</option>
                <option value="Pekerjaan Kontraktor" <?= $user['keperluan'] === 'Pekerjaan Kontraktor' ? 'selected' : '' ?>>Pekerjaan Kontraktor / Pihak Ketiga</option>
                <option value="Lain-lain" <?= $user['keperluan'] === 'Lain-lain' ? 'selected' : '' ?>>Lain-lain</option>
            </select>
        </div>

        <div class="mb-3" id="detail_keperluan_box" style="display:none;">
            <label id="detail_keperluan_label">Detail Keperluan</label>
            <input type="text" name="detail_keperluan" class="form-control" value="<?= esc($user['detail_keperluan']) ?>">
        </div>

        <div class="mb-3" id="undanganGroup" style="display:none;">
            <label for="file_undangan">Upload Undangan Rapat</label>
            <input type="file" name="file_undangan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-primary">Submit Kunjungan</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleDetailKeperluan() {
        const val = $('#keperluan').val();
        const label = $('#detail_keperluan_label');
        const box = $('#detail_keperluan_box');
        const undangan = $('#undanganGroup');

        if (val === 'Kunjungan') {
            label.text('Tujuan Kunjungan');
            box.show();
            undangan.hide();
        } else if (val === 'Pekerjaan Kontraktor') {
            label.text('Nomor WP');
            box.show();
            undangan.hide();
        } else if (val === 'Lain-lain') {
            label.text('Keterangan Tambahan');
            box.show();
            undangan.hide();
        } else if (val === 'Rapat') {
            label.text('Agenda Rapat');
            box.show();
            undangan.show();
        } else {
            box.hide();
            undangan.hide();
        }
    }

    $('#keperluan').on('change', toggleDetailKeperluan);
    $(document).ready(toggleDetailKeperluan);
</script>
</body>
</html>
