<?php
    session_start();
    header("Content-type: application/json");
    require_once "../../config/connection.php";
    require_once "functions.php";
    $data["error_message"]=["An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $password=trim($_POST["password"]);
        $email=trim($_POST["email"]);
        

        if(!preg_match("/^\w{10,}$/", $password)){
            $data["errorPassword"]=["The password is not in good format"];
            $code=422;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $data["errorEmail"]=["The email is not in good format"];
            $code=422;
        }

        if($code!=422){
            $password=md5($password);
            $query="SELECT * FROM user u INNER JOIN role r ON u.idRole=r.idRole WHERE u.email=? AND u.password=?";
            $prepare=$connection->prepare($query);

            $queryEmail="SELECT idUser FROM user WHERE email=?";
            $prepare2=$connection->prepare($queryEmail);

            $queryPassword="SELECT idUser FROM user WHERE password=?";
            $prepare3=$connection->prepare($queryPassword);

            try{
                $prepare2->execute([$email]);
                if($prepare2->rowCount()!==1){
                    $data["errorEmail"]=["User with that email does not exist"];
                    $code=422;
                }
                $prepare3->execute([$password]);
                if($prepare3->rowCount()<1){
                    $data["errorPassword"]=["User with that password does not exist"];
                    $code=422;
                }
                if($code!=422){
                    $prepare->execute([$email, $password]);
                    if($prepare->rowCount()==1){
                        $_SESSION["user"]=$prepare->fetch();
                        user_login($_SESSION["user"]->idUser);
                        $code=200;
                    }else{
                        $code=422;
                        $data["errorPassword"]=["Not a valid password or email"];
                        $to=$email;
                        $text="Someone tried to login on your account.";
                        $subject="Message from Real Estate login";
                        $header="From: http://www.projekti.epizy.com";
                        mail($to, $subject, $text, $header);
                    }
                }
            }catch(PDOException $e){
                $code=500;
                $data["error_message"]=["An error has occurred, please try again later"];
                writeError($e->getMessage());
            }
           
        }
    }
    echo json_encode($data);
    http_response_code($code);
?>