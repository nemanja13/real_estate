<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
    if(isset($_POST["idUser"]) && isset($_POST["submit"])){
        $idUser=$_POST["idUser"];
        $query="SELECT * FROM user WHERE idUser=?";
        $prepare=$connection->prepare($query);
        $queryRole="SELECT * FROM role";
        try{
            if($prepare->execute([$idUser])){
                $code=200;
                $data["user"]=$prepare->fetch();
                $data["role"]=ExecuteQuery($queryRole);
            }
        }
        catch(PDOException $e){
            $code=500;
            $data=["error_message"=>"An error has occurred, please try again later"];
            writeError($e->getMessage());
        }
    }
    
   
    echo json_encode($data);
    http_response_code($code);
?>