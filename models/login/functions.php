<?php
 require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
 
 function user_login($id){
    @$open=fopen(LOGIN_FILE, "a");
    $write=$id."\n";
    @fwrite($open,$write);
    @fclose($open);
}
function user_login_count(){
    return count(file(LOGIN_FILE));
}

?>