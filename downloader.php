<?php
include 'vendor\madcodez\youtube-downloader\src\YTDownloader.php';
final class Downloader{
    private $yt;
    public static function Instance(){
        static $inst = null;
        if ($inst === null) {
            $yt = new YTDownloader();
            $inst = new UserFactory();
        }
        return $inst;
    }
    private function __construct(){
        //Private constructer so it can't be instatialized
    }
    private function __clone(){
        //Private cloner so it can't be cloned
    }
    public function findBestMatch($strng, $url){
        //TODO:
    }
    public function singleDownload($url): void{
        // ignore unknows formats
        //TODO:
    }
    public function playlistDownload($url): void{
        //TODO:
    }
}