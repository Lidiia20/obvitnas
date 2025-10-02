<?= $this->extend('layouts/admin_template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Edit Data Tamu</h3>

    <form method="post" action="<?= base_url('admin/edit_tamu/'.$tamu['id']) ?>">
        <div class="form-group mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= $tamu['nama'] ?>" required>
        </div>

        <div class="form-group mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= $tamu['email'] ?>" required>
        </div>

        <div class="form-group mb-3">
            <label>No Kendaraan</label>
            <input type="text" name="no_kendaraan" class="form-control" value="<?= $tamu['no_kendaraan'] ?>" required>
        </div>

        <div class="form-group mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="checkin" <?= $tamu['status']=='checkin' ? 'selected' : '' ?>>Check-In</option>
                <option value="checkout" <?= $tamu['status']=='checkout' ? 'selected' : '' ?>>Check-Out</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('admin/kelola_user') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
