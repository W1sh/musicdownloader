<?php
include 'logger.php';

class Cacher
{
    private $logger;
    private $path;
    public function __construct()
    {
        $this->$path = "config.json";
        $this->$logger = new Logger("cacher");
    }
    /*
     **  Function to store information in the configuration file
     **  @param $key -> parameter to search in the file
     **  @param $data -> value to replace/store in the file 
     **  @return -> none
     */
    function store($key, $data)
    {
        $this->$logger->info('Call to function store with $key->' . $key . ' and $data->' . $data);
        $json = $this->read('store');
        $json[$key] = $data;
        file_put_contents("config.json", json_encode($json));
    }
    /*
     **  Function to retrieve information from the configuration file
     **  @param $key -> parameter to search in the file
     **  @return -> value corresponding to the key
     */
    function fetch($key)
    {
        $this->$logger->info('Call to function retrieve with $key->' . $key);
        $json = $this->read('fetch');
        print_r($json[$key]);
        return $json[$key];
    }
    /*
     **  Function to read the information contained in the configuration file
     **  @return -> configuration file as json
     */
    function read($callback = false)
    {
        $this->$logger->info('Call to function read with $callback->' . ($callback !== false ? $callback : "read"));
        $file = "config.json";
        if (is_file($file)) {
            $this->$logger->info('File found.');
            $json = json_decode(file_get_contents($file), true);
            return $json;
        } else {
            $this->$logger->warning('File config.json doesn\'t exist.');
            $this->$logger->info('Running file initialization.');
            $this->initialize(callback !== false ? "callback" : "read");
        }
    }
    /*
     **  Function to create the configuration file with default settings
     **  @param? $callback -> (optional) callback to preceding function
     **  @return -> none
     */
    function initialize($callback)
    {
        $this->$logger->info('Created configuration file with name "config.json".');
        $this->$logger->info('Started configuration file with value "directory": "downloads"');
        $this->$logger->info('Started configuration file with value "flags": "[]"');
        file_put_contents("config.json", "{\"directory\":\"downloads\",\"flags\":[]}");
        $this->$callback();
    }
}

$cacher = new Cacher();
$cacher->read();
$cacher->store("directory", "/meteaqui/");
$cacher->read();