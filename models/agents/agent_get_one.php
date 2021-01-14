<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
   if(isset($_POST["id"])){
       $id=$_POST["id"];
       $query="SELECT * FROM user u INNER JOIN agent a ON a.idUser=u.idUser WHERE u.idUser=?";
       $prepare=$connection->prepare($query);
       $queryType="SELECT * FROM type";
       $prepareType=$connection->prepare($queryType);
       try{
           if($prepare->execute([$id]) && $prepareType->execute()){
               $code=200;
               $data["agent"]=$prepare->fetch();
               $data["type"]=$prepareType->fetchAll();
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