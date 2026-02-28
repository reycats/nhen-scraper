<?php
require_once 'config.php';

function fetchDataById($id) {
    if (!preg_match('/^\d{6}$/', $id)) return false;
    $url = "https://nhentai.net/g/" . $id . "/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    if (!$res) return false;

    // Tarik Gallery ID (GID) buat link t1-t4 bbi!
    preg_match('/galleries\/(\d+)\/cover/', $res, $gid);
    preg_match('/<span class="pretty">(.*?)<\/span>/', $res, $title);
    preg_match('/<div id="cover">.*?<img.*?src="(.*?)"/', $res, $cover);
    preg_match('/(\d+) pages/', $res, $pages);
    preg_match_all('/<span class="name">(.*?)<\/span>/', $res, $tagMatches);

    return [
        'title' => $title[1] ?? "Miko #$id",
        'cover' => $cover[1] ?? "",
        'gid' => $gid[1] ?? "",
        'pages' => $pages[1] ?? 35,
        'tags' => isset($tagMatches[1]) ? array_slice($tagMatches[1], 0, 5) : ['miko', 'high-school']
    ];
}