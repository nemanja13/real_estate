<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"]) && isset($_POST["agent"])){

        $firstName=trim($_POST["firstName"]);
        $lastName=trim($_POST["lastName"]);
        $phoneNumber=trim($_POST["phoneNumber"]);
        $email=trim($_POST["email"]);
        $agentDesc=trim($_POST["agentDesc"]);
        $agentType=trim($_POST["agentType"]);
        $linkedin=trim($_POST["linkedin"]);
        $idAgent=trim($_POST["agent"]);
        
        $errors=[];

        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/", $firstName)){
            $errors[]="The name is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/", $lastName)){
            $errors[]="The first name is not in good format";
        }
        if(!preg_match("/^\+?\d{7,20}$/", $phoneNumber)){
            $errors[]="The password is not in good format";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="The email is not in good format";
        }
        if(!filter_var($linkedin, FILTER_VALIDATE_URL)){
            $errors[]="The linkedin url is not in good format";
        }
        if(!preg_match("/^[\w\.,]+(\s[\w\.,]+)*$/", $agentDesc)){
            $errors[]="The biography is not in good format";
        }
        if(empty($agentType)){
            $errors[]="You must choose profession";
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{

            $query="UPDATE user u INNER JOIN agent a ON u.idUser=a.idUser SET u.firstName=?, u.lastName=?, u.phone=?, u.email=?, a.description=?, a.idType=?, linkLinkedin=? WHERE a.idAgent=?";
            $prepare=$connection->prepare($query);
            try{
                if($prepare->execute([$firstName, $lastName, $phoneNumber, $email, $agentDesc, $agentType, $linkedin, $idAgent])){
                    $code=204;
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