<?php 
include 'D:\GitHub\musicdownloader\vendor\athlon1600\youtube-downloader\src\YouTubeDownloader.php';
$yt = new YouTubeDownloader();

$links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=QxsmWxxouIM");

var_dump($links);