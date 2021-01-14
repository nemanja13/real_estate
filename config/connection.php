<?php

require_once "config.php";

zabeleziPristupStranici();

try{
    $connection=new PDO("mysql:host=".SERVER.";dbname=".DATABASE.";charset=UTF8", USERNAME, PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
}catch(PDOException $e){
    echo $e->getMessage();
}

function ExecuteQuery($query){
    global $connection;
    return $connection->query($query)->fetchAll();
}

function zabeleziPristupStranici(){
    $open = fopen(LOG_FILE, "a");
    $date = date('d-m-Y H:i:s');
    fwrite($open, "{$_SERVER['REQUEST_URI']}\t{$date}\t{$_SERVER['REMOTE_ADDR']}\t\n");
    fclose($open);
}
function writeError($error){
    @$open=fopen(ERROR_FILE,"a");
    $write=$error."\t".date('d-m-Y H:i:s')."\n";
    @fwrite($open,$write);
    @fclose($open);
}
?>