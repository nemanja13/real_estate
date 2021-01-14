<?php

header("Content-Type: application/json");
$code=400;
$data=["error_message"=>"An error has occurred, bad request"];
if(isset($_POST["id"]) && isset($_POST["submit"])){
    $id=$_POST["id"];
    require_once "../../config/connection.php";

    $querySelect="SELECT * FROM image WHERE idImage=?";
    $prepareSelect=$connection->prepare($querySelect);

    $query="DELETE FROM image WHERE idImage=?";
    $prepare=$connection->prepare($query);
    try{
        $prepareSelect->execute([$id]);
        if(count($prepareSelect->fetchAll())==1){
            $prepare->execute([$id]);
            $code=204;
            $data=["success"=>"Image has been deleted"];
        }
    }catch(PDOException $e){
        $code=500;
        $data=["error_message"=>"An error has occurred, please try again later"];
        writeError($e->getMessage());
    }
}
echo json_encode($data);
http_response_code($code);

?>