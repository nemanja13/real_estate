<?php

    session_start();
    header("Content-type: application/json");
    $data=["captcha_text"=>$_SESSION["captcha_text"]];
    $code=200;

    echo json_encode($data);
    http_response_code($code);
?>