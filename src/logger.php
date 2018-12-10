<?php
class Logger {
    private $file = "logs/script.log";
    private $channel = "default";
    /*
    **  Constructor
    **  @param $ch -> channel name
    */
    public function __construct($ch){
        $this->channel = $ch;
    }
    /*
    **  Function to store information in the log file
    **  @param $strng -> string to store in the file
    **  @return -> none
    */
    function info($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s INFO: (%s) %s".PHP_EOL, $time, $this->channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    /*
    **  Function to store warnings in the log file
    **  @param $strng -> string to store in the file
    **  @return -> none
    */
    function warning($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s WARNING: (%s) %s".PHP_EOL, $time, $this->channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    /*
    **  Function to store errors in the log file
    **  @param $strng -> string to store in the file
    **  @param $e -> exception error message
    **  @return -> none
    */
    function error($strng, $e){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ERROR: (%s) %s EXCEPTION: %s".PHP_EOL, $time, $this->channel, $strng, $e);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    /*
    **  Function to store alerts in the log file
    **  @param $strng -> string to store in the file
    **  @return -> none
    */
    function alert($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ALERT: (%s) %s".PHP_EOL, $time, $this->channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    /*
    **  Function to clear the log file
    **  @return -> none
    */
    function clear(){
        file_put_contents($this->file, "");
    }
}