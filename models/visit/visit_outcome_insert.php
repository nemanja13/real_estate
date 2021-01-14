<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $idOutcome=trim($_POST["outcome"]);
        $outcomeDescription=trim($_POST["outcomeDescription"]);
        $idVisit=trim($_POST["visit"]);
        
        $errors=[];

        
        if(empty($idOutcome)){
            $errors[]="You must choose the outcome";
        }
        if(empty($idVisit)){
            $errors[]="You are not submiting the form";
        }
        if(empty($outcomeDescription)){
            $errors[]="You must enter the description";
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $query="INSERT INTO visit_outcome VALUES (null, ?, ?, ?, DEFAULT)";
            $prepare=$connection->prepare($query);

            $queryAgentVisit="SELECT * FROM agent_visit WHERE idVisit=?";
            $prepareAgentVisit=$connection->prepare($queryAgentVisit);

            $queryDeleteProperty="UPDATE real_estate rs INNER JOIN visit v ON rs.idRealEstate=v.idRealEstate INNER JOIN agent_visit av ON av.idVisit=v.idVisit SET rs.deleted=1 WHERE av.idAgentVisit IN (SELECT idAgentVisit FROM visit_outcome WHERE idOutcome!=3)";
            $prepareDeleteProperty=$connection->prepare($queryDeleteProperty);
            try{
                $prepareAgentVisit->execute([$idVisit]);
                $AgentVisit=$prepareAgentVisit->fetch();
                $idAgentVisit=$AgentVisit->idAgentVisit;
                if($prepare->execute([$idAgentVisit, $idOutcome, $outcomeDescription])){
                    $prepareDeleteProperty->execute([$idVisit]);
                    $code=201;
                    $data=["success"=>"You have successfully inserted outcome"];
                }
            }catch(PDOException $e){
                $code=500;
                $data=["error_message"=>"An error has occurred, please try again later"];
                writeError($e->getMessage());
            }
        }
    }
    echo json_encode($data);
    http_response_code($code);
?>