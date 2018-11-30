<?php
final class Downloader{
    public static function Instance(){
        static $inst = null;
        if ($inst === null) {
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
        //TODO:
    }
    public function playlistDownload($url): void{
        //TODO:
    }
}