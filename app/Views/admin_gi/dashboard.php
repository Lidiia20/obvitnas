<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin UPT Bandung</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #f8fafc;
            --accent-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            padding-bottom: 2rem;
        }

        /* Navbar Enhancement */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-medium);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            transform: translateY(-1px);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
        }

        /* Container Enhancement */
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-heavy);
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Header Enhancement */
        .dashboard-header {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(79, 70, 229, 0.1);
        }

        .dashboard-title {
            background: linear-gradient(135deg, var(--primary-color), #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.2rem;
            margin: 0;
            letter-spacing: -1px;
        }

        .date-display {
            background: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            color: var(--text-secondary);
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Stat Cards Enhancement */
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            border-radius: 20px 20px 0 0;
        }

        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, var(--primary-color), #8b5cf6); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, var(--accent-color), #34d399); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, var(--info-color), #38bdf8); }
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, var(--warning-color), #fbbf24); }
        .stat-card:nth-child(5)::before { background: linear-gradient(90deg, var(--danger-color), #f56565); } /* Barang Masuk */
        .stat-card:nth-child(6)::before { background: linear-gradient(90deg, var(--info-color), #06b6d4); } /* Barang Keluar */

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-heavy);
        }

        .stat-card .card-body {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            opacity: 0.2;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.6;
            transform: scale(1.1);
        }

        /* Chart Cards Enhancement */
        .chart-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-heavy);
        }

        .chart-card .card-header {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border: none;
            padding: 1.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(79, 70, 229, 0.1);
        }

        .chart-card .card-body {
            padding: 2rem;
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1rem;
                border-radius: 15px;
            }

            .dashboard-title {
                font-size: 1.8rem;
            }

            .stat-value {
                font-size: 2rem;
            }

            .chart-card .card-body {
                padding: 1rem;
            }

            .dashboard-header {
                text-align: center;
            }

            .dashboard-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 576px) {
            .stat-card .card-body {
                padding: 1.5rem 1rem;
            }

            .stat-value {
                font-size: 1.8rem;
            }
        }

        /* Loading Animation */
        .chart-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            color: var(--text-secondary);
        }

        /* Button Enhancement */
        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-1px);
        }

        /* Animation keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }
        .animate-delay-5 { animation-delay: 0.5s; }
        .animate-delay-6 { animation-delay: 0.6s; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-tachometer-alt me-2"></i>Admin UPT Bandung
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/admin-gi">
                        <i class="fas fa-chart-line me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin-gi/data_kunjungan">
                        <i class="fas fa-users me-1"></i>Data Kunjungan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin-gi/kelola_user">
                        <i class="fas fa-user-cog me-1"></i>Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin-gi/jadwal">
                        <i class="fas fa-calendar-alt me-1"></i>Jadwal Satpam
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin-gi/kelolaBarang">
                        <i class="fas fa-box-open me-1"></i>Kelola Barang
                    </a>
                </li>
            </ul>
            <button type="button" class="btn btn-logout" onclick="showLogoutConfirmation()">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </button>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container-fluid">
    <div class="main-container animate-fade-up">

        <!-- HEADER -->
        <div class="dashboard-header animate-fade-up animate-delay-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="dashboard-title">Dashboard Admin UPT Bandung</h1>
                    <p class="mb-0 text-muted">Kelola dan monitor sistem kunjungan dan Kelola Barang Masuk/Keluar dengan mudah</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar me-2"></i>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-4 mb-4">
            <?php 
                $cards = [
                    ['label' => 'Total Kunjungan', 'value' => $total ?? 0, 'icon' => 'fas fa-users', 'delay' => '1'],
                    ['label' => 'Approved', 'value' => $approved ?? 0, 'icon' => 'fas fa-check-circle', 'delay' => '2'],
                    ['label' => 'Checkout', 'value' => $checkout ?? 0, 'icon' => 'fas fa-sign-out-alt', 'delay' => '3'],
                    ['label' => 'Pending', 'value' => $pending ?? 0, 'icon' => 'fas fa-clock', 'delay' => '4'],
                    // ['label' => 'Barang Masuk', 'value' => $total_barang_masuk ?? 0, 'icon' => 'fas fa-box-open', 'delay' => '5'],
                    // ['label' => 'Barang Keluar', 'value' => $total_barang_keluar ?? 0, 'icon' => 'fas fa-box', 'delay' => '6'],
                ]; 
            ?>
            <?php foreach ($cards as $card): ?>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card animate-fade-up animate-delay-<?= $card['delay'] ?>">
                        <div class="card-body position-relative">
                            <i class="<?= $card['icon'] ?> stat-icon"></i>
                            <h6 class="stat-label"><?= $card['label'] ?></h6>
                            <h2 class="stat-value"><?= $card['value'] ?></h2>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CHARTS -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card chart-card animate-fade-up animate-delay-2">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i>Status Kunjungan
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card animate-fade-up animate-delay-3">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Kunjungan per Minggu
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="card chart-card animate-fade-up animate-delay-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Barang Masuk per Minggu
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barangMasukChart"></canvas>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- <div class="col-lg-6">
                <div class="card chart-card animate-fade-up animate-delay-5">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Barang Keluar per Minggu
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barangKeluarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

    </div>
</div>

<!-- SCRIPTS -->
<script>
    // Show logout confirmation
    function showLogoutConfirmation() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            window.location.href = "<?= base_url('logout') ?>";
        }
    }

    // Display current date
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Chart configurations
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6b7280';

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Checkout', 'Pending'],
            datasets: [{
                data: [<?= $approved ?? 0 ?>, <?= $checkout ?? 0 ?>, <?= $pending ?? 0 ?>],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(6, 182, 212, 1)',
                    'rgba(245, 158, 11, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            weight: '600'
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Bar Chart Kunjungan
    const barCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($weekly_data ?? [])) ?>,
            datasets: [{
                label: 'Kunjungan per Minggu',
                data: <?= json_encode(array_values($weekly_data ?? [])) ?>,
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            weight: '600'
                        }
                    }
                }
            }
        }
    });

    // Bar Chart Barang Masuk
    const masukCtx = document.getElementById('barangMasukChart').getContext('2d');
    new Chart(masukCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($barang_masuk_weekly ?? [])) ?>,
            datasets: [{
                label: 'Barang Masuk per Minggu',
                data: <?= json_encode(array_values($barang_masuk_weekly ?? [])) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            weight: '600'
                        }
                    }
                }
            }
        }
    });

    // Bar Chart Barang Keluar
    const keluarCtx = document.getElementById('barangKeluarChart').getContext('2d');
    new Chart(keluarCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($barang_keluar_weekly ?? [])) ?>,
            datasets: [{
                label: 'Barang Keluar per Minggu',
                data: <?= json_encode(array_values($barang_keluar_weekly ?? [])) ?>,
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            weight: '600'
                        }
                    }
                }
            }
        }
    });

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>