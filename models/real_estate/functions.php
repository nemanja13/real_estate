<?php
 require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
function real_estate_get_all($limit, $offset, $sort, $category, $propertyType, $country, $city, $priceMin, $priceMax, $sizeMin, $sizeMax, $numBed, $numBath){
    global $connection;
    $query="SELECT * FROM real_estate rs INNER JOIN user u ON rs.idUser=u.idUser INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) INNER JOIN category cat ON cat.idCategory=rs.idCategory WHERE rs.approved=1 AND rs.deleted=0";
    if($category!=0)
    {
        $query.=" AND cat.idCategory=$category";
    }
    if($propertyType!=0)
    {
        $query.=" AND pt.idPropertyType=$propertyType";
    }
    if($country!=0)
    {
        $query.=" AND c.idCountry=$country";
    }
    if($city!=0)
    {
        $query.=" AND ct.idCity=$city";
    }
    if($numBed!=0)
    {
        $query.=" AND rs.bedrooms=$numBed";
    }
    if($numBath!=0)
    {
        $query.=" AND rs.bathrooms=$numBath";
    }
    if($sizeMin!=0 || $sizeMax!=0)
    {
        $query.=" AND rs.size BETWEEN $sizeMin AND $sizeMax";
    }
    if($priceMin!=0 || $priceMax!=0)
    {
        $query.=" AND p.price BETWEEN $priceMin AND $priceMax";
    }
    if($sort!=0){
        if($sort==1){
            $query.=" ORDER BY rs.title";
        }else if($sort==2){
            $query.=" ORDER BY rs.title DESC";
        }else if($sort==3){
            $query.=" ORDER BY p.price";
        }else if($sort==4){
            $query.=" ORDER BY p.price DESC";
        }else{
            $query.=" ORDER BY rs.idRealEstate";
        }
    }
    if($limit!=0)
    {
        $query.=" LIMIT $limit OFFSET $offset";
    }
    $real_estates=ExecuteQuery($query);
    return $real_estates;
}
function price($price)
{
    $new_price=preg_replace("/\B(?=(\d{3})+(?!\d))/", ",", substr($price, 0, -3));
    return $new_price;
}
function get_property_type(){
    global $connection;
    $query="SELECT * FROM property_type";
    $property_type=ExecuteQuery($query);
    return $property_type;
}
function get_country(){
    global $connection;
    $query="SELECT * FROM country";
    $country=ExecuteQuery($query);
    return $country;
}
function get_rooms(){
    global $connection;
    $query="SELECT * FROM rooms ORDER BY idRooms";
    $rooms=ExecuteQuery($query);
    return $rooms;
}
function get_floor_status(){
    global $connection;
    $query="SELECT * FROM floor_status";
    $floor_status=ExecuteQuery($query);
    return $floor_status;
}
function get_features(){
    global $connection;
    $query="SELECT * FROM feature";
    $features=ExecuteQuery($query);
    return $features;
}
function get_category(){
    global $connection;
    $query="SELECT * FROM category";
    $category=ExecuteQuery($query);
    return $category;
}
function get_documentation(){
    global $connection;
    $query="SELECT * FROM documentation";
    $documentation=ExecuteQuery($query);
    return $documentation;
}
function get_heating(){
    global $connection;
    $query="SELECT * FROM heating";
    $heating=ExecuteQuery($query);
    return $heating;
}
function get_real_estate_user($idUser){
    global $connection; 
    $query="SELECT * FROM real_estate rs INNER JOIN price p ON p.idPrice=(SELECT idPrice FROM price WHERE rs.idRealEstate=idRealEstate ORDER BY date DESC LIMIT 1) INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN image i ON i.idImage=(SELECT idImage FROM image WHERE rs.idRealEstate=idRealEstate ORDER BY idImage LIMIT 1) INNER JOIN category cat ON cat.idCategory=rs.idCategory WHERE rs.approved=1 AND rs.deleted=0 AND rs.idUser=?";
    $prepare=$connection->prepare($query);
    $prepare->execute([$idUser]);
    $real_estates=$prepare->fetchAll();
    if($prepare->rowCount()>0){
        return $real_estates;
    }
}
function create_images($fileName, $tmp_name, $size, $type){
    $files=$_FILES["images"];
    $location=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/assets/img/";
    
    $imageName=time()."_".$fileName;

    $width_medium=600;
    $width_thumbnail=200;
    list($width, $height)=getimagesize($tmp_name);
    $height_medium=($width_medium/$width) * $height;
    $height_thumbnail=($width_thumbnail/$width) * $height;
    switch($type){
        case "image/jpg": case "image/jpeg": $image=imagecreatefromjpeg($tmp_name); break;
        case "image/png": $image=imagecreatefrompng($tmp_name); break;
    }
    $image_medium=imagecreatetruecolor($width_medium, $height_medium);
    $image_thumbnail=imagecreatetruecolor($width_thumbnail, $height_thumbnail);

    imagecopyresampled($image_medium, $image, 0,0,0,0, $width_medium, $height_medium, $width, $height);
    imagecopyresampled($image_thumbnail, $image, 0,0,0,0, $width_thumbnail, $height_thumbnail, $width, $height);

    $imageName_thumbnail=time()."_thumbnail_".$fileName;
    $imageName_medium=time()."_medium_".$fileName;

    
    $location_logo=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/assets/images/logo2.png";
    list($width_logo, $height_logo)=getimagesize($location_logo);
    list($width, $height)=getimagesize($tmp_name);

    $logo=imagecreatefrompng($location_logo);
    imagecopymerge($image, $logo, $width-$width_logo, $height-$height_logo, 0,0, $width_logo, $height_logo, 30);


    switch($type){
        case "image/jpg": case "image/jpeg": imagejpeg($image_medium, $location.$imageName_medium); imagejpeg($image_thumbnail, $location.$imageName_thumbnail); imagejpeg($image, $location.$imageName); break;
        case "image/png": imagepng($image_medium, $location.$imageName_medium); imagepng($image_thumbnail, $location.$imageName_thumbnail); imagepng($image, $location.$imageName); break;
    }
    
    $array["medium"]=$imageName_medium;
    $array["thumbnail"]=$imageName_thumbnail;
    $array["image"]=$imageName;
    return $array;
}
?>