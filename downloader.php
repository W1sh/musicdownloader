<?php
include_once 'vendor\madcodez\youtube-downloader\src\YTDownloader.php';
final class Downloader{
    private function __construct(){
        //Private constructer so it can't be instatialized
    }
    private function __clone(){
        //Private cloner so it can't be cloned
    }
    public static function findBestMatch($strng, $url){
        //TODO:
    }
    public static function singleDownload($url, $flags): void{
        $youtube = new YTDownloader();
        $results = $youtube->getDownloadLinks($url);
        $info = $results["info"];
        $title = ($info["Title"]);
        $dls = $results["dl"];
        //print_r($dls);

        /*$links = array();
        for ($i = 1; $i <= sizeof($dls); $i++) {
            array_push($links, $dls[$i]["url"]);
            echo ($dls[$i]["type"]).PHP_EOL;
            
        }
        print_r($links);*/
        $possibleDls = array();
        
        switch($flags[0]){
            case "-v": 
                echo "TOU CA";
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Video Only") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged);
                break;
            case "-a": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged);
            break;
            case "-av": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") === false 
                    && strpos($item["type"], "Video Only") === false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged); 
            break;
            case "-mp4": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "MP4") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged); 
            break;
            case "-3gp": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "3GP") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged);
             break;
            case "-webm": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "WEBM") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged); 
            break;
            case "-m4a": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "M4A") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                print_r($merged);
            break;
        }
        echo $flags;
        //TODO:
    }
    public static function playlistDownload($url): void{
        //TODO:
    }
}