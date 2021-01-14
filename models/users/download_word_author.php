<?php

$word = new COM("word.Application") or die('Connection to word failed');
$word->Visible = 1;

$word->Documents->Add();

$text1="Author: Nemanja Maksimovic";
$text2="My name is Nemanja Maksimovic, I am a student of the High School of ICT, majoring in Internet technology.";
$text3="I know how to program in HTML, CSS, JavaScript, jQuery, PHP";

$word->Selection->TypeText($text1."\n\n\n".$text2."\n\n".$text3);


$filename = tempnam(sys_get_temp_dir(), "word");
$word->Documents[1]->SaveAs($filename);

$word->Quit();
$word = null;

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=autor.doc");

readfile($filename);
unlink($filename);
header("Location: index.php?page=author");

?>