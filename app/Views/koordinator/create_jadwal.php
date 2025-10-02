<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Jadwal - Koordinator Satpam</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .member-card {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .member-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.2);
        }
        .member-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        .shift-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('koordinator/jadwal') ?>">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Jadwal
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-plus me-2"></i>Buat Jadwal Shift Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" id="scheduleForm">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="regu_number" class="form-label">
                                        <i class="fas fa-users me-1"></i>Regu
                                    </label>
                                    <select class="form-select" id="regu_number" name="regu_number" required>
                                        <option value="">Pilih Regu</option>
                                        <option value="1">Regu 1</option>
                                        <option value="2">Regu 2</option>
                                        <option value="3">Regu 3</option>
                                        <option value="4">Regu 4</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Tanggal
                                    </label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           min="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="shift" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Shift
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shift" id="shift_p" value="P" required>
                                            <label class="form-check-label" for="shift_p">
                                                <strong>Pagi</strong><br>
                                                <small class="text-muted">08:00 - 16:00</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shift" id="shift_s" value="S" required>
                                            <label class="form-check-label" for="shift_s">
                                                <strong>Siang</strong><br>
                                                <small class="text-muted">16:00 - 00:00</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shift" id="shift_m" value="M" required>
                                            <label class="form-check-label" for="shift_m">
                                                <strong>Malam</strong><br>
                                                <small class="text-muted">00:00 - 08:00</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shift" id="shift_l" value="L" required>
                                            <label class="form-check-label" for="shift_l">
                                                <strong>Libur</strong><br>
                                                <small class="text-muted">Tidak bertugas</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="shift-info" class="shift-info" style="display: none;">
                                <!-- Shift info will be populated by JavaScript -->
                            </div>

                            <div id="member-selection" style="display: none;">
                                <h5 class="mb-3">
                                    <i class="fas fa-user-check me-2"></i>Pilih Anggota yang Bertugas
                                </h5>
                                <div id="members-container">
                                    <!-- Members will be populated by JavaScript -->
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan (Opsional)
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Catatan tambahan untuk shift ini"></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('koordinator/jadwal') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>Simpan Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data - in real implementation, this should come from server
        const reguMembers = {
            1: [
                {id: 2, nama: 'DIDIN S'},
                {id: 3, nama: 'SANDI PURNAMA'}, 
                {id: 4, nama: 'DIK DIK'},
                {id: 1, nama: 'DIAN H', is_koordinator: true}
            ],
            2: [
                {id: 5, nama: 'ARI H'},
                {id: 6, nama: 'M. WIJATHMINTA'},
                {id: 7, nama: 'AHMAD RIFA F'},
                {id: 1, nama: 'DIAN H', is_koordinator: true}
            ],
            3: [
                {id: 8, nama: 'AAN'},
                {id: 9, nama: 'ATIF HIDAYAT'},
                {id: 10, nama: 'DADANG S'},
                {id: 1, nama: 'DIAN H', is_koordinator: true}
            ],
            4: [
                {id: 11, nama: 'TAUFIK Z'},
                {id: 12, nama: 'ABDUL AZIZ'},
                {id: 13, nama: 'DIAN EFFENDI'},
                {id: 1, nama: 'DIAN H', is_koordinator: true}
            ]
        };

        const shiftInfo = {
            'P': {
                name: 'Shift Pagi',
                time: '08:00 - 16:00',
                description: 'Shift pagi dengan aktivitas rutin monitoring dan verifikasi tamu',
                color: 'success'
            },
            'S': {
                name: 'Shift Siang', 
                time: '16:00 - 00:00',
                description: 'Shift siang dengan aktivitas monitoring dan keamanan malam',
                color: 'warning'
            },
            'M': {
                name: 'Shift Malam',
                time: '00:00 - 08:00', 
                description: 'Shift malam dengan fokus keamanan dan monitoring kontinyu',
                color: 'info'
            },
            'L': {
                name: 'Libur',
                time: 'Tidak bertugas',
                description: 'Regu tidak bertugas, hanya koordinator yang standby',
                color: 'secondary'
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const reguSelect = document.getElementById('regu_number');
            const shiftRadios = document.querySelectorAll('input[name="shift"]');
            const shiftInfoDiv = document.getElementById('shift-info');
            const memberSelection = document.getElementById('member-selection');
            const membersContainer = document.getElementById('members-container');

            // Handle regu selection
            reguSelect.addEventListener('change', function() {
                updateMemberSelection();
            });

            // Handle shift selection
            shiftRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateShiftInfo(this.value);
                    updateMemberSelection();
                });
            });

            function updateShiftInfo(shiftValue) {
                const info = shiftInfo[shiftValue];
                if (info) {
                    shiftInfoDiv.innerHTML = `
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-${info.color} me-3">${info.name}</span>
                            <strong>${info.time}</strong>
                        </div>
                        <p class="mb-0 text-muted">${info.description}</p>
                    `;
                    shiftInfoDiv.style.display = 'block';
                } else {
                    shiftInfoDiv.style.display = 'none';
                }
            }

            function updateMemberSelection() {
                const reguNumber = reguSelect.value;
                const selectedShift = document.querySelector('input[name="shift"]:checked');

                if (reguNumber && selectedShift) {
                    const members = reguMembers[reguNumber] || [];
                    const isLibur = selectedShift.value === 'L';

                    let html = '<div class="row">';
                    
                    members.forEach(member => {
                        // Untuk shift libur, hanya tampilkan koordinator
                        if (isLibur && !member.is_koordinator) return;

                        const isChecked = !isLibur || member.is_koordinator ? 'checked' : '';
                        const cardClass = member.is_koordinator ? 'border-warning' : '';
                        
                        html += `
                            <div class="col-md-6 mb-2">
                                <div class="member-card p-3 ${cardClass}" onclick="toggleMember(${member.id})">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="anggota_ids[]" value="${member.id}" 
                                               id="member_${member.id}" ${isChecked}>
                                        <label class="form-check-label w-100" for="member_${member.id}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>${member.nama}</strong>
                                                ${member.is_koordinator ? '<span class="badge bg-warning text-dark">Koordinator</span>' : ''}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    
                    if (isLibur) {
                        html = '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Untuk shift libur, hanya koordinator yang standby.</div>' + html;
                    }
                    
                    membersContainer.innerHTML = html;
                    memberSelection.style.display = 'block';
                } else {
                    memberSelection.style.display = 'none';
                }
            }

            // Toggle member selection
            window.toggleMember = function(memberId) {
                const checkbox = document.getElementById(`member_${memberId}`);
                const card = checkbox.closest('.member-card');
                
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            };

            // Form validation
            document.getElementById('scheduleForm').addEventListener('submit', function(e) {
                const selectedMembers = document.querySelectorAll('input[name="anggota_ids[]"]:checked');
                if (selectedMembers.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu anggota untuk bertugas!');
                    return false;
                }
            });

            // Set default date to today
            document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];
        });
    </script>
</body>
</html>