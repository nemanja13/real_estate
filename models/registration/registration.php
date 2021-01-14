<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $firstName=trim($_POST["firstName"]);
        $lastName=trim($_POST["lastName"]);
        $password=trim($_POST["password"]);
        $passwordConfirm=trim($_POST["passwordConfirm"]);
        $phoneNumber=trim($_POST["phoneNumber"]);
        $email=trim($_POST["email"]);
        $captchaText=trim($_POST["captchaText"]);
        $idRole=1;
        
        $errors=[];

        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/", $firstName)){
            $errors[]="The name is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/", $lastName)){
            $errors[]="The first name is not in good format";
        }
        if(!preg_match("/^\w{10,}$/", $password)){
            $errors[]="The last name is not in good format";
        }
        if(!preg_match("/^\+?\d{7,20}$/", $phoneNumber)){
            $errors[]="The password is not in good format";
        }
        if($password!==$passwordConfirm){
            $errors[]="The password is not the same";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="The email is not in good format";
        }
        if($captchaText!=$_SESSION["captcha_text"]){
            $errors[]="You did not enter a matching word";
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $password=md5($password);
            $query="INSERT INTO user VALUES (null, ?, ?, ?, ?, ?, DEFAULT, ?)";
            $prepare=$connection->prepare($query);
            try{
                $prepare->execute([$firstName, $lastName, $password, $phoneNumber, $email, $idRole]);
                $code=201;
                $data=["success"=>"You have successfully registered"];
            }catch(PDOException $e){
                $code=500;
                $data=["error"=>"A user with this email already exists"];
                writeError($e->getMessage());
            }
        }
    }
    echo json_encode($data);
    http_response_code($code);
?>