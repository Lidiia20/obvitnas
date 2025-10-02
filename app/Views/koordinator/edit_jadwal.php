<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - Emergency Mode</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .emergency-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        .member-status {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        .member-available {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        .member-unavailable {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .replacement-card {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('koordinator/jadwal') ?>">
                <i class="fas fa-exclamation-triangle me-2"></i>EMERGENCY MODE
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-clock me-1"></i><span id="current-time"></span>
            </span>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header emergency-header text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Emergency Edit - Regu <?= $jadwal['regu_number'] ?>
                        </h4>
                        <div class="mt-2">
                            <small>
                                Tanggal: <?= date('d/m/Y', strtotime($jadwal['tanggal'])) ?> | 
                                Shift: <?= $jadwal['shift'] ?> (<?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?>)
                            </small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Mode Emergency:</strong> Anda dapat mengubah ketersediaan anggota, menambah pengganti, atau mengubah jadwal shift.
                        </div>

                        <form method="post" id="emergencyForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="update_members">
                            
                            <div class="row">
                                <!-- Current Regu Members -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">
                                        <i class="fas fa-users me-2"></i>Anggota Regu <?= $jadwal['regu_number'] ?>
                                    </h5>
                                    
                                    <?php foreach ($regu_members as $member): ?>
                                        <?php 
                                        $is_available = false;
                                        $current_status = '';
                                        foreach ($current_members as $current) {
                                            if ($current['satpam_id'] == $member['id']) {
                                                $is_available = $current['is_available'];
                                                $current_status = $current['notes'] ?? '';
                                                break;
                                            }
                                        }
                                        ?>
                                        <div class="member-status <?= $is_available ? 'member-available' : 'member-unavailable' ?>">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="available_members[]" value="<?= $member['id'] ?>"
                                                       id="member_<?= $member['id'] ?>"
                                                       <?= $is_available ? 'checked' : '' ?>
                                                       onchange="updateMemberStatus(<?= $member['id'] ?>)">
                                                <label class="form-check-label w-100" for="member_<?= $member['id'] ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong><?= esc($member['nama']) ?></strong>
                                                            <?php if ($member['is_koordinator']): ?>
                                                                <span class="badge bg-warning text-dark ms-2">Koordinator</span>
                                                            <?php endif; ?>
                                                            <div class="small text-muted"><?= $current_status ?></div>
                                                        </div>
                                                        <span class="availability-status">
                                                            <?= $is_available ? 
                                                                '<i class="fas fa-check-circle text-success"></i>' : 
                                                                '<i class="fas fa-times-circle text-danger"></i>' ?>
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Replacement Options -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">
                                        <i class="fas fa-user-plus me-2"></i>Pengganti
                                    </h5>
                                    
                                    <div class="replacement-card p-3 mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Pilih anggota sebagai pengganti sementara
                                        </small>
                                    </div>
                                    
                                    <div id="replacements">
                                        <?php if (empty($replacement_options)): ?>
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Tidak ada satpam tersedia untuk pengganti. Silakan hubungi administrator.
                                            </div>
                                        <?php else: ?>
                                            <?php 
                                            $replacement_count = 0;
                                            foreach ($current_members as $current) {
                                                if (!empty($current['replacement_for'])) {
                                                    $replacement_count++;
                                                }
                                            }
                                            ?>
                                            
                                            <?php if ($replacement_count > 0): ?>
                                                <?php foreach ($current_members as $current): ?>
                                                    <?php if (!empty($current['replacement_for'])): ?>
                                                        <div class="mb-2 replacement-item">
                                                            <div class="input-group">
                                                                <select class="form-select" name="replacement_members[]">
                                                                    <option value="">Pilih pengganti</option>
                                                                    <?php foreach ($replacement_options as $option): ?>
                                                                        <option value="<?= $option['id'] ?>" 
                                                                                <?= $option['id'] == $current['satpam_id'] ? 'selected' : '' ?>>
                                                                            <?= esc($option['nama']) ?> (Regu <?= $option['regu_number'] ?><?php if ($option['is_koordinator']): ?> - Koordinator<?php endif; ?>)
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <button type="button" class="btn btn-outline-danger remove-replacement">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="mb-2 replacement-item">
                                                    <div class="input-group">
                                                        <select class="form-select" name="replacement_members[]">
                                                            <option value="">Pilih pengganti</option>
                                                            <?php foreach ($replacement_options as $option): ?>
                                                                <option value="<?= $option['id'] ?>">
                                                                    <?= esc($option['nama']) ?> (Regu <?= $option['regu_number'] ?><?php if ($option['is_koordinator']): ?> - Koordinator<?php endif; ?>)
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="button" class="btn btn-outline-danger remove-replacement">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <button type="button" class="btn btn-sm btn-success" id="add-replacement" <?= empty($replacement_options) ? 'disabled' : '' ?>>
                                        <i class="fas fa-plus me-1"></i>Tambah Pengganti
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Emergency Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan Emergency <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Wajib diisi: Alasan perubahan jadwal (contoh: Ada yang sakit, emergency keluarga, dll)" required></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('koordinator/jadwal') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <div>
                                    <button type="button" class="btn btn-warning me-2" onclick="previewChanges()">
                                        <i class="fas fa-eye me-1"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-danger" onclick="return confirmEmergencyUpdate()">
                                        <i class="fas fa-save me-2"></i>Update Emergency
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Preview Perubahan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewContent">
                    <!-- Preview content will be generated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" onclick="submitEmergencyForm()">
                        <i class="fas fa-save me-1"></i>Konfirmasi Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update time
            function updateTime() {
                const now = new Date();
                document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateTime, 1000);
            updateTime();

            // Add replacement functionality
            document.getElementById('add-replacement').addEventListener('click', function() {
                const container = document.getElementById('replacements');
                const template = `
                    <div class="mb-2 replacement-item">
                        <div class="input-group">
                            <select class="form-select" name="replacement_members[]">
                                <option value="">Pilih pengganti</option>
                                <?php foreach ($replacement_options as $option): ?>
                                    <option value="<?= $option['id'] ?>">
                                        <?= esc($option['nama']) ?> (Regu <?= $option['regu_number'] ?><?php if ($option['is_koordinator']): ?> - Koordinator<?php endif; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-replacement">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', template);
                updateRemoveButtons();
            });

            // Remove replacement functionality
            function updateRemoveButtons() {
                document.querySelectorAll('.remove-replacement').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.replacement-item').remove();
                    });
                });
            }
            updateRemoveButtons();

            // Update member status visual
            function updateMemberStatus(memberId) {
                const checkbox = document.getElementById(`member_${memberId}`);
                const memberDiv = checkbox.closest('.member-status');
                const statusIcon = memberDiv.querySelector('.availability-status i');
                
                if (checkbox.checked) {
                    memberDiv.classList.remove('member-unavailable');
                    memberDiv.classList.add('member-available');
                    statusIcon.className = 'fas fa-check-circle text-success';
                } else {
                    memberDiv.classList.remove('member-available');
                    memberDiv.classList.add('member-unavailable');
                    statusIcon.className = 'fas fa-times-circle text-danger';
                }
            }

            function previewChanges() {
                const form = document.getElementById('emergencyForm');
                const formData = new FormData(form);
                let previewHtml = '<h6>Perubahan yang akan disimpan:</h6>';

                // Available Members
                previewHtml += '<p><strong>Anggota Tersedia:</strong><br>';
                const availableMembers = formData.getAll('available_members[]');
                if (availableMembers.length > 0) {
                    availableMembers.forEach(id => {
                        const label = document.querySelector(`label[for="member_${id}"] strong`).textContent;
                        previewHtml += `- ${label}<br>`;
                    });
                } else {
                    previewHtml += 'Tidak ada anggota tersedia<br>';
                }

                // Replacement Members
                previewHtml += '<p><strong>Pengganti:</strong><br>';
                const replacementMembers = formData.getAll('replacement_members[]');
                if (replacementMembers.length > 0) {
                    replacementMembers.forEach(id => {
                        if (id) {
                            const select = document.querySelector(`select[name="replacement_members[]"] option[value="${id}"]`);
                            if (select) {
                                previewHtml += `- ${select.textContent}<br>`;
                            }
                        }
                    });
                } else {
                    previewHtml += 'Tidak ada pengganti<br>';
                }

                // Notes
                previewHtml += `<p><strong>Catatan:</strong><br>${formData.get('notes') || 'Tidak ada catatan'}</p>`;

                document.getElementById('previewContent').innerHTML = previewHtml;
                new bootstrap.Modal(document.getElementById('previewModal')).show();
            }

            function submitEmergencyForm() {
                document.getElementById('emergencyForm').submit();
            }

            function confirmEmergencyUpdate() {
                return confirm('Apakah Anda yakin ingin menyimpan perubahan jadwal ini?');
            }
        });
    </script>
</body>
</html>