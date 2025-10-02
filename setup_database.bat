@echo off
echo ================================================
echo    OBVITNAS - Local Database Setup
echo ================================================
echo.
echo Pastikan XAMPP/Laragon MySQL sudah berjalan!
echo.
pause

echo Membuat database lokal...
mysql -u root -p < obvitnas_local.sql

if %errorlevel% == 0 (
    echo.
    echo ================================================
    echo Database berhasil dibuat!
    echo.
    echo Database: obvitnas_db
    echo Host: localhost
    echo Username: root
    echo Password: (kosong)
    echo.
    echo Sekarang Anda bisa menjalankan aplikasi!
    echo ================================================
) else (
    echo.
    echo ================================================
    echo Error! Pastikan MySQL sudah berjalan
    echo dan coba lagi.
    echo ================================================
)

echo.
pause 