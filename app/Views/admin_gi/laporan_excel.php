<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background:#eee; font-weight:bold;">
            <th>No</th>
            <th>Nama</th>
            <th>No Kendaraan</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kunjungan as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($row['nama_tamu']) ?></td>
                <td><?= esc($row['no_kendaraan']) ?></td>
                <td><?= esc($row['keperluan']) ?></td>
                <td><?= esc($row['status']) ?></td>
                <td><?= esc($row['checkin'] ?? '-') ?></td>
                <td><?= esc($row['checkout'] ?? '-') ?></td>
                <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
