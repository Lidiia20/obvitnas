<!-- File: app/Views/satpam/checkin.php -->
<h3>Data Tamu Pending</h3>
<?php foreach ($kunjungan as $k): ?>
    <form action="/satpam/verifikasi_checkin" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $k['id'] ?>">
        <p>Nama: <?= esc($k['nama_tamu']) ?></p>
        <p>No Kendaraan: <?= esc($k['no_kendaraan']) ?></p>
        <p>Keperluan: <?= esc($k['keperluan']) ?></p>
        <input type="text" name="nama_satpam_checkin" placeholder="Nama Satpam" required>
        <input type="text" name="warna_kartu_visitor" placeholder="Warna Kartu" required>
        <input type="text" name="nomor_kartu_visitor" placeholder="Nomor Kartu" required>
        <button type="submit">Check-In</button>
    </form>
    <hr>
<?php endforeach; ?>
