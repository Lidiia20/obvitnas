<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin GI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #007bff;
            --bs-success: #28a745;
            --bs-warning: #ffc107;
            --bs-info: #17a2b8;
            --bs-danger: #dc3545;
        }
        body {
            background-color: #f8f9fa;
        }
        .card-stats {
            border-left: 5px solid;
            border-radius: .3rem;
        }
        .card-stats.primary { border-left-color: var(--bs-primary); }
        .card-stats.success { border-left-color: var(--bs-success); }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
        }
        .nav-tabs .nav-link {
            color: #495057;
        }
        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-users-cog me-2 text-primary"></i>Manajemen User</h3>
        <a href="<?= base_url('/admin-gi/dashboard') ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-stats primary h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title text-muted mb-1">Total User Terdaftar</h6>
                        <h3 class="mb-0 text-primary"><?= count($usersList ?? []) ?> orang</h3>
                    </div>
                    <div class="text-primary opacity-50">
                        <i class="fas fa-user-plus fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats success h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title text-muted mb-1">Total Satpam Terdaftar</h6>
                        <h3 class="mb-0 text-success"><?= count($satpamList ?? []) ?> orang</h3>
                    </div>
                    <div class="text-success opacity-50">
                        <i class="fas fa-shield-alt fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Data User (<?= count($usersList ?? []) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="satpam-tab" data-bs-toggle="tab" data-bs-target="#satpam" type="button" role="tab">
                <i class="fas fa-shield-alt me-2"></i>Data Satpam (<?= count($satpamList ?? []) ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (!empty($usersList)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Unit & Email</th>
                                        <th scope="col">Tanggal Registrasi</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usersList as $index => $user): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= esc($user['nama']) ?></strong>
                                            <?php if (isset($user['status']) && $user['status'] == 'active'): ?>
                                                <span class="badge bg-success ms-2">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning ms-2">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-building me-1 text-secondary"></i><?= esc($user['asal_unit']) ?><br>
                                            <i class="fas fa-envelope me-1 text-secondary"></i><?= esc($user['email']) ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                        
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data user yang tersedia.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="satpam" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (!empty($satpamList)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama & Email</th>
                                        <th scope="col">Tanggal Bergabung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($satpamList as $index => $s): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= esc($s['nama']) ?></strong><br>
                                            <i class="fas fa-envelope me-1 text-secondary"></i><?= esc($s['email']) ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($s['created_at'])) ?></td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data satpam yang tersedia.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showUserDetail(userId) {
            document.getElementById('userDetailContent').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data user...</p>
                </div>
            `;
            const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
            modal.show();
            
            const url = `<?= base_url('admin-gi/getUserDetail') ?>/${userId}`;
            console.log('Fetching URL:', url); // Log the URL being fetched
            
            fetch(url)
                .then(response => {
                    console.log('Response Status:', response.status); // Log the HTTP status code
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received Data:', data); // Log the received data
                    if (data.status === 'success') {
                        displayUserDetail(data.data);
                    } else {
                        const errorMessage = data.message || 'Gagal memuat data user';
                        document.getElementById('userDetailContent').innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error: ${errorMessage}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('userDetailContent').innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data user. Silakan coba lagi. ${error.message}</div>`;
                });
        }

        function displayUserDetail(user) {
            const content = `
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h6 class="text-primary"><i class="fas fa-id-card-alt me-2"></i>Informasi Pribadi</h6>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted" width="40%">Nama Lengkap:</td>
                                    <td><strong>${user.nama || '-'}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td>${user.email || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. HP:</td>
                                    <td>${user.no_hp || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Asal Unit:</td>
                                    <td><span class="badge bg-primary">${user.asal_unit || '-'}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jabatan:</td>
                                    <td>${user.jabatan || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat:</td>
                                    <td>${user.alamat || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Kendaraan:</td>
                                    <td>${user.no_kendaraan || '-'}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Keperluan:</td>
                                    <td>${user.keperluan || '-'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-clipboard-list me-2"></i>Statistik Kunjungan</h6>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted" width="40%">Total Kunjungan:</td>
                                    <td><span class="badge bg-primary">${user.total_kunjungan ?? '0'}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kunjungan Disetujui:</td>
                                    <td><span class="badge bg-success">${user.kunjungan_approved ?? '0'}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kunjungan Menunggu:</td>
                                    <td><span class="badge bg-warning">${user.kunjungan_pending ?? '0'}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kunjungan Terakhir:</td>
                                    <td>${user.last_visit ? new Date(user.last_visit).toLocaleString('id-ID') : 'Belum pernah berkunjung'}</td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="text-info mt-3"><i class="fas fa-history me-2"></i>Riwayat Kunjungan</h6>
                        <div class="list-group" style="max-height: 250px; overflow-y: auto;">
                            ${user.riwayat_kunjungan && user.riwayat_kunjungan.length > 0 ?
                                user.riwayat_kunjungan.map(kunjungan => `
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                            ${new Date(kunjungan.created_at).toLocaleString('id-ID')}
                                        </span>
                                        <span class="badge bg-secondary">${kunjungan.status.charAt(0).toUpperCase() + kunjungan.status.slice(1)}</span>
                                    </div>
                                `).join('')
                            : `<div class="text-center text-muted p-3">Tidak ada riwayat kunjungan.</div>`}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin-gi/editUser') ?>/${user.id}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit me-1"></i>Edit Data
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id}, '${user.nama}')">
                        <i class="fas fa-trash-alt me-1"></i>Hapus User
                    </button>
                </div>
            `;
            document.getElementById('userDetailContent').innerHTML = content;
        }

        function deleteUser(userId, userName) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}"? Tindakan ini tidak bisa dibatalkan.`)) {
                fetch(`<?= base_url('admin-gi/hapusUser') ?>/${userId}`, {
                    method: 'POST',
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                })
                .then(response => {
                    if (response.ok) {
                        alert('User berhasil dihapus.');
                        location.reload();
                    } else {
                        alert('Gagal menghapus user. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
            }
        }
    </script>
</body>
</html>