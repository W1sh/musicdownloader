<?php
function store($key, $data){
    // store information in file
    $json = read();
    $json[$key] = $data;
    file_put_contents($file, json_encode($json));
}

function fetch($key){
    // retrieve information from file
    $json = read();
    print_r($json[$key]);
    return $json[$key];
}

function read(){
    // read the configuration file
    $file = "config.json";
    if(is_file($file)){
        $json = json_decode(file_get_contents($file), true);
        return $json;
    }else{
        initialize(this());
    }
}

function initialize($callback){
    // create configuration file with default settings
    file_put_contents("config.file", "{\"directory\":\"downloads\",\"flags\":[]}");
    $callback();
}