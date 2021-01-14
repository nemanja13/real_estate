<?php
    header("Content-type: application/json");
    require "../../config/connection.php";
    require "functions.php";
    require "real_estate_get_one.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"]) && isset($_POST["id"])){
        $idRealEstate=$_POST["id"];
        $property_type=$_POST["property_type"];
        $title=trim($_POST["title"]);
        $description=trim($_POST["description"]);
        $price=trim($_POST["price"]);
        $address=trim($_POST["address"]);
        $houseSize=trim($_POST["houseSize"]);
        $rooms=$_POST["rooms"];
        $bedrooms=trim($_POST["bedrooms"]);
        $bathrooms=trim($_POST["bathrooms"]);
        $heating=$_POST["heating"];
        $documentation=$_POST["documentation"];
        $user=$_POST["user"];
        if(isset($_POST["features"])){
            $features=$_POST["features"];
        }
        if(isset($_POST["floor_status"])){
            $floor_status=$_POST["floor_status"];
        }
        if(isset($_POST["number_of_floors"])){
            $number_of_floors=$_POST["number_of_floors"];
        }
        $location=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/assets/img/";
        $fileTypes=array("image/jpg", "image/jpeg", "image/png");
        $max_size=2*1024*1024;
        $errors=[];
        if(isset($_FILES["images"])){
            $files=$_FILES["images"];
            if(count($_FILES["images"]["name"])){
                for($i=0; $i<count($files["name"]); $i++){
                    $name=$files["name"][$i];
                    $size=$files["size"][$i];
                    $type=$files["type"][$i];
    
                    if(!in_array($type, $fileTypes)){
                        $errors[]="Your image format is not supported ($name)";
                    }
                    if($size>$max_size){
                        $errors[]="Your image is bigger then 2MB ($name)";
                    }
                }
            }
        }

        if(!preg_match("/^\w+(\s\w+)*$/", trim($title))){
            $errors[]="The title is not in good format";
        }
        if(!preg_match("/^\d+$/", trim($price))){
            $errors[]="The price is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,30}(\,?\s[A-ZŠĐŽČĆa-zšđžčć]{2,30})*(\s\d*\w?)*$/", $address)){
            $errors[]="The address is not in good format";
        }
        if(!preg_match("/^\d{2,4}$/", trim($houseSize))){
            $errors[]="The house size is not in good format";
        }
        if(!preg_match("/^\d+$/", trim($bedrooms))){
            $errors[]="The number of bedrooms is not in good format";
        }
        if(!preg_match("/^\d+$/", trim($bathrooms))){
            $errors[]="The number of bathrooms is not in good format";
        }
        if(empty($rooms)){
            $errors[]="You must choose the property rooms";
        }
        if(empty($documentation)){
            $errors[]="You must choose the property documentation";
        }
        if(empty($heating)){
            $errors[]="You must choose the property heating";
        }
        if(empty($user)){
            $errors[]="You must login";
        }

        if($property_type==2){
            if(empty($floor_status)){
                $errors[]="You must choose the property floor status";
            }
            if($floor_status==2){
                if(!preg_match("/^\d{1,2}$/", $number_of_floors)){
                    $errors[]="The number of floors is not in good format";
                }
            }
          }
          if($property_type!=2){
            $floor_status=2;
            if(!preg_match("/^\d{1,2}$/", $number_of_floors)){
                $errors[]="The number of floors is not in good format";
            }
          }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{

            $query="UPDATE real_estate SET title=?, description=?, address=?, size=?, bedrooms=?, bathrooms=?, idHeating=?, idRooms=?,idDocumentation=?, idFloorStatus=? WHERE idRealEstate=? AND idUser=?";
            $prepare=$connection->prepare($query);
            try{
                $prepare->execute([$title, $description, $address, $houseSize, $bedrooms, $bathrooms, $heating, $rooms, $documentation, $floor_status, $idRealEstate, $user]);

                if(!empty($number_of_floors)){
                    $prepareNOF=$connection->prepare("UPDATE number_of_floors SET number_of_floors=? WHERE idRealEstate=?");
                    $prepareNOF->execute([$number_of_floors, $idRealEstate]);
                }

                if(!empty($features)){
                    $features=explode(",", $features);                    
                    $features_real_estate=ExecuteQuery("SELECT fre.idFeature FROM feature_real_estate fre INNER JOIN real_estate rs ON fre.idRealEstate=rs.idRealEstate INNER JOIN feature f ON f.idFeature=fre.idFeature WHERE rs.approved=1 AND rs.idRealEstate=$idRealEstate");
                    foreach($features_real_estate as $fs){
                        $old_features[]=$fs->idFeature;
                    }

                    foreach($features as $i=>$f){
                        if(!in_array($f, $old_features)){
                            $queryFeatures="INSERT INTO feature_real_estate VALUES(null, :idFeature, :idRealEstate)";
                            $prepareFeatures=$connection->prepare($queryFeatures);
                            $prepareFeatures->bindParam(":idRealEstate", $idRealEstate);
                            $prepareFeatures->bindParam(":idFeature", $f);
                            $prepareFeatures->execute();
                        }
                    }

                    foreach($old_features as $of){
                        if(!in_array($of, $features)){
                            $queryFeaturesDelete="DELETE FROM feature_real_estate WHERE idFeature=:idFeature AND idRealEstate=:idRealEstate";
                            $prepareFeaturesDelete=$connection->prepare($queryFeaturesDelete);
                            $prepareFeaturesDelete->bindParam(":idRealEstate", $idRealEstate);
                            $prepareFeaturesDelete->bindParam(":idFeature", $of);
                            $prepareFeaturesDelete->execute();
                        }
                    }
                }
                if(!empty($price)){
                    $old_price=preg_replace("/,/", "",get_price($idRealEstate)[0]);
                    if($price!=$old_price){
                        $queryPrice="INSERT INTO price VALUES(null, ?, ?, DEFAULT)";
                        $preparePrice=$connection->prepare($queryPrice);
                        $preparePrice->execute([$idRealEstate, $price]);
                    }
                }
                if(isset($files)){
                    if(count($files["name"])){
                        for($i=0; $i<count($files["name"]); $i++){
                            $fileName=$files["name"][$i];
                            $tmp_name=$files["tmp_name"][$i];
                            $size=$files["size"][$i];
                            $type=$files["type"][$i];
        
        
                            $images=create_images($fileName, $tmp_name, $size, $type);
                            
                            $queryImage="INSERT INTO image VALUES(null, ?, ?, ?, ?)";
                            $prepareImage=$connection->prepare($queryImage);
                            $prepareImage->execute([$images["image"], $images["medium"], $images["thumbnail"], $idRealEstate]);
                            
                        }
                    }
                }
                
                $code=201;
                $data=["success"=>"You have successfully edit your property"];
                
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