<?php
    header("Content-Type: application/json");
    require "../../config/connection.php";

    $data=null;
    $code=400;

    $query="SELECT * FROM user u INNER JOIN agent a ON a.idUser=u.idUser INNER JOIN type t ON a.idType=t.idType LIMIT 3";
    try{
        $agents=ExecuteQuery($query);
        if($agents){
            $data=$agents;
            $code=200;
        }
    }catch(PDOException $e){
        $code=500;
        $data=["error_message"=>"An error has occurred, please try again later"];
        writeError($e->getMessage());
    }
    http_response_code($code);
    echo json_encode($data);
?>