                <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal - Koordinator Satpam</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .schedule-table th { background-color: #f8f9fa; }
        .shift-badge { font-size: 0.8rem; }
        .status-normal { color: #28a745; }
        .status-edited { color: #ffc107; }
        .status-emergency { color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('koordinator/dashboard') ?>">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user-tie me-1"></i>DIAN H
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Kelola Jadwal Shift</h2>
                        <p class="text-muted mb-0">Manajemen jadwal shift untuk semua regu satpam</p>
                    </div>
                    <div>
                        <!-- <button class="btn btn-success me-2" onclick="exportSchedule()">
                            <i class="fas fa-download me-1"></i>Export
                        </button> -->
                        <a href="<?= base_url('koordinator/create-jadwal') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Buat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Regu</label>
                        <select class="form-select" id="filterRegu">
                            <option value="">Semua Regu</option>
                            <option value="1">Regu 1</option>
                            <option value="2">Regu 2</option>
                            <option value="3">Regu 3</option>
                            <option value="4">Regu 4</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="normal">Normal</option>
                            <option value="edited">Edited</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" id="filterDateFrom" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="filterDateTo" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Shift</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="scheduleTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Regu</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Jam</th>
                                <th>Anggota Bertugas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jadwal)): ?>
                                <?php foreach ($jadwal as $j): ?>
                                    <tr>
                                        <td><?= $j['id'] ?></td>
                                        <td>
                                            <span class="badge bg-primary">Regu <?= $j['regu_number'] ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($j['tanggal'])) ?></td>
                                        <td>
                                            <?php 
                                            $shift_info = [
                                                'P' => ['label' => 'Pagi', 'class' => 'success'],
                                                'S' => ['label' => 'Siang', 'class' => 'warning'],
                                                'M' => ['label' => 'Malam', 'class' => 'info'],
                                                'L' => ['label' => 'Libur', 'class' => 'secondary']
                                            ];
                                            $info = $shift_info[$j['shift']] ?? ['label' => $j['shift'], 'class' => 'dark'];
                                            ?>
                                            <span class="badge bg-<?= $info['class'] ?> shift-badge">
                                                <?= $info['label'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <?= date('H:i', strtotime($j['jam_mulai'])) ?> - 
                                                <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if (!empty($j['anggota_names'])): ?>
                                                <small><?= $j['anggota_names'] ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $j['status'] === 'normal' ? 'success' : ($j['status'] === 'edited' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($j['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- <button class="btn btn-outline-info" onclick="viewDetails(<?= $j['id'] ?>)" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button> -->
                                                <a href="<?= base_url('koordinator/edit-jadwal/' . $j['id']) ?>" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger" onclick="deleteSchedule(<?= $j['id'] ?>)" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-warning me-2"></i>
                        Tindakan ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#scheduleTable').DataTable({
                order: [[2, 'desc'], [1, 'asc']], // Sort by date desc, then regu asc
                pageLength: 25,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            // Filter functions
            $('#filterRegu, #filterStatus, #filterDateFrom, #filterDateTo').on('change', function() {
                table.draw();
            });

            // Custom filter function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const regu = $('#filterRegu').val();
                const status = $('#filterStatus').val();
                const dateFrom = $('#filterDateFrom').val();
                const dateTo = $('#filterDateTo').val();
                
                // Check regu filter
                if (regu && !data[1].includes(regu)) return false;
                
                // Check status filter  
                if (status && !data[6].toLowerCase().includes(status)) return false;
                
                // Check date range
                const rowDate = data[2].split('/').reverse().join('-'); // Convert dd/mm/yyyy to yyyy-mm-dd
                if (dateFrom && rowDate < dateFrom) return false;
                if (dateTo && rowDate > dateTo) return false;
                
                return true;
            });
        });

        // View details function
        function viewDetails(jadwalId) {
            window.open(`<?= base_url('koordinator/shift-details/') ?>${jadwalId}`, '_blank');
        }

        // Delete schedule function
        let deleteId = null;
        function deleteSchedule(jadwalId) {
            deleteId = jadwalId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteId) {
                window.location.href = `<?= base_url('koordinator/delete-jadwal/') ?>${deleteId}`;
            }
        });

        // Export function
        function exportSchedule() {
            const format = prompt("Pilih format export:\n1. Excel\n2. PDF\nMasukkan angka (1 atau 2):");
            if (format === '1') {
                window.open('<?= base_url('koordinator/export-excel') ?>', '_blank');
            } else if (format === '2') {
                window.open('<?= base_url('koordinator/export-pdf') ?>', '_blank');
            }
        }
    </script>
</body>
</html>