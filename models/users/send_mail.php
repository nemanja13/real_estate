<?php
    session_start();
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $firstName=trim($_POST["firstName"]);
        $lastName=trim($_POST["lastName"]);
        $email=trim($_POST["email"]);
        $subject=trim($_POST["subject"]);
        $message=trim($_POST["message"]);
        
        $errors=[];

        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/", $firstName)){
            $errors[]="The first name is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/", $lastName)){
            $errors[]="The last name is not in good format";
        }
        if(!preg_match("/^\w+(\s\w+)*$/", $subject)){
            $errors[]="The subject is not in good format";
        }
        if(!preg_match("/^[\w\.,]+(\s[\w\.,]+)*$/", $message)){
            $errors[]="The message is not in good format";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="The email is not in good format";
        }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $seen=0;
            $query="INSERT INTO contact VALUES(null, ?, ?, ?, ?, ?, DEFAULT, ?)";
            $prepare=$connection->prepare($query);
            try{
                if($prepare->execute([$firstName, $lastName, $email, $subject, $message, $seen])){
                    $code=201;
                    $data=["success"=>"You have successfully sent the mail"];
                    $to="nemanjamaksimovic13081999@gmail.com";
                    $text=$firstName." ".$lastName."\n".$message;
                    $header="From: http://www.projekti.epizy.com";
                    mail($to, $subject, $text, $header);
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