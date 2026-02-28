<?php
require_once 'config.php';

function fetchDataCom($id) {
    if (!preg_match('/^\d{1,7}$/', $id)) return false;
    
    // nhentai.com itu url-nya beda asu!
    $url = "https://nhentai.com/en/g/" . $id;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Mimic iQOO lu biar gak diblokir mampus! [cite: 2025-12-19]
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 13; iQOO Z11) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) return false;

    // Ambil Judul (Struktur .com lebih simpel tapi dongo)
    preg_match('/<h1.*?>(.*?)<\/h1>/', $res, $title);
    
    // Ambil Cover HD asu!
    preg_match('/<img.*?src="(https:\/\/cdn.nhentai.com\/galleries\/.*?\/cover\..*?)"/', $res, $cover);
    
    // Ambil Genre/Tags miko sekolah lu bbi! [cite: 2026-02-15]
    preg_match_all('/<span class="tag-name">(.*?)<\/span>/', $res, $tagMatches);
    $tags = isset($tagMatches[1]) ? array_slice($tagMatches[1], 0, 6) : ['miko', 'high-school'];

    return [
        'title' => trim($title[1] ?? "Miko COM #" . $id),
        'cover' => $cover[1] ?? "",
        'tags' => $tags,
        'url' => $url
    ];
}