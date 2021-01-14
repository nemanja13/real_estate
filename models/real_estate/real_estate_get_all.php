<?php
    session_start();
    header("Content-type: application/json");
    require_once "functions.php";
    $data=["error_message"=>"An error has occurred, bad request"];
    $code=400;
    $offset=0;
    $limit=3;
    $category=0;
    $country=0;
    $city=0;
    $numBed=0;
    $numBath=0;
    $priceMin=0;
    $priceMax=0;
    $sizeMin=0;
    $sizeMax=0;
    $propertyType=0;
    $sort=0;
    if(isset($_POST['submit'])){
        if(isset($_POST["page"]) && $_POST["page"]!=""){
            $offset=($_POST["page"]-1)*$limit;
        }
        if(isset($_POST["category"]) && $_POST["category"]!=""){
            $category=$_POST["category"];
        }
        if(isset($_POST["propertyType"]) && $_POST["propertyType"]!=""){
            $propertyType=$_POST["propertyType"];
        }
        if(isset($_POST["country"]) && $_POST["country"]!=""){
            $country=$_POST["country"];
        }
        if(isset($_POST["city"]) && $_POST["city"]!=""){
            $city=$_POST["city"];
        }
        if(isset($_POST["bedrooms"]) && $_POST["bedrooms"]!="" && is_numeric($_POST["bedrooms"])){
            $numBed=trim($_POST["bedrooms"]);
        }
        if(isset($_POST["bathrooms"]) && $_POST["bathrooms"]!="" && is_numeric($_POST["bathrooms"])){
            $numBath=trim($_POST["bathrooms"]);
        }
        if(isset($_POST["priceMin"]) && $_POST["priceMin"]!="" && is_numeric($_POST["priceMin"])){
            $priceMin=trim($_POST["priceMin"]);
        }
        if(isset($_POST["priceMax"]) && $_POST["priceMax"]!="" && is_numeric($_POST["priceMax"])){
            $priceMax=trim($_POST["priceMax"]);
        }
        if(isset($_POST["sizeMin"]) && $_POST["sizeMin"]!="" && is_numeric($_POST["sizeMin"])){
            $sizeMin=trim($_POST["sizeMin"]);
        }
        if(isset($_POST["sizeMax"]) && $_POST["sizeMax"]!="" && is_numeric($_POST["sizeMax"])){
            $sizeMax=trim($_POST["sizeMax"]);
        }
        if(isset($_POST["sort"])){
            $sort=$_POST["sort"];
        }
    }
    try{
        $real_estates=real_estate_get_all($limit, $offset, $sort, $category, $propertyType, $country, $city, $priceMin, $priceMax, $sizeMin, $sizeMax, $numBed, $numBath);
        $total=count(real_estate_get_all(0, 0, 0, $category, $propertyType, $country, $city, $priceMin, $priceMax, $sizeMin, $sizeMax, $numBed, $numBath));
        if(count($real_estates)>=1){
            $data["real_estate"]=$real_estates;
            $pages = ceil($total / $limit);
            $data["pages"]=$pages;
            $code=200;
        }else{
            $code=404;
            $data=["error_message"=>"An error has occurred, not found"];
        }
    }
    catch(PDOException $e){
        $code=500;
        $data=["error_message"=>"An error has occurred, please try again later"];
        writeError($e->getMessage());
    }
    
   
    echo json_encode($data);
    http_response_code($code);
?>