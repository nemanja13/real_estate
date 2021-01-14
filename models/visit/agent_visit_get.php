<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
   if(isset($_POST["id"])){
       $id=$_POST["id"];
       $query="SELECT * FROM agent_visit av INNER JOIN visit v ON av.idVisit=v.idVisit INNER JOIN real_estate rs ON rs.idRealEstate=v.idRealEstate INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN category cat ON cat.idCategory=rs.idCategory INNER JOIN user u ON u.idUser=v.idUser WHERE rs.approved=1 AND rs.deleted=0 AND av.idAgentVisit NOT IN (SELECT vo.idAgentVisit FROM visit_outcome vo INNER JOIN agent_visit av ON av.idAgentVisit=vo.idAgentVisit) AND av.idAgent=?";
       $prepare=$connection->prepare($query);
       $queryOutcome="SELECT * FROM outcome";
       $prepareOutcome=$connection->prepare($queryOutcome);
       $queryAgent="SELECT * FROM agent WHERE idUser=?";
       $prepareAgent=$connection->prepare($queryAgent);
       try{
           $prepareAgent->execute([$id]);
           $idAgent=$prepareAgent->fetch()->idAgent;
           if($prepare->execute([$idAgent]) && $prepareOutcome->execute()){
               $code=200;
               $data["visits"]=$prepare->fetchAll();
               $data["outcome"]=$prepareOutcome->fetchAll();
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