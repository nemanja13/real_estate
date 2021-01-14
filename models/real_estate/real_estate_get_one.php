<?php
require_once "functions.php";

function get_real_estate($idRealEstate){
    global $connection;
    $query="SELECT * FROM real_estate rs INNER JOIN city ct ON rs.idCity=ct.idCity INNER JOIN country c ON c.idCountry=ct.idCountry INNER JOIN property_type pt ON pt.idPropertyType=rs.idPropertyType INNER JOIN category cat ON cat.idCategory=rs.idCategory WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $real_estate=$prepare->fetch();
    if($real_estate){
        return $real_estate;
    }else{
        header("Location: 404.php");
    }
}
function get_images_real_estate($idRealEstate){
    global $connection;
    $query="SELECT * FROM image i INNER JOIN real_estate rs ON rs.idRealEstate=i.idRealEstate WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $images=$prepare->fetchAll();
    return $images;
}
function get_price($idRealEstate){
    global $connection;
    $query="SELECT p.price FROM real_estate rs INNER JOIN price p ON rs.idRealEstate=p.idRealEstate WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=? ORDER BY p.date DESC";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $prices=$prepare->fetchAll();
    if(count($prices)>=1){
        foreach($prices as $p){
            $price[]=price($p->price);
        }
        return $price;
    }else{
        return "NO";
    }
}

function get_floor($idRealEstate){
    global $connection;
    $query="SELECT * FROM real_estate rs INNER JOIN property_type pt ON rs.idPropertyType=pt.idPropertyType WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $real_estate=$prepare->fetch();

    if($real_estate){
        if($real_estate->type=="House"){
            return "ground floor";
        }else{
           $query2="SELECT f.floor FROM real_estate rs INNER JOIN floors fs ON fs.idRealEstate=rs.idRealEstate INNER JOIN floor f ON f.idFloor=fs.idFloor WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=$real_estate->idRealEstate"; 
           $floor=ExecuteQuery($query2);
           return $floor[0]->floor;
        }
    }
}

function get_number_of_floors($idRealEstate){
    global $connection;
    $query="SELECT * FROM real_estate rs INNER JOIN floor_status fs ON rs.idFloorStatus=fs.idFloorStatus WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $real_estate=$prepare->fetch();

    if($real_estate){
        if($real_estate->idFloorStatus==1){
            return "ground floor";
        }else{
           $query2="SELECT * FROM real_estate rs INNER JOIN number_of_floors nof ON rs.idRealEstate=nof.idRealEstate WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=$real_estate->idRealEstate"; 
           $floors=ExecuteQuery($query2);
           return $floors[0]->number_of_floors;
        }
    }
}

function get_rooms_real_estate($idRealEstate){
    global $connection;
    $query="SELECT r.rooms FROM real_estate rs INNER JOIN rooms r ON rs.idRooms=r.idRooms WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $rooms=$prepare->fetch();
    if($rooms){
        return $rooms->rooms;
    }else{
        return "NO";
    }
}

function get_documentation_real_estate($idRealEstate){
    global $connection;
    $query="SELECT d.documentation FROM real_estate rs INNER JOIN documentation d ON rs.idDocumentation=d.idDocumentation WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $documentation=$prepare->fetch();
    if($documentation){
        return $documentation->documentation;
    }else{
        return "NO";
    }
}

function get_heating_real_estate($idRealEstate){
    global $connection;
    $query="SELECT h.heating FROM real_estate rs INNER JOIN heating h ON rs.idHeating=h.idHeating WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $heating=$prepare->fetch();
    if($heating){
        return $heating->heating;
    }else{
        return "NO";
    }
}

function get_features_real_estate($idRealEstate){
    global $connection;
    $query="SELECT * FROM feature_real_estate fre INNER JOIN real_estate rs ON fre.idRealEstate=rs.idRealEstate INNER JOIN feature f ON f.idFeature=fre.idFeature WHERE rs.approved=1 AND rs.deleted=0 AND rs.idRealEstate=?";
    $prepare=$connection->prepare($query);
    $prepare->bindValue(1, $idRealEstate);
    $prepare->execute();
    $features=$prepare->fetchAll();
    if(count($features)>=1){
        foreach($features as $f){
            $array[]=$f->feature;
        }
        return $array;
    }else{
        return ["NO"];
    }
}
function get_price_per_m2($price, $size){
    $ppm2=round(preg_replace("/,/", "", $price)/$size);
    return $ppm2;
}
?>