<?php
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"]) && isset($_POST["id"])){
        $id=$_POST["id"];
        $deleted=1;
        $query="UPDATE real_estate SET deleted=? WHERE idRealEstate=?";
        $prepare=$connection->prepare($query);
        try{
            if($prepare->execute([$deleted, $id])){
                $code=204;
                $data=["success"=>"You have successfully deleted your property"];
            }
        }catch(PDOException $e){
            $code=500;
            $data=["error_message"=>"An error has occurred, please try again later"];
            writeError($e->getMessage());
        }
    }
http_response_code($code);
echo json_encode($data);
?>