<?php
include_once 'logger.php';

class Cacher {
    private $logger;
    private $file;
    /*
    **  Constructor
    **  @param $path -> path to the configuration file
    */
    public function __construct($path = "config.json")
    {   
        $this->file = $path;
        $this->logger = new Logger("Cacher");
    }
    /*
    **  Function to store information in the configuration file
    **  @param $key -> parameter to search in the file
    **  @param $data -> value to replace/store in the file
    **  @throws Exception -> If the key does not exist in the configuration file
    **  @return -> none
    */
    public function store($key, $data) 
    {
        $this->logger->info('Call to method store with parameters: $key->'.'"'.$key.'"'.' and $data->'.'"'.$data.'".');
        if(($json = $this->read('store', array($key, $data))) !== false){
            if(isset($json[$key])){
                $json[$key] = $data;
                file_put_contents($this->file, json_encode($json));
            }else{
                $this->logger->alert('Failed to identify $key->'.'"'.$key.'".');
                throw new Exception("Non-existant key in configuration file.");
            }
        };
    }
    /*
    **  Function to retrieve information from the configuration file
    **  @param $key -> parameter to search in the file
    **  @throws Exception -> If the key does not exist in the configuration file
    **  @return -> value corresponding to the key
    */
    public function fetch($key) 
    {
        $this->logger->info('Call to method fetch with parameters: $key->'.'"'.$key.'".');
        $json = $this->read('fetch', array($key));
        if(isset($json[$key])){
            $this->logger->info('Fetched value: '.'"'.$json[$key].'"'.' with $key->'.'"'.$key.'".');
            return $json[$key];
        }else{
            $this->logger->alert('Failed to retrieve $key->'.'"'.$key.'". $key does not exist in the configuration file');
            throw new Exception("Non-existant key in configuration file.");
        }
    }
    /*
    **  Function to read the information contained in the configuration file
    **  @param $mName -> method name to call after config file is created
    **  @param $mParam -> method parameters to append to method call after config file is created
    **  @return -> configuration file as json
    */
    public function read($mName = false, $mParam = array())
    {   
        $this->logger->info('Call to method read with parameters: $mName->'.'"'.$mName.'"'.' and $mParam->'.'"'.str_replace("\n", "", print_r($mParam, true)).'".');
        if (is_file($this->file)) {
            $this->logger->info('File found.');
            $json = json_decode(file_get_contents($this->file), true);
            return $json;
        } else {
            $this->logger->warning('File config.json doesn\'t exist.');
            $this->logger->info('Running file initialization.');
            $this->initialize($mName, $mParam);
            return false;
        }
    }
    /*
    **  Function to create the configuration file with default settings
    **  @param $mName -> method name to call after config file is created
    **  @param $mParam -> method parameters to append to method call after config file is created
    **  @return -> none
    */
    public function initialize($mName, $mParam) 
    {   
        $this->logger->info('Call to method read with parameters: $mName->'.'"'.$mName.'"'.' and $mParam->'.'"'.str_replace("\n", "", print_r($mParam, true)).'".');
        $this->logger->info('Created configuration file with name "config.json".');
        $this->logger->info('Started configuration file with value "directory": "downloads".');
        $this->logger->info('Started configuration file with value "flags": "[]".');
        file_put_contents($this->file, 
            sprintf("{\"directory\":\"%s\",\"flags\":[%s]}",
            (sizeof($mParam) > 1 && ($mParam[0] == "directory")) === true ? $mParam[1] : "downloads",
            (sizeof($mParam) > 1 && ($mParam[0] == "flags")) === true ? $mParam[1] : ""));
        switch($mName){
            case false: 
                $this->read();
                break;
            case "fetch": 
                $this->fetch($mParam[0]);
                break;
        }
    }
}