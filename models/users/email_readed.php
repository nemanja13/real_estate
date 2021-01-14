<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
    $query="SELECT * FROM contact WHERE seen=1";
    $prepare=$connection->prepare($query);
    try{
        if($prepare->execute()){
            $code=200;
            $data=$prepare->fetchAll();
        }
    }
    catch(PDOException $e){
        $code=500;
        $data=["error_message"=>"An error has occurred, please try again later"];
        writeError($e->getMessage());
    }
    
   
    echo json_encode($data);
    http_response_code($code);
?>