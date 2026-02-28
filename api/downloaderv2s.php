<?php
require_once 'config.php';
set_time_limit(0); 
$id = $_GET['id'] ?? '';
$gid = $_GET['gid'] ?? '';
$pages = $_GET['pages'] ?? 35;

$zip = new ZipArchive();
$zip_name = "Miko_$id.zip";

if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE) {
    for ($i = 1; $i <= $pages; $i++) {
        $server = "t" . (($i % 4) + 1);
        // Kita simpen extension aslinya bbi biar gak rusak! [cite: 2026-02-21]
        $formats = ['webp', 'jpg', 'png'];
        $img_data = null;
        $ext_found = 'jpg';

        foreach ($formats as $ext) {
            $url = "https://$server.nhentai.net/galleries/$gid/{$i}.$ext";
            if ($ext == 'webp') $url = "https://$server.nhentai.net/galleries/$gid/{$i}t.webp";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $img_data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                $ext_found = $ext;
                break;
            }
        }
        
        if ($img_data) {
            // Nama tetep urut, tapi extension sesuai aslinya biar gak korup bbi! [cite: 2026-02-21]
            $zip->addFromString("miko_page_$i.$ext_found", $img_data);
        }
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zip_name);
    readfile($zip_name);
    unlink($zip_name); 
    exit;
}