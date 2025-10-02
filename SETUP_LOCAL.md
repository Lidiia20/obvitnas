# OBVITNAS - Setup Local Development

## ✅ Setup Berhasil!

Aplikasi OBVITNAS sudah berhasil dikonfigurasi untuk berjalan di localhost.

## Persyaratan
- ✅ XAMPP atau Laragon (untuk MySQL dan Apache)
- ✅ PHP 8.1 atau lebih tinggi
- ✅ Composer
- ✅ MySQL/MariaDB

## Status Setup
- ✅ Database: `obvitnas_db` 
- ✅ Konfigurasi database sudah diubah ke localhost
- ✅ Semua tabel sudah dibuat dan berisi data
- ✅ User default sudah dibuat

## Cara Menjalankan

### 1. Pastikan MySQL Berjalan
Pastikan service MySQL di Laragon/XAMPP sudah running.

### 2. Jalankan Aplikasi
```bash
php spark serve
```

### 3. Akses Aplikasi
Buka browser dan akses: `http://localhost:8080`

## Database Information
- **Host**: localhost
- **Database**: obvitnas_db
- **Username**: root
- **Password**: (kosong)
- **Port**: 3306

## Akun Login Default

### Admin System
- **Username**: admin
- **Password**: password
- **Role**: admin

### Satpam
- **Username**: satpam1
- **Password**: password
- **Role**: satpam

### Admin GI
- **Username**: Admin GI 1
- **Email**: Admingi1@gmail.com
- **Role**: admin_gi

## Struktur Database yang Sudah Dibuat

✅ **zona_ultg** - Unit Layanan Transmisi
- ID: 1, Nama: ULTG BANDUNG

✅ **zona_gi** - Data Gardu Induk (6 records)
- GI 150KV BANDUNG UTARA
- GI 150KV CIANJUR
- GI 150KV CIBEUREUM BARU
- GI 150KV CIGERELENG
- GI 150KV PADALARANG BARU
- GI 150KV PANASIA

✅ **users** - Data pengguna sistem
✅ **admin-gi** - Data admin per GI
✅ **kunjungan** - Data kunjungan tamu

## Troubleshooting

### Jika masih ada error database
```bash
# Jalankan ulang setup database
php setup_final.php
```

### Error "Connection refused"
- Pastikan MySQL service sudah berjalan di Laragon/XAMPP
- Cek Task Manager untuk memastikan mysql.exe berjalan

### Error "Permission denied"
- Pastikan folder `writable` memiliki permission yang tepat
- Di Windows biasanya tidak ada masalah permission

## URL Akses Setelah Login
- **Homepage**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin
- **Dashboard Satpam**: http://localhost:8080/satpam/dashboard

## File Penting
- `app/Config/Database.php` - Konfigurasi database
- `setup_final.php` - Script setup database (untuk reset ulang)
- `obvitnas_local.sql` - Backup database
- `setup_database.bat` - Setup otomatis (Windows)

---
**Status**: ✅ READY TO USE
**Last Updated**: $(Get-Date -Format "yyyy-MM-dd HH:mm") 