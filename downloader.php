<?php
include_once './vendor/madcodez/youtube-downloader/src/YTDownloader.php';
class Downloader{
    const FIREFOX = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0";
    const FILE_SEPARATOR_WINDOWS = "\\";
    const FILE_SEPARATOR_LINUX = "/";
    private $dLogger;
    private $youtubedl;
    private $dCacher;
    public function __construct()
    {
        $this->dLogger = new Logger("Downloader");
        $this->youtubedl = new YTDownloader();
        $this->dCacher = new Cacher();
    }
    public function findBestMatch($strng, $url)
    {
        //TODO:
    }
    public function singleDownload($url, $flags): void{
        
        $this->dLogger->info('Call to method singleDownloader with parameters $url->'.'"'.$url.'" and $flags->'.'"'.$url.'"');
        $results = $this->youtubedl->getDownloadLinks($url);
        $info = $results["info"];
        $title = $info["Title"];
        $dls = $results["dl"];

        $this->dLogger->info('Started filtering results based on received $flags->'.'"'.$flags[0].'"'.' parameter');
        if(sizeof($flags)>1){
            switch($flags[0]){
                case "-showall": 
                    $possibleDls = $this->filterResults($dls, $flags[1]);
                    echo"Available urls:\n";
                    $counter = 0;
                    foreach ($possibleDls as $dl) {
                        echo "\t".$counter++.": ".$dl["type"]. " -> ".$this->shortenURL($dl["url"])."\n";
                    }
                    $line = $this->readline("Which one should downloaded? (insert index number): ");
                    $chosenURL = $this->shortenURL($possibleDls[intval($line)]["url"]);
                    print_r($chosenURL);
                    return;
                default: 
                    break;    
            }
        }else{
            if(strpos($flags[0], "showall") > 0){
                echo"Available urls:\n";
                foreach ($dls as $dl) {
                    echo "\t".$dl["type"]. " -> " .$this->shortenURL($dl["url"])."\n";
                }
                $line = $this->readline("Command: ");
                echo "USER INPUT: ".$line;
                return;
            }else{
                $possibleDls = $this->filterResults($dls, $flags[0]);
                $ext = $this->getExtension($possibleDls, $flags[0]);
            }
        }
        if(sizeof($possibleDls)>0){
            $chosenURL = reset($possibleDls);
            $dCacher = new Cacher();
        
            $this->dLogger->info('Found best match with $url->'.'"'.$chosenURL["url"].'"');
            $this->dLogger->info('Created file with name: '.'"'.$title.'.'.$ext.'"');
            print_r($this->shortenURL($chosenURL['url']));

            $bytes = $this->consumeURL($chosenURL["url"]);
            download($bytes, $title, $ext);
        }else{
            $this->dLogger->warning('Couldn\'t find a link to download.');
        }
    }
    public function playlistDownload($url): void
    {
        //TODO:
    }
    /*
    **  Function to consume the video url 
    **  @param $url -> url to consume
    **  @return -> bytes of the video
    */
    private function consumeURL($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, static::FIREFOX);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING,"");
        curl_setopt($ch, CURLOPT_COOKIEJAR,"cookiejar.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE,"cookiefile.txt");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /*
    **  Function to shorten a url using tinyurl API
    **  @param $url -> url to shorten
    **  @return -> shortened url as string
    */
    private function shortenURL($url)  {  
        $ch = curl_init();  
        $timeout = 5;  
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
        $data = curl_exec($ch);  
        curl_close($ch);  
        return $data;  
    }

    /*
    **  Function to filter download links based on a received flag
    **  @param $results -> download links
    **  @param $flag -> filter
    **  @return -> filtered results as array
    */
    private function filterResults($results, $flag){
        $possibleDls = array();
        switch($flag){
            case "-v": 
                $possibleDls = array_filter($results, function($item){
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
                $possibleDls = array_filter($results, function($item){
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
                $possibleDls = array_filter($results, function($item){
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
                $possibleDls = array_filter($results, function($item) use ($flag){
                    return strpos($item["type"], strtoupper(substr($flag, 1)) ) !== false;
                });
                $ext=substr($flag, 1);
                break;
        }
        return $possibleDls;
    }
     /*
    **  Function to get the extension for the file, from a array of possible links
    **  @param $results -> download links
    **  @param $flag -> filter
    **  @return -> extension as string
    */
    private function getExtension($results, $flag){
        $ext = "";
        switch($flag){
            case "-v": 
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
                switch(reset($possibleDls)["itag"]){
                    case "140": 
                        $ext = "mp4";
                        break;
                    case "171": case "249": case "250": case "251":
                        $ext = "webm";
                        break;
                }
                break;
            case "-av": 
                switch(reset($possibleDls)["itag"]){
                    case "18": case "22": 
                        $ext = "mp4";
                        break;
                    case "36": case "17": 
                        $ext = "3gp";
                        break;
                    case "43":
                        $ext = "webm";
                        break;
                }
            break;
            default:
                $ext = substr($flag, 1);
                break;
        }
        return $ext;
    }

    private function download($bytes, $title, $ext)
    {
        $dir = $this->dCacher->fetch('directory');
        $clientOS = php_uname("s");
        $this->dLogger->info('Identified client operating system as '.'"'.$clientOS.'"');
        $fileName = ($clientOS == "Windows NT")
                ? $dir.static::FILE_SEPARATOR_WINDOWS.$title.'.'.$ext
                : $dir.static::FILE_SEPARATOR_LINUX.$title.'.'.$ext;
        file_put_contents($fileName, $bytes);
        $this->dLogger->info('Saved file to location: '.'"'.getcwd().static::FILE_SEPARATOR_WINDOWS.$fileName.'"');
    }

    // SHOULD BE MOVED TO UTILS.PHP
    private function readline($prompt = null){
        if($prompt){
            echo $prompt;
        }
        $fp = fopen("php://stdin","r");
        $line = rtrim(fgets($fp, 1024));
        return $line;
    }
}
