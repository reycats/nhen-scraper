@echo off
setlocal enabledelayedexpansion
title MASTER ARATA SERVER - ANIMATION EDITION [cite: 2026-02-22]
color 0E

:: === ANIMASI LOADING GILA KAISAR ===
echo.
echo  [ 🏮 ] MENGAKTIFKAN PROTOKOL KERAJAAN MIKO...
echo.
set "bar=####################"
for /l %%i in (1,1,20) do (
    cls
    echo.
    echo  [ 🏮 ] LOADING PROTOKOL KAISAR ARATA...
    echo  [!bar:~0,%%i!] %%i0%%
    timeout /t 1 /nobreak >nul
)
cls
echo.
echo  [ ✅ ] JALUR NUKLIR TERBUKA SEJAGAT RAYA ASU!
echo.

:: === FORCE PATH PHP (Folder kaisar: C:\php) ===
set "PATH=%PATH%;C:\php"

:: 1. CMD PERTAMA: PHP Server di 0.0.0.0
start "PHP_CORE_SERVER" cmd /k "color 0A && echo 🐉 SERVER NYALA DI 0.0.0.0:8000 && php -S 0.0.0.0:8000"

:: Jeda biar naga pertama napas dulu bbi
timeout /t 2 /nobreak >nul

:: 2. CMD KEDUA: Cloudflare Tunnel (Fix Protocol http2 bbi!)
:: Ganti domain kalau mau asu, ini pake protocol biar gak timeout!
start "CLOUDFLARE_TUNNEL" cmd /k "color 0B && echo 🧧 TUNNELING GAS KE 18.t-mail.my.id && cloudflared tunnel run --url http://127.0.0.1:8000 --protocol http2 mitsuki-nuklir"

echo  [ 🔥 ] 2 CMD SUDAH NYALA TERPISAH!
echo  [ 🧧 ] iQOO SIAP PANEN MIKO HD MAMPUS!
echo.
pause