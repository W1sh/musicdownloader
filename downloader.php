<?php
include_once './vendor/madcodez/youtube-downloader/src/YTDownloader.php';
final class Downloader{
    const FIREFOX = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0";
    const FILE_SEPARATOR_WINDOWS = "\\";
    const FILE_SEPARATOR_LINUX = "/";
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
        $ext="";
        $dLogger->info('Started filtering results based on received $flags->'.'"'.$flags[0].'"'.' parameter');
        switch($flags[0]){
            case "-showall": 
                
                var_dump($dls);
            break;
            case "-v": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "Video Only") !== false;
                });
                switch(reset($possibleDls)["itag"]){
                    case "137": case "136": case "135": case "134": case "133": case "160": 
                        $ext="mp4";
                        break;
                    case "248": case "247": case "244": case "243": case "242": case "278": 
                        $ext="webm";
                        break;
                }
                break;
            case "-a": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") !== false;
                });
                switch(reset($possibleDls)["itag"]){
                    case "140": 
                        $ext="mp4";
                        break;
                    case "171": case "249": case "250": case "251":
                        $ext="webm";
                        break;
                }
                break;
            case "-av": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "Audio Only") === false 
                    && strpos($item["type"], "Video Only") === false;
                });
                switch(reset($possibleDls)["itag"]){
                    case "18": case "22": 
                        $ext="mp4";
                        break;
                    case "36": case "17": 
                        $ext="3gp";
                        break;
                    case "43":
                        $ext="webm";
                        break;
                }
            break;
            default:
                $possibleDls = array_filter($dls, function($item) use ($flags){
                    return strpos($item["type"], strtoupper(substr($flags[0], 1)) ) !== false;
                });
                $ext=substr($flags[0], 1);
                break;
            /*case "-mp4": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "MP4") !== false;
                });
                $ext="mp4";
            break;
            case "-3gp": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "3GP") !== false;
                });
                $ext="3gp";
            break;
            case "-webm": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "WEBM") !== false;
                });
                $ext="webm";
            break;
            case "-m4a": 
                $possibleDls = array_filter($dls, function($item){
                    return strpos($item["type"], "M4A") !== false;
                });
                $ext="m4a";
            break;*/
        }
        if(sizeof($possibleDls)>0){
            $chosenURL = reset($possibleDls);
            print_r($chosenURL);
            $dCacher = new Cacher();
            $dir = $dCacher->fetch('directory');
        
            //print_r($possibleDls[0]);
            //echo $ext;
            /*$dLogger->info('Found best match with $url->'.'"'.$merged[0]["url"].'"');
            $dLogger->info('Created file with name: '.'"'.$title.'.'.$ext.'"')
            print_r(static::shortenURL($merged[0]['url']));
            //print_r(urlencode($merged[0]["url"]));

            $clientOS = php_uname("s");
            $dLogger->info('Identified client operating system as '.'"'.$clientOS.'"');
            $bytes = static::consumeURL($merged[0]["url"]);
            $fileName = ($clientOS == "Windows NT")
            ? $dir.static::FILE_SEPARATOR_WINDOWS.$title.'.'.$ext
            : $dir.static::FILE_SEPARATOR_LINUX.$title.'.'.$ext;
            file_put_contents($fileName, $bytes);
            $dLogger->info('Saved file to location: '.'"'.getcwd().static::FILE_SEPARATOR_WINDOWS.$fileName.'"');*/
        }else{
            $dLogger->warning()('Couldn\'t find a link to download.');
        }
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
    /*
    **  Function to shorten a url using Google's http://goo.gl/ URL shortener
    **  @param $url -> url to shorten
    **  @return -> shortened url as string or an error message
    */
    private static function shortenURL($url)
    {
        //print_r($url);
        $ch = curl_init();
        $params = '{"url": '.$url.'}';
        $strng = 'http://tinyurl.com/api-create.php?url='.$url;
        curl_setopt($ch, CURLOPT_URL, $strng);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, static::FIREFOX);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        return curl_exec($ch);
    }
}