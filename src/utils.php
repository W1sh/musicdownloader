<?php
include_once"cacher.php";

function readline($prompt = null)
{
    if($prompt) echo $prompt;
    $fp = fopen("php://stdin","r");
    $line = rtrim(fgets($fp, 1024));
    return $line;
}

function createDir($name, $dLogger){
    $clientOS = php_uname("s");
    $dir= getcwd().($clientOS == "Windows NT" ? Downloader::FILE_SEPARATOR_WINDOWS
    : Downloader::FILE_SEPARATOR_LINUX).$name;
    if (!mkdir($dir, 0777, true)) {
        $dLogger->alert('Failed to create directory with $name: '.'"'.$name.'".');
        throw new Exception ("Error: No can do");
    }else{
        $cacher=new Cacher();
        $cacher->store("directory", sprintf("%s", $name));
        $dLogger->info('Created default downloads folder with name: '.$name.' at '.'"'.$dir.'"');
    }
}