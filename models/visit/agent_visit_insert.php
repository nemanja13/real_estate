<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $idAgent=trim($_POST["agent"]);
        $idVisit=trim($_POST["visit"]);
        
        $errors=[];

        
        if(empty($idAgent)){
            $errors[]="You must choose the agent";
        }
        if(empty($idVisit)){
            $errors[]="You are not submiting the form";
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $query="INSERT INTO agent_visit VALUES (null, ?, ?)";
            $prepare=$connection->prepare($query);

            $queryEmailAgent="SELECT email FROM user u INNER JOIN agent a ON a.idUser=u.idUser WHERE a.idAgent=?";
            $prepareEmailAgent=$connection->prepare($queryEmailAgent);

            $querySelectVisit="SELECT * FROM visit v INNER JOIN user u ON u.idUser=v.idUser INNER JOIN real_estate rs ON rs.idRealEstate=v.idRealEstate WHERE v.idVisit=?";
            $prepareSelectVisit=$connection->prepare($querySelectVisit);
            try{
                if($prepare->execute([$idVisit, $idAgent])){
                    $code=201;
                    $data=["success"=>"You have successfully assign agent"];
                }
                if($prepareEmailAgent->execute([$idAgent]) && $prepareSelectVisit->execute([$idVisit])){
                    $to=$prepareEmailAgent->fetch()->email;
                    $info=$prepareSelectVisit->fetch();
                    $dateFromat=date("l jS \of M Y H:i", strtotime($info->dateVisit." ".$info->time));
                    $message="You have been assign to the visit of property $info->title, $info->address for the $dateFromat \nClient= $info->firstName $info->lastName\nContact: $info->phone, $info->email";
                    $subject="Assign to the visit";
                    mail($to,$subject,$message);
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