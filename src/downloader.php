<?php
include_once './vendor/madcodez/youtube-downloader/src/YTDownloader.php';
include_once 'logger.php';
include_once 'cacher.php';
include_once 'utils.php';

class Downloader{
    const FIREFOX = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0";
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
        if($results === false){
            $this->dLogger->info('Video at $url->'.'"'.$url.'" is unavailable');
            return;
        }
        $info = $results["info"];
        $vowels = array("/", "\\", "<", ">", ":", "\"", "|", "?", "*");
        $title = str_replace("/", "", $info["Title"]);
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
                    $line = readline("Which one should downloaded? (insert index number): ");
                    $chosen = $dls[intval($line)];
                    $ext = $this->getExtension($chosen);
                    $fileLocation = $this->getFileLocation();
                    $fileName = $title.$ext;
                    $bytes = $this->consumeURL($chosen["url"]);
                    $this->download($bytes, $fileLocation, $fileName);
                    return;
                default: 
                    break;    
            }
        }else{
            if(strpos($flags[0], "showall") > 0){
                echo"Available urls:\n";
                $counter = 0;
                foreach ($dls as $dl) {
                    echo "\t".$counter++.": ".$dl["type"]. " -> ".$this->shortenURL($dl["url"])."\n";
                }
                $line = readline("Which one should downloaded? (insert index number): ");
                $chosen = $dls[intval($line)];
                $ext = $this->getExtension($chosen);
                $fileLocation = $this->getFileLocation();
                $fileName = $title.$ext;
                $bytes = $this->consumeURL($chosen["url"]);
                $this->download($bytes, $fileLocation, $fileName);
                return;
            }else{
                $possibleDls = $this->filterResults($dls, $flags[0]);
            }
        }
        if(sizeof($possibleDls)>0){
            $chosen = reset($possibleDls);
            $dCacher = new Cacher();
            $this->dLogger->info('Found best match with $url->'.'"'.$chosen["url"].'"');
            echo "DONE: ".$title.PHP_EOL;
            $ext = $this->getExtension($chosen);
            $fileLocation = $this->getFileLocation($title, $ext);
            $fileName = $title.$ext;
            $bytes = $this->consumeURL($chosen["url"]);
            $this->download($bytes, $fileLocation, $fileName);
        }else{
            $this->dLogger->warning('Couldn\'t find a link to download.');
        }
    }
    public function playlistDownload($url, $flags): void
    {
        $pHtml = $this->consumeURL($url);
        $pInterestPoint = "\"videoId\":\"";
        if (is_string($pHtml) && strlen($pHtml)>0)
        {
                $lastPos = 0;
                $positions = array();
                while (($lastPos = strpos($pHtml, $pInterestPoint, $lastPos))!== false)
                {
                    $positions[] = $lastPos;
                    $lastPos = $lastPos + strlen($pInterestPoint);
                }
                $videoIds = array();
                foreach ($positions as $value)
                {
                    $endIndex = stripos(substr($pHtml, $value + strlen($pInterestPoint)), "\"");
                    $videoURL = substr($pHtml, $value + strlen($pInterestPoint), $endIndex);
                    $videoIds[] = $videoURL;
                }
                print_r($videoIds);
                print_r(array_values(array_unique($videoIds)));

                if(strpos($flags[0], "showall") > 0){
                    echo "WUT";
                    // log error
                }else if(sizeof($flags)>1){
                    echo "WUT2";
                    // log error
                }else{
                    foreach(array_unique($videoIds) as $id){
                        $this->singleDownload("https://www.youtube.com/watch?v=".$id, $flags);
                    }
                    return;
                }
        }else{
            // log error
            return;
        }
    }
    /*
    **  Function to consume the video url 
    **  @param $url -> url to consume
    **  @return -> bytes of the video
    */
    public function consumeURL($url)
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
    private function filterResults($results, $flag)
    {
        $possibleDls = array();
        switch($flag)
        {
            case "-v": 
                $possibleDls = array_filter($results, function($item){
                    return strpos($item["type"], "Video Only") !== false;
                });
                break;
            case "-a": 
                $possibleDls = array_filter($results, function($item){
                    return strpos($item["type"], "Audio Only") !== false;
                });
                break;
            case "-av": 
                $possibleDls = array_filter($results, function($item){
                    return strpos($item["type"], "Audio Only") === false 
                    && strpos($item["type"], "Video Only") === false;
                });
            break;
            default:
                $possibleDls = array_filter($results, function($item) use ($flag){
                    return strpos($item["type"], strtoupper(substr($flag, 1)) ) !== false;
                });
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
    private function getExtension($object)
    {
        $ext = "";
        switch($object["itag"])
        {
            case "137": case "136": case "135": case "134": case "133": case "160": 
                $ext=".mp4";
                break;
            case "248": case "247": case "244": case "243": case "242": case "278": 
                $ext=".webm";
                break;
            case "140": 
                $ext = ".mp4";
                break;
            case "171": case "249": case "250": case "251":
                $ext = ".webm";
                break;
            case "18": case "22": 
                $ext = ".mp4";
                break;
            case "36": case "17": 
                $ext = ".3gp";
                break;
            case "43":
                $ext = ".webm";
                break;
        }
        return $ext;
    }
    /*
    **  Function to get the file location
    **  @return -> desired location for the file
    */
    private function getFileLocation()
    {
        $dir = $this->dCacher->fetch('directory');
        $clientOS = php_uname("s");
        $this->dLogger->info('Identified client operating system as '.'"'.$clientOS.'"');
        $dirLocation = (getcwd().($clientOS == "Windows NT" ? SEPARATOR_WIN : SEPARATOR_LINUX).$dir);
        if(!is_dir($dirLocation)){
            $this->dLogger->warning('Couldn\'t find file at location: '.'"'.$dirLocation.'"');
            createDir($dir, $this->dLogger);
        }
        return $dirLocation;
    }
    /*
    **  Function to place downloaded bytes into a file
    **  @param $bytes -> bytes received after consuming the url
    **  @param $fileLocation -> where to place the file (includes file name)
    **  @return -> none
    */
    private function download($bytes, $fileLocation, $fileName): void
    {
        file_put_contents($fileLocation.(php_uname("s") == "Windows NT" 
            ? SEPARATOR_WIN : SEPARATOR_LINUX).$fileName, $bytes);
        $this->dLogger->info('Saved file to location: '.'"'.(getcwd().(php_uname("s") == "Windows NT" 
            ? SEPARATOR_WIN : SEPARATOR_LINUX).$fileLocation).'"');
    }
}