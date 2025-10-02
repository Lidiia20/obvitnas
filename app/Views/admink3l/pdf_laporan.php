<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan</title>
    <style>
        table {
            width: 100%; border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000; padding: 6px;
        }
    </style>
</head>
<body>
    <h2>Laporan Kunjungan</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Tamu</th>
                <th>Keperluan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kunjungan as $k): ?>
            <tr>
                <td><?= $k['nama_tamu'] ?? '-' ?></td>
                <td><?= $k['keperluan'] ?? '-' ?></td>
                <td><?= $k['created_at'] ?? '-' ?></td>
                <td><?= $k['status'] ?? '-' ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
