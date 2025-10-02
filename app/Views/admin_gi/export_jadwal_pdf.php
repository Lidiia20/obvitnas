<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Satpam - PDF</title>
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
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>JADWAL SATPAM</h1>
        <p>GITET New Ujung Berung</p>
        <p>Periode: <?= date('F Y') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satpam</th>
                <th>Tanggal</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jadwal)): ?>
                <?php foreach ($jadwal as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $row['nama_satpam'] ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td>
                            <?php
                            $shiftClass = '';
                            $shiftLabel = '';
                            switch ($row['shift']) {
                                case 'P':
                                    $shiftClass = 'badge-success';
                                    $shiftLabel = 'Pagi';
                                    break;
                                case 'S':
                                    $shiftClass = 'badge-warning';
                                    $shiftLabel = 'Siang';
                                    break;
                                case 'M':
                                    $shiftClass = 'badge-info';
                                    $shiftLabel = 'Malam';
                                    break;
                                case 'L':
                                    $shiftClass = 'badge-secondary';
                                    $shiftLabel = 'Libur';
                                    break;
                                default:
                                    $shiftClass = 'badge-secondary';
                                    $shiftLabel = $row['shift'];
                            }
                            ?>
                            <span class="badge <?= $shiftClass ?>"><?= $shiftLabel ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data jadwal</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        <p>Sistem Informasi Kunjungan GITET New Ujung Berung</p>
    </div>
</body>
</html> 