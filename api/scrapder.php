<?php
require_once 'config.php';

function fetchDataById($id) {
    if (!preg_match('/^\d{6}$/', $id)) return false;
    
    // URL Sumber Miko bbi!
    $url = "https://nhentai.net/g/" . $id . "/";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Mimic browser iQOO lu biar gak diblokir Cloudflare asu! [cite: 2025-12-19]
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) return false;

    // Ambil Judul Asli
    preg_match('/<span class="pretty">(.*?)<\/span>/', $res, $title);
    
    // Ambil Cover (Webp version biar enteng di laptop kentang lu!)
    preg_match('/<div id="cover">.*?<img.*?src="(.*?)"/', $res, $cover);
    
    // Ambil Tags yang bener bbi, jangan ngasal asu!
    preg_match_all('/<span class="name">(.*?)<\/span>/', $res, $tagMatches);
    $tags = isset($tagMatches[1]) ? array_slice($tagMatches[1], 0, 5) : ['miko', 'high-school'];

    return [
        'title' => $title[1] ?? "Miko #" . $id,
        'cover' => $cover[1] ?? "",
        'tags' => $tags,
        'download_url' => "https://nhentai.net/g/" . $id . "/download" // Link sedot mampus!
    ];
}