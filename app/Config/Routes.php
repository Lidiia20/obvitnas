<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -----------------------------
// ROOT & HOME
// -----------------------------
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

// -----------------------------
// AUTH (Login / Logout / Register Tamu)
// -----------------------------
$routes->get('login', 'Auth::loginMulti');
$routes->post('login', 'Auth::loginPost');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerPost');
$routes->get('forgot-password', 'Auth::forgotPasswordForm');
$routes->post('forgot-password', 'Auth::sendResetPassword');

// -----------------------------
// FORM KUNJUNGAN TAMU
// -----------------------------
$routes->get('kunjungan/form', 'Kunjungan::form');
$routes->post('kunjungan/submit', 'Kunjungan::submit');

// -----------------------------
// DASHBOARD (Per Role)
// -----------------------------
$routes->get('dashboard/tamu', 'Dashboard::tamu');
$routes->get('dashboard/satpam', 'Dashboard::satpam');
$routes->get('dashboard/admink3l', 'AdminK3L::index');

// -----------------------------
// ADMIN GI
// -----------------------------
$routes->group('admin-gi', ['namespace' => 'App\Controllers'], static function ($routes) {
    $routes->get('/', 'AdminGI::index');
    $routes->get('dashboard', 'AdminGI::dashboard');
    $routes->get('data_kunjungan', 'AdminGI::data_kunjungan');
    $routes->get('export/kunjungan-pdf', 'AdminGI::export');
    $routes->get('export/kunjungan-excel', 'AdminGI::exportExcel');
    $routes->get('kelola_user', 'AdminGI::kelolaUser');
    $routes->post('tambah-akun', 'AdminGI::tambahAkun');
    $routes->get('jadwal', 'JadwalSatpam::index');
    $routes->post('simpan-jadwal', 'JadwalSatpam::simpan');
    $routes->get('hapus-jadwal/(:num)', 'JadwalSatpam::hapus/$1');
    $routes->get('export-jadwal-pdf', 'JadwalSatpam::exportPDF');
    $routes->get('export-jadwal-excel', 'JadwalSatpam::exportExcel');
    $routes->get('preview-pdf', 'AdminGI::previewPdf');
    $routes->get('kelolaBarang', 'AdminGI::kelolaBarang');
    $routes->get('export-barang-masuk/pdf', 'ExportBarang::exportMasukPDF');
    $routes->get('export-barang-masuk/excel', 'ExportBarang::exportMasukExcel');
    $routes->get('export-barang-keluar/pdf', 'ExportBarang::exportKeluarPDF');
    $routes->get('export-barang-keluar/excel', 'ExportBarang::exportKeluarExcel');
});

// -----------------------------
// ADMIN K3L
// -----------------------------
$routes->group('admink3l', static function ($routes) {
    $routes->get('/', 'AdminK3L::index');
    $routes->get('kelola-user', 'AdminK3L::kelolaUser');
    $routes->get('tambah-user', 'AdminK3L::tambahUser');
    $routes->post('simpan-user', 'AdminK3L::simpanUser');
    $routes->get('hapus-user/(:segment)/(:num)', 'AdminK3L::hapusUser/$1/$2');
    $routes->get('kelola-zona', 'AdminK3L::kelolaZona');
    $routes->post('simpan-upt', 'AdminK3L::simpanZonaUpt');
    $routes->post('simpan-ultg', 'AdminK3L::simpanZonaUltg');
    $routes->post('simpan-gi', 'AdminK3L::simpanZonaGi');
    $routes->get('laporan-gi', 'AdminK3L::laporanGI');
    $routes->get('export-excel', 'AdminK3L::exportExcel');
    $routes->get('kelola-tamu', 'AdminK3L::kelolaTamu');
    $routes->get('kelola-satpam', 'AdminK3L::kelolaSatpam');
    $routes->get('kelola-admin-gi', 'AdminK3L::kelolaAdminGI');
    $routes->post('tambah-akun', 'AdminK3L::tambahAkun');
    $routes->get('kelola-akun', 'AdminK3L::kelolaAkun');
    $routes->post('hapus-user/(:num)', 'AdminK3L::hapusUser/$1');
    $routes->get('dashboard/getStats', 'DashboardController::getStats');
    $routes->get('export-pdf', 'AdminK3L::exportPdf'); // <-- konsisten dengan AdminK3L, bukan AdminK3LController
});
$routes->get('grafik/get', 'GrafikController::get');

