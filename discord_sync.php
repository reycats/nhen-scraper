<?php
require_once 'config.php';

function sendToDiscord($miko_data) {
    // PASTIIN LU ISI WEBHOOK URL DISCORD LU DI SINI ASU!
    $webhook_url = "https://discord.com/api/webhooks/1474655589597577267/E__YOPQhU3J_Sq2t28HuUsUywYkl741kcEnf7ofH0wGI0UmTgVqVIvjCqhpwHq5D6EVq";

    $message = [
        "content" => "🏮 **MIKO BARU DICULIK MASTER MITSUKI!** 🏮",
        "embeds" => [[
            "title" => $miko_data['title'],
            "description" => "ID: " . $miko_data['id'] . "\nSource: " . $miko_data['source'],
            "color" => 9109504, // Warna Merah Oriental kntol!
            "image" => ["url" => $miko_data['cover']],
            "footer" => ["text" => "Logged at: " . date("Y-m-d H:i:s")]
        ]]
    ];

    $json_data = json_encode($message);
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Buat laptop RAM 2GB lu bbi!
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}