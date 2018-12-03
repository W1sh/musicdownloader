<?php 
include 'vendor\madcodez\youtube-downloader\src\YTDownloader.php';
$yt = new YTDownloader();

$links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=jGow4nmYkkA");

print_r($links);