<?php
    session_start();
    header("Content-type: application/json");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    
    $query="SELECT * FROM real_estate rs INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) INNER JOIN category cat ON cat.idCategory=rs.idCategory INNER JOIN user u ON u.idUser=rs.idUser WHERE rs.deleted=1 AND rs.idRealEstate NOT IN (SELECT prs.idRealEstate FROM real_estate prs INNER JOIN visit v ON prs.idRealEstate=v.idRealEstate INNER JOIN agent_visit av ON av.idVisit=v.idVisit INNER JOIN visit_outcome vo ON av.idAgentVisit=vo.idAgentVisit)";
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