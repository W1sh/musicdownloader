<?php
include_once './vendor/madcodez/youtube-downloader/src/YTDownloader.php';
final class Downloader{
    const FIREFOX = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0";
    private function __construct()
    {
        //Private constructer so it can't be instatialized
    }
    private function __clone()
    {
        //Private cloner so it can't be cloned
    }
    public static function findBestMatch($strng, $url)
    {
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
        print_r($merged[0]);
        $bytes = static::consumeURL($merged[0]["url"]);
        echo strlen($bytes);
    }
    public static function playlistDownload($url): void
    {
        //TODO:
    }
    /*
    **  Function to consume the video url 
    **  @param $url -> url to consume
    **  @return -> bytes of the video
    */
    private static function consumeURL($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, static::FIREFOX);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION,3);
        
        $error = curl_error($ch);
        echo $error;

        return curl_exec($ch);
    }
    /*
    **  Function to shorten a url using Google's http://goo.gl/ URL shortener
    **  @param $url -> url to shorten
    **  @return -> shortened url as string or an error message
    */
    private static function shortenURL($url)
    {
        $ch = curl_init("http://goo.gl/api/url?url=" . urlencode($url));
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($result = curl_exec($ch))
        {
            $json = json_decode($result);
            if($error = ($json->error_message))
            {
                echo "$error";
                exit;
            }
            $short_url = $json->short_url;
            return "$short_url";
        } else {
            echo curl_error($ch);
        }
    }
}