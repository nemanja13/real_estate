<?php

header("Content-Type: application/json");
$code=400;
$data=["error_message"=>"An error has occurred, bad request"];
if(isset($_POST["id"])){
    $id=$_POST["id"];
    require_once "../../config/connection.php";

    $querySelect="SELECT * FROM country WHERE idCountry=?";
    $prepareSelect=$connection->prepare($querySelect);

    $query="SELECT * FROM city WHERE idCountry=?";
    $prepare=$connection->prepare($query);
    try{
        $prepareSelect->execute([$id]);
        if(count($prepareSelect->fetchAll())==1){
            $prepare->execute([$id]);
            $city=$prepare->fetchAll();
            $code=200;
            $data=["city"=>$city];
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