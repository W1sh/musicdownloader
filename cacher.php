<?php
include 'logger.php';
class Cacher {
    private $logger;
    public function __construct(){
        $this->$logger = new Logger("cacher");
    }
    /*
    **  Function to store information in the configuration file
    **  @param $key -> parameter to search in the file
    **  @param $data -> value to replace/store in the file 
    **  @return -> none
    */
    function store($key, $data) {
        $this->$logger->info('Call to function store with $key->'.$key.' and $data->'.$data);
        $json = read();
        $json[$key] = $data;
        file_put_contents($file, json_encode($json));
    }
    /*
    **  Function to retrieve information from the configuration file
    **  @param $key -> parameter to search in the file
    **  @return -> value corresponding to the key
    */
    function fetch($key) {
        $this->$logger->info('Call to function retrieve with $key->'.$key);
        $json = read();
        print_r($json[$key]);
        return $json[$key];
    }
    /*
    **  Function to read the information contained in the configuration file
    **  @return -> configuration file as json
    */
    function read() {
        $this->$logger->info('Call to function read');
        $file = "config.json";
        if (is_file($file)) {
            $this->$logger->info('File found.');
            $json = json_decode(file_get_contents($file), true);
            return $json;
        } else {
            $this->$logger->warning('File config.json doesn\'t exist.');
            $this->$logger->info('Running file initialization.');
            $this->initialize('read');
        }
    }
    /*
    **  Function to create the configuration file with default settings
    **  @param? $callback -> (optional) callback to preceding function
    **  @return -> none
    */
    function initialize($callback) {
        $this->$logger->info('Created configuration file with name "config.json".');
        $this->$logger->info('Started configuration file with value "directory": "downloads"');
        $this->$logger->info('Started configuration file with value "flags": "[]"');
        file_put_contents("config.json", "{\"directory\":\"downloads\",\"flags\":[]}");
        $this->$callback();
    }
}
$cacher = new Cacher();
$cacher->read();