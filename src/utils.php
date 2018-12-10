<?php
include_once"cacher.php";

define('SEPARATOR_WIN', '\\');
define('SEPARATOR_LINUX', '/');

function readline($prompt = null)
{
    if($prompt) echo $prompt;
    $fp = fopen("php://stdin","r");
    $line = rtrim(fgets($fp, 1024));
    return $line;
}

function createDir($name, $dLogger){
    $clientOS = php_uname("s");
    $dir= getcwd().($clientOS == "Windows NT" ? SEPARATOR_WIN : SEPARATOR_LINUX).$name;
    if (!mkdir($dir, 0777, true)) {
        $dLogger->alert('Failed to create directory with $name: '.'"'.$name.'".');
        throw new Exception ("Error: No can do");
    }else{
        $cacher=new Cacher();
        $cacher->store("directory", sprintf("%s", $name));
        $dLogger->info('Created default downloads folder with name: '.$name.' at '.'"'.$dir.'"');
    }
}