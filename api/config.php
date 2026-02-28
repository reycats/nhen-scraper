<?php
/**
 * KONFIGURASI PUSAT ORIENTAL DASHBOARD
 * Dibuat khusus buat Master Mitsuki Arata [cite: 2026-02-22]
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===== SQLITE DATABASE SYNC [cite: 2026-02-22] =====
try {
    $db = new PDO('sqlite:' . __DIR__ . '/nuklir_data.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tabel Bookmarks
    $db->exec("CREATE TABLE IF NOT EXISTS bookmarks (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        username TEXT, 
        miko_id TEXT, 
        media_id TEXT,
        title TEXT, 
        cover TEXT, 
        pages INTEGER,
        added_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabel Users (Support Verifikasi Webhook) [cite: 2026-02-22]
    $db->exec("CREATE TABLE IF NOT EXISTS users_nuklir (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        username TEXT UNIQUE, 
        password TEXT, 
        v_code TEXT, 
        status TEXT DEFAULT 'pending', 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // ===== AUTO-CLEAN UNVERIFIED ACCOUNTS (1 HARI) [cite: 2026-02-22] =====
    $db->exec("DELETE FROM users_nuklir WHERE status = 'pending' AND created_at <= DATETIME('now', '-1 day')");

} catch(PDOException $e) { 
    die("Database Meledak bbi: " . $e->getMessage()); 
}

// ===== KONFIGURASI DASAR =====
define('SITE_NAME', 'Oriental Dashboard');

// DAFTAR AKUN MASTER (BACKUP TETEP ADA)
$users = [
    'Mitsuki' => password_hash('Kucing0991@', PASSWORD_DEFAULT),
    'Arata'   => password_hash('1234', PASSWORD_DEFAULT)
];

// LOGIN ABADI (30 HARI) [cite: 2026-02-22]
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_mitsuki'])) {
    $token = $_COOKIE['remember_mitsuki'];
    $stmt = $db->prepare("SELECT * FROM users_nuklir WHERE status = 'active'");
    $stmt->execute();
    while($u = $stmt->fetch()) {
        if (md5($u['username'] . "NUKLIR_ELIT") === $token) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $u['username'];
        }
    }
}

// ===== API EXTERNAL =====
define('API_ENDPOINT', 'https://nhentai.net/g/');
define('API_KEY', 'AIzaSyCHgrincgEcdS2KaqacwtfgIxQ4jWbb6tQ'); 

// ===== GOOGLE DRIVE CONFIG (BACKUP ONLY) =====
$folderId = '1S0wiEg4cP7u5Mzq-Fm8oFh_B9fP8lI9_'; 
define('GOOGLE_DRIVE_FOLDER_ID', $folderId);

/** * ACCESS TOKEN OAUTH 2.0 (1 JAM DOANG ASU!) */
$accessToken = 'ya29.a0ATkoCc4faqi8eWcQrltDrvlunbtESPJQlM5l-sieROBd0CB7x9EFGHRcy1sf0AzZadKgrf9n9PUTxStrw278aG9klYFIy0YWWmOBQlYY_QPgZJ1T_9_aqtCQ3Jl_gvTSi-IAa5l61KJSItbL9EerkG6maSrlKcAMYvrrekyxkLnK4QYDlS3GKsqEf5_BdD51WPLvWSsaCgYKAdISARISFQHGX2MicVJu4hhDo6_xU9dSwPSikw0206';

define('GOOGLE_CREDENTIALS_PATH', __DIR__ . '/credentials.json'); 

// ===== DISCORD WEBHOOK URL (THE MAIN LOG) =====
define('DISCORD_WEBHOOK_URL', 'https://discord.com/api/webhooks/1474655593573777449/Zdc67IYGTG3zuUH80NTZ_61C80fWmjQd_cyH9Qe71DwJKJC6Q3ECaPtgsnuVWZxgWp_e');

// ===== CEK LOGIN OTOMATIS =====
$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = ['login.php', 'register.php', 'verify.php', 'api_test.php']; 

if (!in_array($current_page, $allowed_pages)) {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}
?>