<?php
    header("Content-type: application/json");
    require "../../config/connection.php";
    require "functions.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;

    if(isset($_POST["submit"])){

        $title=trim($_POST["title"]);
        $description=trim($_POST["description"]);
        $price=trim($_POST["price"]);
        $property_type=$_POST["property_type"];
        $country=$_POST["country"];
        $city=$_POST["city"];
        $address=trim($_POST["address"]);
        $houseSize=trim($_POST["houseSize"]);
        $rooms=$_POST["rooms"];
        $bedrooms=trim($_POST["bedrooms"]);
        $bathrooms=trim($_POST["bathrooms"]);
        $built=trim($_POST["built"]);
        $category=$_POST["category"];
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
        if(isset($_POST["floor"])){
            $floor=$_POST["floor"];
        }
        $files=$_FILES["images"];
        $location=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/assets/img/";
        $fileTypes=array("image/jpg", "image/jpeg", "image/png");
        $max_size=2*1024*1024;
        $errors=[];

        if(!preg_match("/^\w+(\s\w+)*$/", $title)){
            $errors[]="The title is not in good format";
        }
        if(!preg_match("/^\d+$/", $price)){
            $errors[]="The price is not in good format";
        }
        if(!preg_match("/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,30}(\,?\s[A-ZŠĐŽČĆa-zšđžčć]{2,30})*(\s\d*\w?)*$/", $address)){
            $errors[]="The address is not in good format";
        }
        if(!preg_match("/^\d{2,4}$/", $houseSize)){
            $errors[]="The house size is not in good format";
        }
        if(!preg_match("/^\d+$/", $bedrooms)){
            $errors[]="The number of bedrooms is not in good format";
        }
        if(!preg_match("/^\d+$/", $bathrooms)){
            $errors[]="The number of bathrooms is not in good format";
        }
        if(!preg_match("/^\d{4}$/", $built)){
            $errors[]="The year of construction is not in good format";
        }
        if(empty($property_type)){
            $errors[]="You must choose the property type";
        }
        if(empty($category)){
            $errors[]="You must choose the property category";
        }
        if(empty($country)){
            $errors[]="You must choose the property country";
        }
        if(empty($city)){
            $errors[]="You must choose the property city";
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
        if(!count($_FILES["images"]["name"])){
            $errors[]="You must add a picture of the property";
        }else{
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
            if(empty($floor)){
                $errors[]="You must choose the property floor";
            }
            if(!preg_match("/^\d{1,2}$/", $number_of_floors)){
                $errors[]="The number of floors is not in good format";
            }
            if(is_int($floor)){
              if($floor>$number_of_floors){
                $errors[]="You did not enter an appropriate value for the floor";
              }
            }
          }
          $date=date("Y");
          if($built>$date){
            $errors[]="You did not enter an appropriate value for the year of construction";
          }

        if(count($errors)){
            $data=["errors"=>$errors];
            $code=422;
        }
        else{


            
            $approved=0;
            $deleted=0;
            $query="INSERT INTO real_estate VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, $approved, $deleted)";
            $prepare=$connection->prepare($query);
            try{
                $prepare->execute([$title, $description, $address, $houseSize, $bedrooms, $bathrooms, $built, $property_type, $city, $category, $heating, $rooms, $documentation, $floor_status, $user]);
                $idRealEstate=$connection->lastInsertId();

                if(!empty($number_of_floors)){
                    $prepareNOF=$connection->prepare("INSERT INTO number_of_floors VALUES(null, ?, ?)");
                    $prepareNOF->execute([$idRealEstate, $number_of_floors]);
                }

                if(!empty($features)){
                    $features=explode(",", $features);
                    foreach($features as $i=>$f){
                        $queryFeatures="INSERT INTO feature_real_estate VALUES(null, :idFeature, :idRealEstate)";
                        $prepareFeatures=$connection->prepare($queryFeatures);
                        $prepareFeatures->bindParam(":idRealEstate", $idRealEstate);
                        $prepareFeatures->bindParam(":idFeature", $f);
                        $prepareFeatures->execute();
                    }
                }
                if($property_type!=2){
                    $queryFloor="INSERT INTO floors VALUES(null, ?, ?)";
                    $prepareFloor=$connection->prepare($queryFloor);
                    $prepareFloor->execute([$idRealEstate, $floor]);
                }
                if(!empty($price)){
                    $queryPrice="INSERT INTO price VALUES(null, ?, ?, DEFAULT)";
                    $preparePrice=$connection->prepare($queryPrice);
                    $preparePrice->execute([$idRealEstate, $price]);
                }

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
                $code=201;
                $data=["success"=>"You have successfully add your property"];
                
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