<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
    $query="SELECT * FROM visit v INNER JOIN real_estate rs ON rs.idRealEstate=v.idRealEstate INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN category cat ON cat.idCategory=rs.idCategory INNER JOIN user u ON u.idUser=v.idUser WHERE rs.approved=1 AND rs.deleted=0 AND v.idVisit NOT IN (SELECT vi.idVisit FROM visit vi INNER JOIN agent_visit av ON av.idVisit=vi.idVisit)";
    $prepare=$connection->prepare($query);
    $queryAgents="SELECT * FROM agent a INNER JOIN user u ON u.idUser=a.idUser WHERE idType=1";
    $prepareAgents=$connection->prepare($queryAgents);
    try{
        if($prepare->execute() && $prepareAgents->execute()){
            $code=200;
            $data["visits"]=$prepare->fetchAll();
            $data["agents"]=$prepareAgents->fetchAll();
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