<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Masuk/Keluar - PDF</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BARANG MASUK/KELUAR</h1>
        <p>UPT Bandung</p>
        <p>Periode: <?= date('F Y', mktime(0, 0, 0, $selected_month, 1, $selected_year)) ?></p>
    </div>

    <?php if (!empty($barangMasuk)): ?>
        <h2>Barang Masuk</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Instansi</th>
                    <th>Petugas</th>
                    <th>No HP</th>
                    <th>PIC Tujuan</th>
                    <th>No Surat Pengantar</th>
                    <th>Keterangan Barang</th>
                    <th>Kesesuaian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangMasuk as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $item['tanggal'] ?? '-' ?></td>
                        <td><?= $item['nama_instansi'] ?? '-' ?></td>
                        <td><?= $item['nama_petugas'] ?? '-' ?></td>
                        <td><?= $item['no_hp'] ?? '-' ?></td>
                        <td><?= $item['nama_pic_tujuan'] ?? '-' ?></td>
                        <td><?= $item['no_surat_pengantar'] ?? '-' ?></td>
                        <td><?= $item['keterangan_barang'] ?? '-' ?></td>
                        <td><?= ucfirst($item['kesesuaian'] ?? 'Belum Dicek') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($barangKeluar)): ?>
        <h2>Barang Keluar</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Instansi</th>
                    <th>Petugas</th>
                    <th>No HP</th>
                    <th>Pemilik Barang</th>
                    <th>No Surat</th>
                    <th>Keterangan Barang</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangKeluar as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $item['tanggal'] ?? '-' ?></td>
                        <td><?= $item['nama_instansi'] ?? '-' ?></td>
                        <td><?= $item['nama_petugas'] ?? '-' ?></td>
                        <td><?= $item['no_hp'] ?? '-' ?></td>
                        <td><?= $item['pemilik_barang'] ?? '-' ?></td>
                        <td><?= $item['no_surat'] ?? '-' ?></td>
                        <td><?= $item['keterangan_barang'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (empty($barangMasuk) && empty($barangKeluar)): ?>
        <p style="text-align: center;">Tidak ada data barang</p>
    <?php endif; ?>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?> WIB</p>
        <p>Sistem Informasi Kelola Barang UPT Bandung</p>
    </div>
</body>
</html>