<?php
class Logger {
    private $file = "script.log";
    private $channel = "default";
    public function __construct($ch){
        $this->$channel = $ch;
    }
    function info($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s INFO: (%s) %s".PHP_EOL, $time, $this->$channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    function warning($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s WARNING: (%s) %s".PHP_EOL, $time, $this->$channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    function error($strng, $e){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ERROR: (%s) %s EXCEPTION: %s".PHP_EOL, $time, $this->$channel, $strng, $e);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
    function alert($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ALERT: (%s) %s".PHP_EOL, $time, $this->$channel, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
}