// -----------------------------
// SATPAM
// -----------------------------
$routes->group('satpam', ['namespace' => 'App\Controllers'], function ($routes) {
    // Dashboard
    $routes->get('/', 'DashboardSatpam::index');
    $routes->get('dashboard', 'DashboardSatpam::dashboard', ['as' => 'dashboard']);

    // Check-in / Check-out
    $routes->get('checkin', 'DashboardSatpam::checkin', ['as' => 'checkin']);
    $routes->post('verifikasi_checkin', 'DashboardSatpam::verifikasi_checkin');
    $routes->get('checkout', 'DashboardSatpam::checkout', ['as' => 'checkout']);
    $routes->post('verifikasi_checkout', 'DashboardSatpam::verifikasi_checkout');
    $routes->get('scan-wajah/(:num)', 'DashboardSatpam::scanWajah/$1');
    $routes->post('verifikasiWajah', 'DashboardSatpam::verifikasiWajah');
    $routes->get('formCheckin/(:num)', 'DashboardSatpam::formCheckin/$1');
    $routes->get('historyKunjungan', 'DashboardSatpam::historyKunjungan', ['as' => 'historyKunjungan']);
    $routes->post('proses-verifikasi-wajah', 'DashboardSatpam::prosesVerifikasiWajah');

    // Statistik
    $routes->get('statistik', 'DashboardSatpam::statistik');

    // Barang Masuk
    $routes->group('barangmasuk', function ($routes) {
        $routes->get('/', 'Satpam\BarangMasuk::index', ['as' => 'barang_masuk_index']);
        $routes->get('create', 'Satpam\BarangMasuk::create', ['as' => 'barang_masuk_create']);
        $routes->post('store', 'Satpam\BarangMasuk::store', ['as' => 'barang_masuk_store']);
        $routes->get('edit/(:num)', 'Satpam\BarangMasuk::edit/$1', ['as' => 'barang_masuk_edit']);
        $routes->match(['put', 'post'], 'update/(:num)', 'Satpam\BarangMasuk::update/$1', ['as' => 'barang_masuk_update']);
        $routes->get('view/(:num)', 'Satpam\BarangMasuk::view/$1', ['as' => 'barang_masuk_view']);
        $routes->match(['delete', 'post'], 'delete/(:num)', 'Satpam\BarangMasuk::delete/$1', ['as' => 'barang_masuk_delete']);
        $routes->get('export/(:alpha)', 'Satpam\BarangMasuk::export/$1', ['as' => 'barang_masuk_export']);
        $routes->get('getData', 'Satpam\BarangMasuk::getData', ['as' => 'barang_masuk_get_data']);
        $routes->get('file/(:segment)', 'Satpam\BarangMasuk::viewFile/$1', ['as' => 'barang_masuk_file']);
    });

    // Barang Keluar
        // Barang Keluar
    $routes->group('barangkeluar', function ($routes) {
        $routes->get('/', 'Satpam\BarangKeluar::index', ['as' => 'barang_keluar_index']);
        $routes->get('create', 'Satpam\BarangKeluar::create', ['as' => 'barang_keluar_create']);
        $routes->post('store', 'Satpam\BarangKeluar::store', ['as' => 'barang_keluar_store']);
        $routes->get('edit/(:num)', 'Satpam\BarangKeluar::edit/$1', ['as' => 'barang_keluar_edit']);
        $routes->match(['put', 'post'], 'update/(:num)', 'Satpam\BarangKeluar::update/$1', ['as' => 'barang_keluar_update']);
        $routes->get('view/(:num)', 'Satpam\BarangKeluar::view/$1', ['as' => 'barang_keluar_view']);
        $routes->match(['delete', 'post'], 'delete/(:num)', 'Satpam\BarangKeluar::delete/$1', ['as' => 'barang_keluar_delete']);
        $routes->get('export/(:alpha)', 'Satpam\BarangKeluar::export/$1', ['as' => 'barang_keluar_export']);
        $routes->get('getData', 'Satpam\BarangKeluar::getData', ['as' => 'barang_keluar_get_data']);
        $routes->get('file/(:segment)', 'Satpam\BarangKeluar::viewFile/$1', ['as' => 'barang_keluar_file']);
    });
}); // ✅ ini penutup untuk group satpam

// -----------------------------
// KOORDINATOR SATPAM
// -----------------------------
$routes->group('koordinator', ['filter' => 'auth_koordinator_satpam'], static function ($routes) {
    $routes->get('/', 'KoordinatorSatpam::index');
    $routes->get('dashboard', 'KoordinatorSatpam::dashboard');
    $routes->get('jadwal', 'KoordinatorSatpam::jadwal');
    $routes->get('create-jadwal', 'KoordinatorSatpam::createJadwal');
    $routes->post('create-jadwal', 'KoordinatorSatpam::createJadwal');
    $routes->get('edit-jadwal/(:num)', 'KoordinatorSatpam::editJadwal/$1');
    $routes->post('edit-jadwal/(:num)', 'KoordinatorSatpam::editJadwal/$1');
    $routes->get('kelola-satpam', 'KoordinatorSatpam::kelolaSatpam');
    $routes->get('delete-jadwal/(:num)', 'KoordinatorSatpam::deleteJadwal/$1');
    $routes->get('shift-details/(:num)', 'KoordinatorSatpam::shiftDetails/$1');
});

// -----------------------------
// API & ZONA
// -----------------------------
$routes->get('api/ultg/(:num)', 'ZonaController::getUltg/$1');
$routes->get('api/gi/(:num)', 'ZonaController::getGi/$1');
$routes->get('zona/getUpt', 'ZonaController::getUpt');
$routes->get('zona/getUltg/(:num)', 'ZonaController::getUltg/$1');
$routes->get('zona/getGi/(:num)', 'ZonaController::getGi/$1');

// -----------------------------
// CHECKIN
// -----------------------------
$routes->get('checkin/scan/(:num)', 'Checkin::scan/$1');
$routes->post('checkin/verifikasi-wajah', 'Checkin::verifikasiWajah');
