<?php
include_once 'src/downloader.php';
include_once 'src/logger.php';
include_once 'src/cacher.php';
include_once 'src/utils.php';
$helpText = "Help \n -init <name>: creates a folder with the inserted name, if name is left empty, the folder name will be downloads; \n 
-config <> \n";
$howToUseText = "how to use";


function parseArguments($arguments){
    
    $mdLogger=new Logger("Music Downloader");
    $isFlag = substr($arguments[1], 0 ,1) == "-";
    if (!$isFlag){ 
        download($arguments, $mdLogger);
    } else {
        $mdLogger->info("Called the \"".$arguments[1]."\" flag");
        switch ($arguments[1]) {
            case "-init": 
                
                break;
            case "-help": 
                global $helpText;
                echo $helpText;
                break;
            case "-config": 
                config($arguments, $mdLogger);
                break;
            default: 
                global $howToUseText;
                echo $howToUseText;
                break;
        }
    }
}

function config($mArg, $dLogger){
    $cacher=new Cacher();
        //Method to give flags to the config.json and search the url for the requested video/song
        switch($mArg[2]){
            case "-flag":
                switch($mArg[3]){
                    case "-v": case"-a": case"-av": case"-mp4": case"-3gp": case"-webm": case"-m4a":
                    $cacher->store("flags",$mArg[3]);
                    break;
                default:
                    echo("Invalid flag, you can use the following ones:\n\t".
                     "-v: Video only; \n\t".
                     "-a: Audio only; \n\t".
                     "-av: Audio and Video; \n\t".
                     "-mp4: Extension Mp4; \n\t".
                     "-3gp: Extension 3gp; \n\t".
                     "-webm: Extension webm; \n\t".
                     "-m4a: Extension m4a;");
                    break;
                }
            break;
            case "-dir":
                $cacher->store("directory", sprintf("%s", $mArg[3]));
            break;
            case "-clear":
                $files = glob($cacher->fetch("directory").'/*'); // get all file names
                foreach($files as $file){ // iterate files
                    if(is_file($file))
                    unlink($file); // delete file
                }
            //podemos incluir tbm
            //$cacher->store("directory", "");*/
            break;
            default:
            break;
        }
        $dLogger->info("Config argument \"".$mArg[2]."\"");
}
/*
    **  Function to create a folder to hold the downloads
    **  @param $name -> name of the folder
    **  @param $dLogger -> instance of logger
    **  @throws Exception -> If the folder couldn't be created
    **  @return -> none
    */
function init($name, $dLogger){
    
}

function download($arguments, $dLogger){
    $dLogger->info('Call to method download with parameters $arguments: '.'"'.print_r($arguments, true).'".');
    $isVideo = strpos($arguments[1], "https://www.youtube.com/watch") !== false;
    $isPlaylist = strpos($arguments[1], "https://www.youtube.com/playlist") !== false;
    $downloader = new Downloader();
    if(sizeof($arguments)>2){
        $flags = array_splice($arguments, 2);
    }else{
        $cacher = new Cacher();
        $flags = $cacher->fetch("flags");
    }
    if($isVideo){
        $downloader->singleDownload($arguments[1], $flags);
    }else if($isPlaylist){
        $downloader->playlistDownload($arguments[1], $flags);
    }else{
        $dLogger->alert('Invalid $url: '.'"'.$arguments[1].'".');
        throw new Exception ("Invalid url received");
    }
}

echo parseArguments($argv).PHP_EOL;