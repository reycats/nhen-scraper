<?php
require_once 'config.php';
set_time_limit(0); 
$id = $_GET['id'] ?? '';
$gid = $_GET['gid'] ?? '';
$pages = $_GET['pages'] ?? 35;

if (!$id || !$gid) die("ID/GID miko gaib asu!");

$zip = new ZipArchive();
$zip_name = "Miko_Hybrid_$id.zip";

if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE) {
    for ($i = 1; $i <= $pages; $i++) {
        $img_data = null;
        $ext_found = 'jpg';
        
        // JALUR 1: NYOBA HD (i1-i4) [cite: 2026-02-21]
        $s_hd = "i" . (($i % 4) + 1);
        $hd_urls = [
            "https://$s_hd.nhentai.net/galleries/$gid/$i.webp",
            "https://$s_hd.nhentai.net/galleries/$gid/$i.jpg",
            "https://$s_hd.nhentai.net/galleries/$gid/$i.png"
        ];

        foreach ($hd_urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_REFERER, "https://nhentai.net/g/$id/");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $img_data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                $ext_found = pathinfo($url, PATHINFO_EXTENSION);
                break;
            }
        }

        // JALUR 2: FAILOVER KE THUMBNAIL (t1-t4) KALAU HD GAGAL [cite: 2026-02-21]
        if (!$img_data) {
            $s_t = "t" . (($i % 4) + 1);
            $t_urls = [
                "https://$s_t.nhentai.net/galleries/$gid/{$i}t.webp",
                "https://$s_t.nhentai.net/galleries/$gid/{$i}t.jpg"
            ];
            foreach ($t_urls as $url) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $img_data = curl_exec($ch);
                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                    $ext_found = pathinfo($url, PATHINFO_EXTENSION);
                    curl_close($ch);
                    break;
                }
                curl_close($ch);
            }
        }
        
        if ($img_data) {
            $zip->addFromString("page_$i.$ext_found", $img_data);
        }
    }
    $zip->close();
    
    // DOWNLOAD FILE [cite: 2026-02-21]
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zip_name);
    header('Content-Length: ' . filesize($zip_name));
    readfile($zip_name);
    unlink($zip_name); 
    exit;
}