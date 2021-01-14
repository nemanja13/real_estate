<?php 
if(isset($_GET["page"])){
    require_once "models/head/functions.php";
    $meta=meta_data_get($_GET["page"]);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, height=device-height">
    <meta name="robots" content="index, follow"/>
    <meta name="keywords" content="<?=isset($meta['keywords'])?$meta['keywords']:'Real Estate, House, Apartment, Buy, Rent, Agents'?>"/>
    <meta name="author" content="Nemanja Maksimović"/>
    <meta name="description" content="<?=isset($meta['description'])?$meta['description']:'Site number 1 for real estates in Serbia! Advertisements for renting and selling apartments, houses, shops, offices, business premises in Belgrade, Serbia and abroad.'?>"/>
    <title><?=isset($meta['title'])?$meta['title']:'Real Estate | Homes for Sale, Purchase and Rental'?> </title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/logo.ico"/>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/slick.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>