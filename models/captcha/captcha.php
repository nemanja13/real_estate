<?php

session_start();
header("Content-Type: image/jpeg");

$length=8;
$array="QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890";

function captcha_text($l, $a){
    $text="";
    for($i=0; $i<$l; $i++){
        $text.=$a[rand(0,strlen($a)-1)];
    }
    return $text;
}

$captcha_text=captcha_text($length, $array);
$_SESSION["captcha_text"]=$captcha_text;

$img=imagecreatefromjpeg("../../assets/images/captcha.jpg");

$white=imagecolorallocate($img, 255, 255, 255);

imagefilledrectangle($img, 0, 0, 400, 0, $white);

$font=$_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/data/mytype.ttf";


for($i=0; $i<$length; $i++){
    imagettftext($img, 17, rand(-15, 15), $i*25, rand(20,40), $white, $font, $captcha_text[$i]);
}

imagejpeg($img);


?>