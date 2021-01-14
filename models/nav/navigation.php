<?php
    require_once "config/connection.php";
    require_once "models/real_estate/functions.php";

    function top_nav(){
        global $connection;
        $query="SELECT * FROM navigation ORDER BY position";
        $nav=ExecuteQuery($query);
        $query2="SELECT * FROM navigation ORDER BY position LIMIT 3 OFFSET 6";
        $nav_log=ExecuteQuery($query2);
        $html="<nav><ul class='flex1' id='nav'>";
        for($i=0; $i<count($nav)-3; $i++){
            $html.="<li><a href='{$nav[$i]->link}'>{$nav[$i]->text}</a></li>";
        }
        $html.="</ul></nav><nav id='navLog' class='flex1'><ul>";
        foreach($nav_log as $r){
            if($r->text=="Login" && isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="Sing up" && isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="profile" && !isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="Login"){
                $html.="<li><a href='#' class='login'>".$r->text."</a></li><li class='liCrta'>|</li>";
            }elseif($r->text=="profile"){
                $idUser=$_SESSION['user']->idUser;
                $nore=is_array(get_real_estate_user($idUser))?count(get_real_estate_user($idUser)):0;
                $html.="<li class='profile'><a href='#' class='profileLink'><i class='fas fa-user'></i></a>
                <div class='profilePopup'>
                    <div class='profilePoint'></div>
                    <div class='profileData flex2'>
                        <a href='index.php?page=register&id=$idUser'>Edit profile <i class='fas fa-edit'></i></a>
                        <a href='index.php?page=userProperty&id=$idUser'>My properties <span class='numberRealEstates'>$nore</span></a>
                        <a href='#' class='logout'>Logout <i class='fas fa-sign-out-alt'></i></a>
                    </div>
                </div>";
            }
            else{
                $html.="<li><a href='$r->link'>$r->text</a></li>";
            }
        }
        $html.="</ul></nav>";
        return $html;
    }
    function bottom_nav(){
        $query="SELECT * FROM navigation ORDER BY position";
        $nav=ExecuteQuery($query);
        $html="<nav><ul>";
        foreach($nav as $r){
            if($r->text=="Login" && isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="Sing up" && isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="profile" && !isset($_SESSION["user"])){
                continue;
            }
            if($r->text=="Login"){
                $html.="<li><a href='#' class='login'>".$r->text."</a></li>";
            }elseif($r->text=="profile"){
                $idUser=$_SESSION['user']->idUser;
                $nore=is_array(get_real_estate_user($idUser))?count(get_real_estate_user($idUser)):0;
                $html.="<li class='profile'><a href='#'><i class='fas fa-user'></i></a><div class='profilePopup'>
                <div class='profilePoint'></div>
                    <div class='profileData flex2'>
                    <a href='index.php?page=register&id=$idUser'>Edit profile <i class='fas fa-edit'></i></a>
                    <a href='index.php?page=userProperty&id=$idUser'>My properties <span class='numberRealEstates'>$nore</span></a>
                    <a href='#' class='logout'>Logout <i class='fas fa-sign-out-alt'></i></a>
                    </div>
                </div></li>";
            }
            else{
                $html.="<li><a href='$r->link'>$r->text</a></li>";
            }
        }
        $html.="</ul></nav>";
        return $html;
    }
    function footer_nav(){
        global $connection;
        $query="SELECT * FROM navigation ORDER BY position LIMIT 6";
        $nav=ExecuteQuery($query);
        $html="<ul class='flex3' id='navFooter'>";
        foreach($nav as $r){
            $html.="<li><a href='$r->link'>$r->text</a></li>";
        }
        $html.="</ul></nav>";
        return $html;
    }









?>

