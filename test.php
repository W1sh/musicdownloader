<?php 
include 'vendor/madcodez/youtube-downloader/src/YTDownloader.php';
$yt = new YTDownloader();

$links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=HOSzLGrfdsM");

echo isset($links);
echo $links === false;
print_r($links);