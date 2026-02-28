<?php
require_once 'config.php';

function fetchDataById($id) {
    if (!preg_match('/^\d{6}$/', $id)) return false;
    $url = "https://nhentai.net/g/" . $id . "/";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Mimic iQOO lu biar gak diblokir satpam nhentai asu! [cite: 2025-12-19]
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) return false;

    // Ambil Judul & Cover
    preg_match('/<span class="pretty">(.*?)<\/span>/', $res, $title);
    preg_match('/<div id="cover">.*?<img.*?src="(.*?)"/', $res, $cover);
    
    // Ambil SEMUA Info Miko (Tags, Artist, Group) gblk!
    preg_match_all('/<span class="name">(.*?)<\/span>/', $res, $matches);
    $tags = isset($matches[1]) ? array_slice($matches[1], 0, 8) : ['miko', 'high-school'];

    return [
        'title' => $title[1] ?? "Miko #" . $id,
        'cover' => $cover[1] ?? "",
        'tags' => $tags,
        'url' => $url
    ];
}