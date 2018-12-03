<?php
class Logger {
    private $file = "script.log";

    function info($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s INFO: %s".PHP_EOL, $time, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }

    function warning($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s WARNING: %s".PHP_EOL, $time, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }

    function error($strng, $e){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ERROR: %s EXCEPTION: %s".PHP_EOL, $time, $strng, $e);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }

    function alert($strng){
        $time = date('Y-m-d H:i:s');
        $log = sprintf("%s ALERT: %s".PHP_EOL, $time, $strng);
        file_put_contents($this->file, $log, FILE_APPEND|LOCK_EX);
    }
}