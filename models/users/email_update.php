<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
    if(isset($_POST["submit"]) && isset($_POST["idContact"])){
        $idContact=$_POST["idContact"];
        $query="UPDATE contact SET seen=1 WHERE idContact=?";
        $prepare=$connection->prepare($query);
        try{
            if($prepare->execute([$idContact])){
                $code=204;
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