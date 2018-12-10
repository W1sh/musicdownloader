<?php
include_once 'src/downloader.php';
include_once 'src/logger.php';
include_once 'src/cacher.php';
include_once 'src/utils.php';

function parseArguments($arguments)
{
    $mdLogger=new Logger("Music Downloader");
    $isFlag = substr($arguments[1], 0 ,1) == "-";
    if (!$isFlag)
    { 
        download($arguments, $mdLogger);
    } else {
        $mdLogger->info("Called the \"".$arguments[1]."\" flag");
        switch ($arguments[1]) 
        {
            case "-help": 
                echo(PHP_EOL."The following text is supposed to help you use this script correctly: ".PHP_EOL."\n\t".
                "To download a video, simply paste the link after calling the script \n\t".
                "You can add flags(-mp4, webm, 3gp) to identify which extension you prefer \n\t".
                "If no flag is given, the script will automatically download the link with the best quality \n\t".
                "Example 1: php musicdownloader.php \"https://www.youtube.com/watch?v=tohW1VLqCFo\" \n\t".
                "Example 2: php musicdownloader.php \"https://www.youtube.com/watch?v=tohW1VLqCFo\" -mp4".PHP_EOL."\n\t".
                "The script can also be used to download entire playlists from youtube, like downloading \n\t".
                "individual videos, you can use flags to specify which extension is preferred \n\t".
                "Example 1: php musicdownloader.php \"https://www.youtube.com/playlist?list=PLVrHtnQEDQVbrm-d1PmFqS-zw4FTT_7hp\" \n\t".
                "Example 2: php musicdownloader.php \"https://www.youtube.com/playlist?list=PLVrHtnQEDQVbrm-d1PmFqS-zw4FTT_7hp\" -mp4".PHP_EOL."\n\t".
                "You can also configure the script to download to a specific folder or to always download using a specific flag \n\t".
                "Example 1: Specify download directory -> php musicdownloader.php -config -dir \"-insert-dir-name-here-\" \n\t".
                "Example 2: Specify default flags -> php musicdownloader.php -config -flags \"-insert-flag-here-\" \n\t".
                "Example 3: Clear all configurations -> php musicdownloader.php -config -clear".PHP_EOL."\n\t".
                "Possible flags used for downloading videos: \n\t".
                "\t-v: Video only; \n\t".
                "\t-a: Audio only; \n\t".
                "\t-av: Audio and Video; \n\t".
                "\t-mp4: Extension Mp4; \n\t".
                "\t-3gp: Extension 3gp; \n\t".
                "\t-webm: Extension webm; \n\t".
                "\t-m4a: Extension m4a; \n\t");
                break;
            case "-config": 
                config($arguments, $mdLogger);
                break;
            case "-clear-logs": 
                $mdLogger->clear();
                break;
            default: 
                echo("Invalid call to script, please use \"php musicdownloader.php -help\" for general information");
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
                    if(is_file($file)) unlink($file); // delete file
                }
            //podemos incluir tbm
            //$cacher->store("directory", "");*/
            break;
            default:
            break;
        }
        $dLogger->info("Config argument \"".$mArg[2]."\"");
}

function download($arguments, $dLogger){
    $dLogger->info('Call to method download with parameters $arguments: '.'"'.str_replace("\n", "", print_r($arguments, true)).'".');
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