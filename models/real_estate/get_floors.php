<?php
    header("Content-Type: application/json");
    require "../../config/connection.php";

    $data=null;
    $code=400;

    $query1="SELECT * FROM floor_status";
    $query2="SELECT * FROM floor ORDER BY idFloor";
    try{
        $floor_status=ExecuteQuery($query1);
        $floor=ExecuteQuery($query2);
        $data["floor_status"]=$floor_status;
        $data["floor"]=$floor;
        $code=200;
    }catch(PDOException $e){
        $code=500;
        $data=["error_message"=>"An error has occurred, please try again later"];
        writeError($e->getMessage());
    }
    http_response_code($code);
    echo json_encode($data);
?>