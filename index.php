<?php
  require_once "config/connection.php";
  session_start();
  ob_start();
  if((isset($_SESSION["user"]) && $_SESSION["user"]->idRole!=1 && (isset($_GET["page"]) && $_GET["page"]!="editProperty" || !isset($_GET["page"]))) || isset($_GET["admin"])){
    if(!isset($_GET["admin"])){
      header("Location: ".$_SERVER["PHP_SELF"]."?admin=home");
    }
    include "views/pages/admin/adminPanel.php"; 
  }else{
    include "views/fixed/head.php";
    include "views/fixed/header.php";
  
    if(isset($_GET['page'])){
      switch($_GET['page'])
      {
        case 'home': include "views/pages/home.php"; break;
        case 'properties': include "views/pages/properties.php"; break;
        case 'sell': include "views/pages/sell.php"; break;
        case 'form': include "views/pages/form.php"; break;
        case 'contact': include "views/pages/contact.php"; break;
        case 'author': include "views/pages/author.php"; break;
        case 'register': include "views/pages/register.php"; break;
        case 'favorit': include "views/pages/favorit.php"; break;
        case 'property': include "views/pages/property.php"; break;
        case 'userProperty': include "views/pages/userProperty.php"; break;
        case 'editProperty': include "views/pages/editProperty.php"; break;
        case 'editProfile': include "views/pages/editProfile.php"; break;
        case '403': include "views/pages/errors/403.php"; break;
        case '404': include "views/pages/errors/404.php"; break;
      }
    } else {
      include "views/pages/home.php";
    }
    include "views/fixed/footer.php";
  }
  
?>


      