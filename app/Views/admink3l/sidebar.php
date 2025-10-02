<!-- app/Views/admink3l/sidebar.php -->
<div class="sidebar">
    <ul>
        <li><a href="/admink3l">Dashboard</a></li>
        <li><a href="/admink3l/kelola-user">Kelola User</a></li>
        <li><a href="/admink3l/tambah-user">Tambah User</a></li>
        <li><a href="/admink3l/kelola-zona">Kelola Zona</a></li>
        <li><a href="/admink3l/laporan-gi">Laporan GI</a></li>
        <li><a href="/admink3l/export-excel">Export Excel</a></li>
        <li><a href="/logout" onclick="return confirm('Yakin ingin logout?')">Logout</a></li>
    </ul>
</div>

<style>
.sidebar {
    width: 200px;
    background: #f4f4f4;
    padding: 15px;
    height: 100vh;
    position: fixed;
}
.sidebar ul {
    list-style: none;
    padding-left: 0;
}
.sidebar ul li {
    margin: 10px 0;
}
.sidebar ul li a {
    text-decoration: none;
    color: #333;
}
</style>
