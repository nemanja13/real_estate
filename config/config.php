<?php

define("ABSOLUTE_PATH", $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt");

define("ENV_FILE", ABSOLUTE_PATH."/config/.env");
define("LOG_FILE", ABSOLUTE_PATH."/data/log.txt");
define("LOGIN_FILE", ABSOLUTE_PATH."/data/login.txt");
define("ERROR_FILE", ABSOLUTE_PATH."/data/error.txt");

define("SERVER", env("SERVER"));
define("DATABASE", env("DBNAME"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));

function env($name){
    $data = file(ENV_FILE);
    $vrednost = "";
    foreach($data as $key=>$value){
        $config = explode("=", $value);
        if($config[0]==$name){
            $vrednost = trim($config[1]);
        }
    }
    return $vrednost;
}
