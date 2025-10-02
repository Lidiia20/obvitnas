<!DOCTYPE html>
<html>
<head>
    <title>Statistik Kunjungan Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="container mt-5">
    <h3>Statistik Kunjungan Harian</h3>

    <div class="row mt-4">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr class="table-primary">
                    <th>Total</th>
                    <th>Approved</th>
                    <th>Check-out</th>
                    <th>Pending</th>
                </tr>
                <tr>
                    <td><?= $total ?></td>
                    <td><?= $approved ?></td>
                    <td><?= $checkout ?></td>
                    <td><?= $pending ?></td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <canvas id="kunjunganChart" width="400" height="300"></canvas>
        </div>
    </div>

    <a href="<?= base_url('/dashboard/satpam') ?>" class="btn btn-secondary mt-4">Kembali ke Dashboard</a>

    <script>
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const kunjunganChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Approved', 'Check-out', 'Pending'],
                datasets: [{
                    label: 'Statistik Hari Ini',
                    data: [
                        <?= $total ?>,
                        <?= $approved ?>,
                        <?= $checkout ?>,
                        <?= $pending ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</body>
</html>
