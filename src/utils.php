<?php
include_once"cacher.php";

define('SEPARATOR_WIN', '\\');
define('SEPARATOR_LINUX', '/');

/*
**  Function to read input line from the command line
**  @param $prompt -> message to display in the console
**  @return -> user input
*/
function readline($prompt = null)
{
    if($prompt) echo $prompt;
    $fp = fopen("php://stdin","r");
    $line = rtrim(fgets($fp, 1024));
    return $line;
}
/*
**  Function to create a folder
**  @param $name -> name of the folder
**  @param $dLogger -> logger to record function
**  @throws Exception -> If the file could not be created
**  @return -> none
*/
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