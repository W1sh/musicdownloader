<?php
include 'downloader.php';
$helpText = "help";
$howToUseText = "how to use";

function parseArguments($arguments){
    $isFlag = substr($arguments[1], 0 ,1) == "-";
    if (!$isFlag) download();
    switch ($arguments[1]) {
        case "-init": 
            try {
                init($arguments[2]);
            } catch (Exception $e){
                echo("Failed to create dir");
            }
            break;
        case "-help": 
            echo $helpText;
            break;
        case "-config": 
            config();
            break;
        default: 
            echo $howToUseText;
            break;
    }
}

function config(){
    echo "config my brodda";
}

function init($name){
    $dir="./".$name;
    if (!mkdir($dir, 0777, true)) {
        throw new Exception ("Error: No can do");
    }else{
        echo "init my brodda on folder ".$name;
    }
}

function download(){
    echo "download my brodda";
}

echo parseArguments($argv).PHP_EOL;