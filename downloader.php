<?php
include_once './vendor/madcodez/youtube-downloader/src/YTDownloader.php';
final class Downloader{
    const FIREFOX = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0";
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
        $dLogger = new Logger("Downloader");
        $dLogger->info('Call to method singleDownloader with parameters $url->'.'"'.$url.'" and $flags->'.'"'.$url.'"');
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
        
        $dLogger->info('Started filtering results based on received $flags parameter');
        switch($flags[0]){
            case "-v": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Video Only") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
                break;
            case "-a": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
            case "-av": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") === false 
                    && strpos($item["type"], "Video Only") === false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
            case "-mp4": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "MP4") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
            case "-3gp": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "3GP") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
            case "-webm": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "WEBM") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
            case "-m4a": 
                $filteredArray = array_filter($dls, function($item){
                    return strpos($item["type"], "M4A") !== false;
                });
                $merged = array_merge($possibleDls, $filteredArray);
            break;
        }
        $bytes = static::consumeURL($merged[0]["url"]);
        var_dump($bytes);
        //echo $merged[0]["url"];
    }
    public static function playlistDownload($url): void{
        //TODO:
    }
    private static function consumeURL($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, static::FIREFOX);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch,CURLOPT_ENCODING,"");
        curl_setopt($ch, CURLOPT_COOKIEJAR,"1.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE,"2.txt");
        //curl_setopt($ch,CURLOPT_REFERER,"googlevideo.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
      /*$error = curl_error($ch);
        echo $error;*/
        return curl_exec($ch);
    }
}