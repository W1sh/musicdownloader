<?php 
include 'vendor\madcodez\youtube-downloader\src\YTDownloader.php';
$yt = new YTDownloader();

$links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=aJOTlE1K90k&t=0s&index=2&list=PLx0sYbCqOb8TBPRdmBHs5Iftvv9TPboYG");

print_r($links);