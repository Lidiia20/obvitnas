<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Admin K3L</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            plnblue: '#0077C8',
            plnyellow: '#FFD100',
            softgray: '#f7fafc',
          }
        }
      }
    };
  </script>

  <style>
    canvas {
      width: 100% !important;
      height: auto !important;
      aspect-ratio: 4 / 3;
    }
  </style>
</head>

<body class="bg-softgray font-sans min-h-screen">

  <!-- Navbar -->
  <nav class="bg-plnblue text-white px-4 py-3 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center space-x-3">
        <img src="/path-to-logo.png" alt="PLN" class="h-10 w-10" />
        <h1 class="font-bold text-lg">Sistem Keamanan Obvitnas</h1>
      </div>
      <div class="hidden md:flex space-x-6 items-center">
        <a href="/admink3l" class="hover:text-plnyellow transition">Dashboard</a>
        <a href="/admink3l/laporan-gi" class="hover:text-plnyellow transition">Laporan GI</a>
        <a href="/logout" class="bg-plnyellow text-plnblue px-3 py-1 rounded font-semibold hover:bg-yellow-400 transition">Logout</a>
      </div>
      <button class="md:hidden" id="mobile-menu-button">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
    <div class="md:hidden hidden mt-3 space-y-2 px-2" id="mobile-menu">
      <a href="/admink3l" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Dashboard</a>
      <a href="/admink3l/laporan-gi" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Laporan GI</a>
      <a href="/logout" class="block text-plnyellow font-semibold">Logout</a>
    </div>
  </nav>

  <!-- Layout -->
  <div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white border-r border-gray-200 shadow-md md:min-h-screen">
      <div class="p-6 border-b font-bold text-plnblue text-lg">Admin K3L</div>
      <nav class="p-4 space-y-2">
        <a href="/admink3l/kelola-user" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Kelola User</a>
        <a href="/admink3l/kelola-akun" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Kelola Akun</a>
        <a href="/admink3l/tambah-user" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Tambah User</a>
        <a href="/admink3l/kelola-zona" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Kelola Zona</a>
        <a href="/admink3l/export-excel" class="block py-2 px-3 rounded hover:bg-blue-50 text-gray-700">Export Excel</a>
        <a href="/logout" onclick="return confirm('Keluar dari akun?')" class="block py-2 px-3 rounded text-red-600 hover:bg-red-50 font-medium">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <h2 class="text-2xl font-semibold text-gray-700 mb-6">Dashboard Admin K3L</h2>

      <!-- Statistik Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-5 rounded-lg shadow-md">
          <h3 class="text-sm text-gray-500">Total Kunjungan</h3>
          <p class="text-2xl font-bold text-gray-800"><?= $total ?></p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-md">
          <h3 class="text-sm text-gray-500">Disetujui (Check-in)</h3>
          <p class="text-2xl font-bold text-gray-800"><?= $approved ?></p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-md">
          <h3 class="text-sm text-gray-500">Sudah Check-out</h3>
          <p class="text-2xl font-bold text-gray-800"><?= $checkout ?></p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-md">
          <h3 class="text-sm text-gray-500">Pending</h3>
          <p class="text-2xl font-bold text-gray-800"><?= $pending ?></p>
        </div>
      </div>

      <!-- Dropdown Filters -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <select id="filterUPT" class="p-2 border rounded bg-white text-gray-700">
          <option value="">Pilih UPT</option>
        </select>
        <select id="filterULTG" class="p-2 border rounded bg-white text-gray-700">
          <option value="">Pilih ULTG</option>
        </select>
        <select id="filterGI" class="p-2 border rounded bg-white text-gray-700">
          <option value="">Pilih GI</option>
        </select>
      </div>

      <!-- Grafik -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold mb-3 text-plnblue">Grafik Harian</h3>
          <canvas id="chartHarian"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold mb-3 text-plnblue">Grafik Mingguan</h3>
          <canvas id="chartMingguan"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold mb-3 text-plnblue">Grafik Bulanan</h3>
          <canvas id="chartBulanan"></canvas>
        </div>
      </div>
    </main>
  </div>

  
  <!-- Scripts -->
  <script>
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    let chartHarian, chartMingguan, chartBulanan;

    async function fetchAndRenderCharts() {
      const upt = document.getElementById("filterUPT").value;
      const ultg = document.getElementById("filterULTG").value;
      const gi = document.getElementById("filterGI").value;

      const params = new URLSearchParams({ upt, ultg, gi });
      const res = await fetch(`/grafik/get?${params.toString()}`);
      const data = await res.json();

      // Harian
      const ctxHarian = document.getElementById("chartHarian").getContext("2d");
      if (chartHarian) chartHarian.destroy();
      chartHarian = new Chart(ctxHarian, {
        type: 'line',
        data: {
          labels: data.harian.labels,
          datasets: [{
            label: 'Kunjungan Harian',
            data: data.harian.values,
            backgroundColor: '#FFD100',
            borderColor: '#0077C8',
            borderWidth: 2,
            fill: true
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });

      // Mingguan
      const ctxMingguan = document.getElementById("chartMingguan").getContext("2d");
      if (chartMingguan) chartMingguan.destroy();
      chartMingguan = new Chart(ctxMingguan, {
        type: 'bar',
        data: {
          labels: data.mingguan.labels,
          datasets: [{
            label: 'Kunjungan Mingguan',
            data: data.mingguan.values,
            backgroundColor: '#0077C8'
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });

      // Bulanan
      const ctxBulanan = document.getElementById("chartBulanan").getContext("2d");
      if (chartBulanan) chartBulanan.destroy();
      chartBulanan = new Chart(ctxBulanan, {
        type: 'line',
        data: {
          labels: data.bulanan.labels,
          datasets: [{
            label: 'Kunjungan Bulanan',
            data: data.bulanan.values,
            backgroundColor: '#FFD100',
            borderColor: '#0077C8',
            borderWidth: 2,
            fill: false
          }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
      const uptSelect = document.getElementById('filterUPT');
      const ultgSelect = document.getElementById('filterULTG');
      const giSelect = document.getElementById('filterGI');

      function loadUpt() {
        fetch('/zona/getUpt')
          .then(res => res.text())
          .then(data => {
            uptSelect.innerHTML = data;
          });
      }

      uptSelect.addEventListener('change', function () {
        const uptId = this.value;
        ultgSelect.innerHTML = '<option value="">Pilih ULTG</option>';
        giSelect.innerHTML = '<option value="">Pilih GI</option>';
        if (uptId) {
          fetch(`/zona/getUltg/${uptId}`)
            .then(res => res.text())
            .then(data => {
              ultgSelect.innerHTML = data;
              fetchAndRenderCharts();
            });
        }
      });

      ultgSelect.addEventListener('change', function () {
        const ultgId = this.value;
        giSelect.innerHTML = '<option value="">Pilih GI</option>';
        if (ultgId) {
          fetch(`/zona/getGi/${ultgId}`)
            .then(res => res.text())
            .then(data => {
              giSelect.innerHTML = data;
              fetchAndRenderCharts();
            });
        }
      });

      giSelect.addEventListener('change', fetchAndRenderCharts);

      loadUpt();
      fetchAndRenderCharts();
    });
  </script>
</body>
</html>
