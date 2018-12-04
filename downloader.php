<?php
include 'vendor\madcodez\youtube-downloader\src\YTDownloader.php';
final class Downloader{
    private $yt;
    protected static $instance = null;
    public static function Instance(){
        if (!isset(static::$instance)) {
            $yt = new YTDownloader();
            $inst = new UserFactory();
            static::$instance = new static;
        }
        return static::$instance;
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
    public function singleDownload($url, $type): void{
        $this->$yt->getDownloadLinks($url);
        
        
        //TODO:
    }
    public function playlistDownload($url): void{
        //TODO:
    }
}