<?php
include 'downloader.php';
include_once 'logger.php';
include 'cacher.php';
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

function config($arguments, $dLogger){
    $cacher=new Cacher();
        //Method to give flags to the config.json and search the url for the requested video/song
        switch($arguments[2]){
            case "-flag":
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
            break;
            case "-dir":
            $cacher->store("directory", sprintf("%s", $arguments[3]));
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
        $dLogger->info("Config argument \"".$arguments[2]."\"");
}
/*
    **  Function to create a folder to hold the downloads
    **  @param $name -> name of the folder
    **  @param $dLogger -> instance of logger
    **  @throws Exception -> If the folder couldn't be created
    **  @return -> none
    */
function init($name, $dLogger){
    $dir=$name;
    if (!mkdir($dir, 0777, true)) {
        $dLogger->alert('Failed to create directory with $name: '.'"'.$name.'".');
        throw new Exception ("Error: No can do");
    }else{
        $cacher=new Cacher();
        $cacher->store("directory", sprintf("%s", $dir));
        $dLogger->info('Successful init. The folder '.'"'.$name.'"'.' was created.');
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