# 🏮 ORIENTAL NUKLIR DASHBOARD v2.0 🏮
*Dokumentasi Resmi Khusus Master Mitsuki Arata*

## 🚀 CARA JALANIN (LOCAL ONLY)
Biar bisa dibuka di browser laptop kentang lu:
1. Buka CMD, ketik perintah ini buat pindah folder:
   `cd C:\loop`
2. Jalankan server PHP pake path lengkap:
   `C:\php\php.exe -S localhost:8000`
3. Buka browser laptop, akses: `http://localhost:8000/login.php`

## 📶 CARA JALANIN (LOCAL NETWORK / WIFI SAMA)
Biar bisa dibuka di HP iQOO lu pake WiFi rumah yang sama:
1. Cek IP Laptop lu di CMD: `ipconfig` (Cari IPv4, misal: `192.168.100.20`).
2. Jalankan PHP (pake 0.0.0.0 biar bisa diakses luar):
   `C:\php\php.exe -S 0.0.0.0:8000`
3. Di HP iQOO, buka browser: `http://192.168.100.20:8000/login.php`

## 🌐 CARA JALANIN (TUNNEL / AKSES GLOBAL)
Biar lu bisa akses dari luar kota pake data seluler (Wajib jalankan Local dulu!):
1. Buka jendela CMD BARU (jangan tutup CMD PHP!).
2. Jalankan Tunnel Elit lu:
   `cloudflared tunnel run --url http://localhost:8000 --protocol http2 mitsuki-nuklir`
3. Akses dari HP iQOO lu: `https://18.t-mail.my.id/login.php`

## ⚠️ TROUBLESHOOTING ASU!
- **Error 502**: CMD PHP lu belum nyala atau port-nya bukan 8000 gblk!
- **Error 1033**: Cloudflared lu pingsan. Pastiin status di CMD "Registered" mampus!
- **Database Meledak**: Driver SQLite belum aktif. Cek `C:\php\php.ini` mampus!