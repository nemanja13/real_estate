<?php
    header("Content-type: application/json");
    require "../../config/connection.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){
        $image=$_FILES["image"];
        $id=$_POST["id"];

        $name=$image["name"];
        $type=$image["type"];
        $size=$image["size"];
        $tmp=$image["tmp_name"];

        $location=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/assets/img_agent/";
        $fileTypes=array("image/jpg", "image/jpeg", "image/png");
        $max_size=2*1024*1024;
        $errors=[];

        if(!in_array($type, $fileTypes)){
            $errors[]="Your image format is not supported ($name)";
        }
        if($size>$max_size){
            $errors[]="Your image is bigger then 2MB ($name)";
        }

        
        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{
            $imageName=time()."_".$name;

            $width_new=250;
            list($width, $height)=getimagesize($tmp);
            $height_new=($width_new/$width) * $height;
            switch($type){
                case "image/jpg": case "image/jpeg": $image=imagecreatefromjpeg($tmp); break;
                case "image/png": $image=imagecreatefrompng($tmp); break;
            }
            $image_new=imagecreatetruecolor($width_medium, $height_medium);
        
            imagecopyresampled($image_medium, $image, 0,0,0,0, $width_new, $height_new, $width, $height);
        
            switch($type){
                case "image/jpg": case "image/jpeg": imagejpeg($image, $location.$imageName); break;
                case "image/png": imagepng($image, $location.$imageName); break;
            }

            
            $query="UPDATE agent SET image=? WHERE idAgent=?";
            $prepare=$connection->prepare($query);
            try{
                if($prepare->execute([$imageName,$id])){
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

    