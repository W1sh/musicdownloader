<?php
include_once 'downloader.php';
include_once 'logger.php';
include_once 'cacher.php';
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
                try {
                    init($arguments[2],$mdLogger);
                } catch (Exception $e){
                    echo("Failed to create dir");
                }
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
                    echo("Invalid flag \n \t".
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

function init($name,$dLogger){
    
    $dir=$name;
    if (!mkdir($dir, 0777, true)) {
        throw new Exception ("Error: No can do");
    }else{
        $cacher=new Cacher();
        $cacher->store("directory", sprintf("%s", $dir));
        $dLogger->info("Successful init. The folder \"".$name."\" was created");
    }
}

function download($arguments, $dLogger){
    $cacher=new Cacher();
    //Method to give flags to the config.json and search the url for the requested video/song
    switch ($arguments[3]){
        case "-a":
        break;

        case "-v":
        break;

        case "-m4a":
        break;

        case "-mp4":
        break;

        case "-3gp":
        break;

        case "-webm":
        break;

        default:
        //audio and video

        break;
    }
    $dLogger->info("Music downloder argument ".$arguments[2]);
}

echo parseArguments($argv).PHP_EOL;