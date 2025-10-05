<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        .badge-approved { background-color: #28a745; }
        .badge-checkout { background-color: #17a2b8; }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-unknown { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KUNJUNGAN GI</h1>
        <p>Periode: <?= !empty($start_date) ? date('d/m/Y', strtotime($start_date)) : 'Semua Waktu' ?> s/d <?= !empty($end_date) ? date('d/m/Y', strtotime($end_date)) : 'Semua Waktu' ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tamu</th>
                <th>No Kendaraan</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Nama Satpam Check-In</th>
                <th>Nama Satpam Check-Out</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($kunjungan)): ?>
                <?php foreach ($kunjungan as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $row['nama_tamu'] ?? '-' ?></td>
                        <td><?= $row['no_kendaraan'] ?? '-' ?></td>
                        <td><?= $row['keperluan'] ?? '-' ?></td>
                        <td>
                            <?php
                                $status = strtolower($row['status'] ?? '');
                                $class = match($status) {
                                    'approved' => 'badge-approved',
                                    'checkout' => 'badge-checkout',
                                    'pending'  => 'badge-pending',
                                    default    => 'badge-unknown'
                                };
                                $label = ucfirst($status ?: '-');
                            ?>
                            <span class="badge <?= $class ?>"><?= $label ?></span>
                        </td>
                        <td><?= !empty($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-' ?></td>
                        <td><?= $row['nama_satpam_checkin'] ?? '-' ?></td>
                        <td><?= $row['nama_satpam_checkout'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data kunjungan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p>Sistem Informasi Kunjungan GI</p>
    </div>
</body>
</html>