<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $date=$_POST["date"];
        $time=$_POST["time"];
        $idRealEstate=$_POST["property"];
        $idUser=$_POST["user"];
        
        $errors=[];

        if(!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)){
            $errors["errorDate"]=true;
        }else{
            if(strtotime($date)<=time()){
                $errors["errorDate"]=true;
            }
        }
        if(!preg_match("/^\d{2}:\d{2}$/", $time)){
            $errors["errorTime"]=true;
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $querySelectUser="SELECT * FROM user WHERE idUser=?";
            $querySelectRS="SELECT * FROM real_estate WHERE idRealEstate=? AND idUser!=?";
            $prepareSelectUser=$connection->prepare($querySelectUser);
            $prepareSelectRS=$connection->prepare($querySelectRS);

            $query="INSERT INTO visit VALUES(null, ?, ?, ?, ?)";
            $prepare=$connection->prepare($query);
            try{
                $prepareSelectUser->execute([$idUser]);
                $prepareSelectRS->execute([$idRealEstate,$idUser]);
                if($prepareSelectUser->rowCount()==1 && $prepareSelectRS->rowCount()==1){
                    if($prepare->execute([$date, $time, $idUser, $idRealEstate])){
                        $code=201;
                        $data=["success"=>"You have successfully scheduled a visit"];
                        $to=$prepareSelectUser->fetch()->email;
                        $real_estate=$prepareSelectRS->fetch();
                        $dateFromat=date("l jS \of M Y H:i", strtotime($date." ".$time));
                        $message="You have successfully scheduled a visit to the property $real_estate->title, $real_estate->address for the $dateFromat";
                        $subject="Scheduling a visit";
                        mail($to,$subject,$message);
                    }
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