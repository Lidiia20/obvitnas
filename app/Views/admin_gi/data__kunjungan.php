<?php
// File: app/Views/admin_gi/data_kunjungan.php
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan GI - GITET New Ujung Berung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem auto;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .filter-section {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }

        .export-section {
            background: linear-gradient(135deg, var(--success-color), #0f5132);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.3rem rgba(13, 110, 253, 0.15);
        }

        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #0f5132);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #842029);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #495057);
            border: none;
        }

        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .export-stats {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .export-info {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <!-- Header Section -->
            <div class="header-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="fas fa-database me-2"></i>
                            Data Kunjungan GI
                        </h2>
                        <p class="mb-0 opacity-75">Sistem Manajemen Kunjungan GITET New Ujung Berung</p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('d F Y') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Section -->
            <div class="export-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-download me-2"></i>
                            Export Laporan
                        </h5>
                        <p class="mb-0 export-info">Unduh laporan kunjungan dalam format Excel atau PDF</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light" onclick="exportToExcel()" id="btnExcel">
                            <div class="loading-spinner" id="loadingExcel"></div>
                            <i class="fas fa-file-excel me-2 text-success"></i>
                            Export Excel
                        </button>
                        <button class="btn btn-light" onclick="exportToPDF()" id="btnPdf">
                            <div class="loading-spinner" id="loadingPdf"></div>
                            <i class="fas fa-file-pdf me-2 text-danger"></i>
                            Export PDF
                        </button>
                    </div>
                </div>
                
                <div class="export-stats">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="fw-bold" id="exportTotal"><?= count($kunjungan) ?></div>
                            <small>Total Data</small>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold" id="exportApproved">
                                <?= count(array_filter($kunjungan, function($k) { return $k['status'] == 'approved'; })) ?>
                            </div>
                            <small>Check-In</small>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold" id="exportPending">
                                <?= count(array_filter($kunjungan, function($k) { return $k['status'] == 'pending'; })) ?>
                            </div>
                            <small>Pending</small>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold" id="exportCheckout">
                                <?= count(array_filter($kunjungan, function($k) { return $k['status'] == 'checkout'; })) ?>
                            </div>
                            <small>Check-Out</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <h5 class="mb-3">
                    <i class="fas fa-filter me-2 text-primary"></i>
                    Filter Data
                </h5>
                
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar me-1"></i>Bulan
                        </label>
                        <select name="month" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $selected_month ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt me-1"></i>Tahun
                        </label>
                        <select name="year" class="form-select">
                            <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                    
                    <!-- <div class="col-md-3 d-flex align-items-end">
                        <a href="<?= base_url('admin-gi/data-kunjungan') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Reset
                        </a>
                    </div> -->
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="kunjunganTable">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>#</th>
                                <th><i class="fas fa-user me-1"></i>Nama</th>
                                <th><i class="fas fa-car me-1"></i>No Kendaraan</th>
                                <th><i class="fas fa-tasks me-1"></i>Keperluan</th>
                                <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                                <th><i class="fas fa-clock me-1"></i>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kunjungan)): ?>
                                <?php foreach ($kunjungan as $index => $row): ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td>
                                            <div class="fw-semibold"><?= esc($row['nama_tamu'] ?? 'Tidak ada data') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-car me-1"></i>
                                                <?= esc($row['no_kendaraan'] ?? 'Tidak ada data') ?>
                                            </span>
                                        </td>
                                        <td><?= esc($row['keperluan'] ?? 'Tidak ada data') ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            $status_icon = '';
                                            switch ($row['status']) {
                                                case 'approved':
                                                    $status_class = 'badge bg-success';
                                                    $status_icon = 'fas fa-check-circle';
                                                    break;
                                                case 'pending':
                                                    $status_class = 'badge bg-warning text-dark';
                                                    $status_icon = 'fas fa-clock';
                                                    break;
                                                case 'checkout':
                                                    $status_class = 'badge bg-danger';
                                                    $status_icon = 'fas fa-sign-out-alt';
                                                    break;
                                                default:
                                                    $status_class = 'badge bg-secondary';
                                                    $status_icon = 'fas fa-question-circle';
                                            }
                                            ?>
                                            <span class="<?= $status_class ?>">
                                                <i class="<?= $status_icon ?> me-1"></i>
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($row['created_at'])) ?> WIB
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                            <h5>Tidak ada data kunjungan</h5>
                                            <p>Tidak ada data kunjungan untuk periode yang dipilih</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan <?= count($kunjungan) ?> data kunjungan
                </div>
                <a href="<?= base_url('/admin-gi/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Include libraries for export functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Data kunjungan dari PHP
        const kunjunganData = <?= json_encode($kunjungan) ?>;
        const selectedMonth = <?= $selected_month ?>;
        const selectedYear = <?= $selected_year ?>;

        // Function to show loading state
        function showLoading(buttonId, loadingId) {
            document.getElementById(buttonId).disabled = true;
            document.getElementById(loadingId).style.display = 'inline-block';
        }

        // Function to hide loading state
        function hideLoading(buttonId, loadingId) {
            document.getElementById(buttonId).disabled = false;
            document.getElementById(loadingId).style.display = 'none';
        }

        // Export to Excel
        function exportToExcel() {
            showLoading('btnExcel', 'loadingExcel');
            
            setTimeout(() => {
                try {
                    // Prepare data for Excel
                    const excelData = kunjunganData.map((row, index) => ({
                        'No': index + 1,
                        'Nama Tamu': row.nama_tamu || 'Tidak ada data',
                        'No Kendaraan': row.no_kendaraan || 'Tidak ada data',
                        'Keperluan': row.keperluan || 'Tidak ada data',
                        'Status': row.status ? row.status.charAt(0).toUpperCase() + row.status.slice(1) : 'Unknown',
                        'Tanggal': new Date(row.created_at).toLocaleDateString('id-ID'),
                        'Waktu': new Date(row.created_at).toLocaleTimeString('id-ID', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        }) + ' WIB'
                    }));

                    // Create workbook
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.json_to_sheet(excelData);

                    // Set column widths
                    const colWidths = [
                        { width: 5 },   // No
                        { width: 20 },  // Nama
                        { width: 15 },  // No Kendaraan
                        { width: 25 },  // Keperluan
                        { width: 12 },  // Status
                        { width: 12 },  // Tanggal
                        { width: 12 }   // Waktu
                    ];
                    ws['!cols'] = colWidths;

                    // Add worksheet to workbook
                    XLSX.utils.book_append_sheet(wb, ws, 'Data Kunjungan');

                    // Generate filename
                    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const filename = `Laporan_Kunjungan_${monthNames[selectedMonth-1]}_${selectedYear}.xlsx`;

                    // Save file
                    XLSX.writeFile(wb, filename);

                    // Show success message
                    showAlert('success', 'Export Excel berhasil! File telah diunduh.');
                    
                } catch (error) {
                    console.error('Error exporting to Excel:', error);
                    showAlert('danger', 'Gagal export ke Excel. Silakan coba lagi.');
                } finally {
                    hideLoading('btnExcel', 'loadingExcel');
                }
            }, 500);
        }

        // Export to PDF
        function exportToPDF() {
            showLoading('btnPdf', 'loadingPdf');
            
            setTimeout(() => {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation

                    // Add header
                    doc.setFontSize(18);
                    doc.setFont(undefined, 'bold');
                    doc.text('LAPORAN KUNJUNGAN TAMU', 20, 20);
                    
                    doc.setFontSize(14);
                    doc.setFont(undefined, 'normal');
                    doc.text('GITET New Ujung Berung', 20, 30);
                    
                    // Add period info
                    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    doc.setFontSize(12);
                    doc.text(`Periode: ${monthNames[selectedMonth-1]} ${selectedYear}`, 20, 40);
                    doc.text(`Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}`, 20, 47);

                    // Prepare table data
                    const tableData = kunjunganData.map((row, index) => [
                        index + 1,
                        row.nama_tamu || 'Tidak ada data',
                        row.no_kendaraan || 'Tidak ada data',
                        row.keperluan || 'Tidak ada data',
                        row.status ? row.status.charAt(0).toUpperCase() + row.status.slice(1) : 'Unknown',
                        new Date(row.created_at).toLocaleDateString('id-ID'),
                        new Date(row.created_at).toLocaleTimeString('id-ID', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        }) + ' WIB'
                    ]);

                    // Add table
                    doc.autoTable({
                        head: [['No', 'Nama Tamu', 'No Kendaraan', 'Keperluan', 'Status', 'Tanggal', 'Waktu']],
                        body: tableData,
                        startY: 55,
                        theme: 'striped',
                        headStyles: {
                            fillColor: [13, 110, 253],
                            textColor: 255,
                            fontStyle: 'bold'
                        },
                        bodyStyles: {
                            fontSize: 9
                        },
                        columnStyles: {
                            0: { cellWidth: 15, halign: 'center' },
                            1: { cellWidth: 40 },
                            2: { cellWidth: 30 },
                            3: { cellWidth: 50 },
                            4: { cellWidth: 25, halign: 'center' },
                            5: { cellWidth: 25, halign: 'center' },
                            6: { cellWidth: 25, halign: 'center' }
                        },
                        alternateRowStyles: {
                            fillColor: [248, 249, 250]
                        }
                    });

                    // Add summary
                    const finalY = doc.lastAutoTable.finalY + 15;
                    doc.setFontSize(12);
                    doc.setFont(undefined, 'bold');
                    doc.text('RINGKASAN:', 20, finalY);
                    
                    doc.setFont(undefined, 'normal');
                    doc.setFontSize(10);
                    const approved = kunjunganData.filter(k => k.status === 'approved').length;
                    const pending = kunjunganData.filter(k => k.status === 'pending').length;
                    const checkout = kunjunganData.filter(k => k.status === 'checkout').length;
                    
                    doc.text(`Total Kunjungan: ${kunjunganData.length}`, 20, finalY + 8);
                    doc.text(`Check-In: ${approved}`, 20, finalY + 16);
                    doc.text(`Pending: ${pending}`, 20, finalY + 24);
                    doc.text(`Check-Out: ${checkout}`, 20, finalY + 32);

                    // Add footer
                    const pageCount = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= pageCount; i++) {
                        doc.setPage(i);
                        doc.setFontSize(8);
                        doc.text(`Halaman ${i} dari ${pageCount}`, 
                            doc.internal.pageSize.width - 30, 
                            doc.internal.pageSize.height - 10);
                    }

                    // Generate filename and save
                    const filename = `Laporan_Kunjungan_${monthNames[selectedMonth-1]}_${selectedYear}.pdf`;
                    doc.save(filename);

                    // Show success message
                    showAlert('success', 'Export PDF berhasil! File telah diunduh.');
                    
                } catch (error) {
                    console.error('Error exporting to PDF:', error);
                    showAlert('danger', 'Gagal export ke PDF. Silakan coba lagi.');
                } finally {
                    hideLoading('btnPdf', 'loadingPdf');
                }
            }, 500);
        }

        // Show alert message
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Add entrance animations
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.header-section, .export-section, .filter-section, .table-container');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    section.style.transition = 'all 0.6s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Add table row animations
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 100 + 1000);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+E for Excel export
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                exportToExcel();
            }
            // Ctrl+P for PDF export
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                exportToPDF();
            }
        });

        // Add tooltip for keyboard shortcuts
        document.getElementById('btnExcel').title = 'Export ke Excel (Ctrl+E)';
        document.getElementById('btnPdf').title = 'Export ke PDF (Ctrl+P)';
    </script>
</body>
</html>