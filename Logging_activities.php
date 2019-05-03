<?php
function hasAccess($username,$error_msg,$event){
    //Write action to txt log
    $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "AUDIT: ".$error_msg.PHP_EOL.
            "User: ".$username.PHP_EOL.
            
            "Event:".$event.PHP_EOL.
            "-------------------------".PHP_EOL;
    //-
    file_put_contents('./Logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
}
function logInfoError($username,$error_msg){
    //Write action to txt log
    $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "INFO: ".$error_msg.PHP_EOL.
            "User: ".$username.PHP_EOL.
            "-------------------------".PHP_EOL;
    //-
    file_put_contents('./Logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
}
function logDebugError($username,$error_msg){
    //Write action to txt log
    $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "DEBUG: ".$error_msg.PHP_EOL.
            "User: ".$username.PHP_EOL.
            "-------------------------".PHP_EOL;
    //-
    file_put_contents('./Logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
}
?>