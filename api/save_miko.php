<?php
require_once 'config.php';
$id = $_POST['id'] ?? '';
$media_id = $_POST['media_id'] ?? '';
$title = $_POST['title'] ?? '';
$cover = $_POST['thumb'] ?? ''; // Fix: Ngambil dari form 'thumb' tapi simpen ke db bbi!
$user = $_SESSION['username'] ?? 'Mitsuki';

try {
    // Pastiin kolomnya sinkron sama config.php asu! [cite: 2026-02-22]
    $stmt = $db->prepare("INSERT INTO bookmarks (username, miko_id, media_id, title, cover, pages) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user, $id, $media_id, $title, $cover, 35]);

    // KIRIM LOG KE DISCORD BIAR ELIT [cite: 2026-02-22]
    $msg = ["content" => "🏮 **Kaisar $user** baru aja nyimpen koleksi elit: $title (ID: $id)"];
    $ch = curl_init(DISCORD_WEBHOOK_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    curl_exec($ch); curl_close($ch);

    echo "Tersimpan di Cloud Nuklir bbi!";
} catch(PDOException $e) {
    echo "Gagal culik asu: " . $e->getMessage();
}
?>