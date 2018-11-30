<?php
include(downloader.php);
$helpText = "help";
$howToUseText = "how to use";

function parseArguments($arguments){
    $isFlag = substr($arguments[1], 0 ,1) == "-";
    if(!$isFlag) download();
    switch($arguments[1]){
        case "-init": 
            init();
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

function init(){
    echo "init my brodda";
}

function download(){
    echo "download my brodda";
}

echo parseArguments($argv).PHP_EOL;