<form action="<?= site_url('satpam/verifikasi_checkout') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($k['id']) ?>">
    <input type="text" name="nama_satpam_checkout" class="form-control mb-2" placeholder="Nama Satpam" required>
    <input type="text" name="jam_checkout" class="form-control mb-2" value="<?= date('Y-m-d H:i:s') ?>" readonly>
    <button type="submit" class="btn btn-primary">Verifikasi Check-Out</button>
</form>
