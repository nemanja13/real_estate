<?php
 require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
 error_reporting(0);
function user_get_one($id)
{
    global $connection;
    $query="SELECT * FROM user WHERE idUser=?";
    $prepare=$connection->prepare($query);
    $prepare->execute([$id]);
    $user=$prepare->fetch();
    if($user){
        return $user;
    }else{
        header("Location: 404.php");
    }
}
function page_access_proc(){
    $array=[];
    $total=0;
    $home=0;
    $properties=0;
    $sell=0;
    $form=0;
    $contact=0;
    $author=0;
    $regiter=0;
    $property=0;
    $day_ago=strtotime("1 day ago");
   
    @$file=file(LOG_FILE);
    if(count($file)){

    foreach($file as $i){
        $part=explode("\t",$i);
        $url=explode(".php", $part[0]);
        $page="";
        $page=explode("&", $url[1]);
    
        if(strtotime($part[1])>=$day_ago){
            switch($page[0]){
            case "":$home++;$total++;;break;
            case "?page=home":$home++;$total++;;break;
            case "?page=properties":$properties++;$total++;;break;
            case "?page=sell":$sell++;$total++;;break;
            case "?page=form":$form++;$total++;;break;
            case "?page=contact":$contact++;$total++;;break;
            case "?page=author":$author++;$total++;;break;
            case "?page=register":$regiter++;$total++;;break;
            case "?page=property":$property++;$total++;;break;
            default:$home++;$total++;;break;
        }
        }
    }
    if($total>0){
        $array["Home"]=round($home*100/$total,2);
        $array["Properties"]=round($properties*100/$total,2);
        $array["Sell"]=round($sell*100/$total,2);
        $array["Form"]=round($form*100/$total,2);
        $array["Contact"]=round($contact*100/$total,2);
        $array["Author"]=round($author*100/$total,2);
        $array["Register"]=round($regiter*100/$total,2);
        $array["Property"]=round($property*100/$total,2);
        }
   
    }
    return $array;
}

?>