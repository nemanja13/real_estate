<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"]) && isset($_POST["user"])){
        $errors=[];
        $roleAgent=3;

        $firstName=trim($_POST["firstName"]);
        $lastName=trim($_POST["lastName"]);
        $password=trim($_POST["password"]);
        if($_POST["submit"]=="user"){
            $passwordConfirm=trim($_POST["passwordConfirm"]);
            $passwordConfirm=md5($passwordConfirm);
            $querySelect="SELECT * FROM user WHERE password=?";
            $prepareSelect=$connection->prepare($querySelect);
            $prepareSelect->execute([$passwordConfirm]);
            if($prepareSelect->rowCount()!==1){
                $errors[]="A user with this password does not exist";
            }
        }else{
            $idRole=trim($_POST["role"]);
        }
        $phoneNumber=trim($_POST["phoneNumber"]);
        $email=trim($_POST["email"]);
        $userId=trim($_POST["user"]);
        

        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/", $firstName)){
            $errors[]="The name is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/", $lastName)){
            $errors[]="The first name is not in good format";
        }
        if(!empty($password)){
            if(!preg_match("/^\w{10,}$/", $password)){
                $errors[]="The last name is not in good format";
            }
        }
        if(!preg_match("/^\+?\d{7,20}$/", $phoneNumber)){
            $errors[]="The password is not in good format";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="The email is not in good format";
        }
        

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{

            if($_POST["submit"]=="user"){
                $query="UPDATE user SET firstName=?, lastName=?, phone=?, email=?";
                if(!empty($password)){
                    $query.=", password=?";
                }
                $query.=" WHERE idUser=? AND password=?";
                $prepare=$connection->prepare($query);
            }else{
                $query="UPDATE user SET firstName=?, lastName=?, phone=?, email=?, idRole=?";
                if(!empty($password)){
                    $query.=", password=?";
                }
                $query.=" WHERE idUser=?";
                $prepare=$connection->prepare($query);
            }



            
            try{
                if($_POST["submit"]=="user"){
                    if(!empty($password)){
                        $password=md5($password);
                        if($prepare->execute([$firstName, $lastName, $phoneNumber, $email, $password, $userId, $passwordConfirm])){
                            $code=200;
                        }else{
                            $code=422;
                            $data=["error"=>"A user with this password does not exist"];
                        }
                    }else{
                        if($prepare->execute([$firstName, $lastName, $phoneNumber, $email, $userId, $passwordConfirm])){
                            $code=200;
                        }else{
                            $code=422;
                            $data=["error"=>"A user with this password does not exist"];
                        }
                    }
                }else{
                    if(!empty($password)){
                        $password=md5($password);
                        if($prepare->execute([$firstName, $lastName, $phoneNumber, $email, $idRole, $password, $userId])){
                            $code=200;
                        }
                    }else{
                        if($prepare->execute([$firstName, $lastName, $phoneNumber, $email, $idRole, $userId])){
                            $code=200;
                        }
                    }
                    if($idRole==$roleAgent){
                        $queryAgentInsert="INSERT INTO agent VALUES(null, '', ?, null, '', DEFAULT)";
                        $prepareAgentInsert=$connection->prepare($queryAgentInsert);
                        $prepareAgentInsert->execute([$userId]);
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