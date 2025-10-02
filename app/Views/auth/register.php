<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Tamu - GITET New Ujung Berung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Form Registrasi Tamu</h4>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Menampilkan Error atau Success -->
                    <?php if (isset($validation)) : ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <!-- Form Registrasi -->
                    <form action="<?= base_url('register') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="mb-3">
                            <label>Asal Unit</label>
                            <select name="asal_unit" id="asal_unit" class="form-select" required>
                                <option value="">-- Pilih Asal Unit --</option>
                                <option value="UPT">UPT</option>
                                <option value="ULTG">ULTG</option>
                                <option value="GI">GI</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>

                        <div class="mb-3" id="instansi_box">
                            <label>Nama Instansi / Unit</label>
                            <input type="text" name="instansi" class="form-control" placeholder="Contoh: PLN UPT Bandung" required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" placeholder="Tuliskan alamat Identitas" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>No. HP (WhatsApp)</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 6281234567890" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: email@domain.com" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                        </div>

                        <div class="mb-3">
                            <label>Foto Identitas (KTP)</label>
                            <input type="file" name="foto_identitas" class="form-control" required>
                            <div class="form-text">Unggah dalam format JPG atau PNG.</div>
                        </div>

                        <div class="mb-3">
                            <label>Foto Selfie</label>
                            <input type="file" name="foto_selfie" class="form-control" required>
                            <div class="form-text">Unggah foto selfie yang jelas dengan KTP di tangan.</div>
                        </div>

                        <div class="mb-3">
                            <label>No. Kendaraan</label>
                            <input type="text" name="no_kendaraan" class="form-control" placeholder="Contoh: D 1234 ABC" required>
                        </div>

                        <div class="mb-3">
                            <label>Keperluan</label>
                            <select name="keperluan" id="keperluan" class="form-select" required>
                                <option value="">-- Pilih Keperluan --</option>
                                <option value="Rapat">Rapat</option>
                                <option value="Kunjungan">Kunjungan</option>
                                <option value="Pekerjaan Kontraktor">Pekerjaan Kontraktor / Pihak Ketiga</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>       
                        </div>

                        <div class="mb-3" id="undanganGroup" style="display:none;">
                            <label for="file_undangan">Upload Undangan Rapat</label>
                            <input type="file" name="file_undangan" class="form-control">
                        </div>

                        <div class="mb-3" id="detail_keperluan_box" style="display:none;">
                            <label id="detail_keperluan_label">Detail Keperluan</label>
                            <input type="text" name="detail_keperluan" class="form-control" placeholder="Detail keperluan...">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>Sudah punya akun? <a href="<?= base_url('login') ?>">Login di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fungsi untuk menunjukkan input tambahan berdasarkan pilihan keperluan
    $('#keperluan').on('change', function () {
        const val = $(this).val();
        const label = $('#detail_keperluan_label');
        const box = $('#detail_keperluan_box');
        const input = $('input[name="detail_keperluan"]');

        // Menyesuaikan label dan input keperluan
        if (val === 'Kunjungan') {
            label.text('Tujuan Kunjungan');
        } else if (val === 'Pekerjaan Kontraktor') {
            label.text('Nomor WP');
        } else if (val === 'Lain-lain') {
            label.text('Keterangan Tambahan');
        } else if (val === 'Rapat') {
            label.text('Agenda Rapat');
        }

        box.show();
        input.prop('required', true);

        // Menampilkan form undangan rapat hanya jika pilihan adalah Rapat
        if (val === 'Rapat') {
            $('#undanganGroup').show();
        } else {
            $('#undanganGroup').hide();
        }
    });
</script>

</body>
</html>
