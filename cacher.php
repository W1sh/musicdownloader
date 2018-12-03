<?php
function store($key, $data){
    // store information in file
    $file = "config.json";
    $json = json_decode(file_get_contents($file), true);
    $json[$key] = $data;
    file_put_contents($file, json_encode($json));
}

function fetch($key){
    $file = "config.json";
    $json = json_decode(file_get_contents($file), true);
    return $json[$key];
    // retrieve information from file
}

function initialize(){
    // create file with default options if it doesn't exist
    $file = "config.json";
    /*if(!is_file($file)){
        $file=fopen("config.json", "w");*/
        /*fwrite($file, "./downloads");*/
        $json = json_decode(file_get_contents($file), true);
        //print_r($json);
        print_r($json["directory"]);
        print_r(sizeof($json["flags"]));
        print_r($json["flags"]);
    //}
}

initialize();