<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Barang Keluar - PDF</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1a202c;
            background: #1b4fd4ff;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .header p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #edf2f7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            font-size: 0.9rem;
        }
        th {
            background-color: #edf2f7;
            font-weight: 600;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BARANG KELUAR</h1>
        <p>UPT Bandung</p>
        <p>Periode: <?= date('F Y', mktime(0, 0, 0, $selected_month, 1, $selected_year)) ?></p>
    </div>
    <?php if (!empty($barangKeluar)): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Nama Instansi</th>
                    <th>Nama Petugas</th>
                    <th>No HP</th>
                    <th>Pemilik Barang</th>
                    <th>Pejabat Penerbit Surat</th>
                    <th>No Surat</th>
                    <th>Keterangan Barang</th>
                    <th>Nama Satpam Pemeriksa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangKeluar as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $item['tanggal'] ?? '-' ?></td>
                        <td><?= $item['waktu'] ?? '-' ?></td>
                        <td><?= $item['nama_instansi'] ?? '-' ?></td>
                        <td><?= $item['nama_petugas'] ?? '-' ?></td>
                        <td><?= $item['no_hp'] ?? '-' ?></td>
                        <td><?= $item['pemilik_barang'] ?? '-' ?></td>
                        <td><?= $item['pejabat_penerbit_surat'] ?? '-' ?></td>
                        <td><?= $item['no_surat'] ?? '-' ?></td>
                        <td><?= $item['keterangan_barang'] ?? '-' ?></td>
                        <td><?= $item['satpam_pemeriksa'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #718096;">Tidak ada data barang keluar</p>
    <?php endif; ?>
    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?> WIB</p>
        <p>Sistem Informasi Kelola Barang UPT Bandung</p>
    </div>
</body>
</html>