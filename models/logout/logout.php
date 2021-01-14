<?php
session_start();
require_once "functions.php";
user_logout($_SESSION["user"]->idUser);
session_destroy();
?>