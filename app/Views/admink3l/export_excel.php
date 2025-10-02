<form action="<?= base_url('admink3l/export-excel') ?>" method="get" target="_blank">
    <label for="upt_id">UPT:</label>
    <select name="upt_id" id="upt_id">
        <option value="">-- Pilih UPT --</option>
        <?php foreach ($upt_list as $upt): ?>
            <option value="<?= $upt['id'] ?>"><?= $upt['nama_upt'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="ultg_id">ULTG:</label>
    <select name="ultg_id" id="ultg_id">
        <option value="">-- Pilih ULTG --</option>
        <!-- Akan diisi dengan JS dinamis -->
    </select>

    <label for="gi_id">GI:</label>
    <select name="gi_id" id="gi_id">
        <option value="">-- Pilih GI --</option>
        <!-- Akan diisi dengan JS dinamis -->
    </select>

    <label for="start_date">Dari Tanggal:</label>
    <input type="date" name="start_date" id="start_date">

    <label for="end_date">Sampai Tanggal:</label>
    <input type="date" name="end_date" id="end_date">

    <button type="submit">Export Excel</button>
</form>


<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Tamu</th>
            <th>Unit</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Email</th>
            <th>No Kendaraan</th>
            <th>Keperluan</th>
            <th>Zona UPT</th>
            <th>Zona ULTG</th>
            <th>Zona GI</th>
            <th>Status</th>
            <th>Jam</th>
            <th>Nama Satpam Check-in</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $row['nama'] ?? '-' ?></td>
                    <td><?= $row['unit'] ?? '-' ?></td>
                    <td><?= $row['alamat'] ?? '-' ?></td>
                    <td><?= $row['no_hp'] ?? '-' ?></td>
                    <td><?= $row['email'] ?? '-' ?></td>
                    <td><?= $row['no_kendaraan'] ?? '-' ?></td>
                    <td><?= $row['keperluan'] ?? '-' ?></td>
                    <td><?= $row['upt_id'] ?? '-' ?></td>
                    <td><?= $row['ultg_id'] ?? '-' ?></td>
                    <td><?= $row['gi_id'] ?? '-' ?></td>
                    <td><?= $row['status'] ?? '-' ?></td>
                    <td><?= $row['jam'] ?? '-' ?></td>
                    <td><?= $row['nama_satpam_checkin'] ?? '-' ?></td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr><td colspan="14">Tidak ada data</td></tr>
        <?php endif ?>
    </tbody>
</table>
