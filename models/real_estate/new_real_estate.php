<?php
    header("Content-Type: application/json");
    require "../../config/connection.php";

    $data=null;
    $code=400;

    $query="SELECT * FROM real_estate rs INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) WHERE rs.approved=1 AND rs.deleted=0 ORDER BY rs.date DESC LIMIT 4";
    try{
        $newest_real_estate=ExecuteQuery($query);
        if($newest_real_estate){
            $data=$newest_real_estate;
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