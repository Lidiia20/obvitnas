<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Satpam - Admin GI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-calendar-alt me-2"></i>Jadwal Satpam</h3>
        <a href="<?= base_url('/admin-gi/dashboard') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Jadwal
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Jadwal Baru</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('/admin-gi/simpan-jadwal') ?>" class="row g-3">
                <?= csrf_field() ?>
                <div class="col-md-4">
                    <label for="nama_satpam" class="form-label">Nama Satpam</label>
                    <select name="nama_satpam" id="nama_satpam" class="form-select" required>
                        <option value="">- Pilih Satpam -</option>
                        <?php foreach ($satpamList as $satpam): ?>
                            <?php if (!empty($satpam['nama'])): ?>
                                <option value="<?= $satpam['nama'] ?>"><?= esc($satpam['nama']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="shift" class="form-label">Shift</label>
                    <select name="shift" id="shift" class="form-select" required>
                        <option value="">- Pilih Shift -</option>
                        <option value="P">Pagi (08:00 - 16:00)</option>
                        <option value="S">Siang (16:00 - 00:00)</option>
                        <option value="M">Malam (00:00 - 08:00)</option>
                        <option value="L">Libur</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Tabel Jadwal -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Jadwal Satpam</h5>
            <div>
                <a href="<?= base_url('/admin-gi/export-jadwal-pdf') ?>" class="btn btn-danger btn-sm me-2">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="<?= base_url('/admin-gi/export-jadwal-excel') ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>No</th>
                        <th><i class="fas fa-user me-1"></i>Nama Satpam</th>
                        <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                        <th><i class="fas fa-clock me-1"></i>Shift</th>
                        <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jadwal)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data jadwal</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($jadwal as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= esc($row['nama_satpam']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <?php
                                    $shiftClass = '';
                                    $shiftLabel = '';
                                    switch ($row['shift']) {
                                        case 'P':
                                            $shiftClass = 'badge bg-success';
                                            $shiftLabel = 'Pagi';
                                            break;
                                        case 'S':
                                            $shiftClass = 'badge bg-warning';
                                            $shiftLabel = 'Siang';
                                            break;
                                        case 'M':
                                            $shiftClass = 'badge bg-info';
                                            $shiftLabel = 'Malam';
                                            break;
                                        case 'L':
                                            $shiftClass = 'badge bg-secondary';
                                            $shiftLabel = 'Libur';
                                            break;
                                        default:
                                            $shiftClass = 'badge bg-primary';
                                            $shiftLabel = $row['shift'];
                                    }
                                    ?>
                                    <span class="<?= $shiftClass ?>"><?= $shiftLabel ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin-gi/hapus-jadwal/' . $row['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2"></i>Total Satpam
                    </h5>
                    <h3><?= count($satpamList) ?> orang</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-check me-2"></i>Total Jadwal
                    </h5>
                    <h3><?= count($jadwal) ?> jadwal</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-clock me-2"></i>Shift Aktif
                    </h5>
                    <h3><?= count(array_filter($jadwal, fn($j) => $j['shift'] !== 'Libur')) ?> shift</h3>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>