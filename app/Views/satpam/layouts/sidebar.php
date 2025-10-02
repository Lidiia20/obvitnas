<div class="sidebar d-flex flex-column">
    <div class="sidebar-header">
        <h4 class="mb-1">
            <i class="fas fa-shield-alt me-2"></i>
            Satpam Panel
        </h4>
        <small class="text-light">GITET New Ujung Berung</small>
    </div>

    <ul class="nav flex-column flex-grow-1 p-3">
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'dashboard') echo 'active'; ?>" href="<?= base_url('satpam/dashboard') ?>">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'checkin') echo 'active'; ?>" href="<?= base_url('satpam/checkin') ?>">
                <i class="fas fa-sign-in-alt me-2"></i>Check-In
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'checkout') echo 'active'; ?>" href="<?= base_url('satpam/checkout') ?>">
                <i class="fas fa-sign-out-alt me-2"></i>Check-Out
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'historyKunjungan') echo 'active'; ?>" href="<?= base_url('satpam/historyKunjungan') ?>">
                <i class="fas fa-history me-2"></i>History Kunjungan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'barangmasuk') echo 'active'; ?>" href="<?= base_url('satpam/barangmasuk') ?>">
                <i class="fas fa-box-open me-2"></i>Barang Masuk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'barangkeluar') echo 'active'; ?>" href="<?= base_url('satpam/barangkeluar') ?>">
                <i class="fas fa-truck-loading me-2"></i>Barang Keluar
            </a>
        </li>
    </ul>

    <div class="p-3 border-top border-light border-opacity-25">
        <a href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin logout?')" class="btn btn-logout w-100">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>
