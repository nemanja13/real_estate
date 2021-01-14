<?php
 require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";

function user_logout($id){
    $id=(int)$id;
    $write="";
    @$file=file(LOGIN_FILE);
    if(count($file)){
        foreach($file as $i){
        $login_id=trim((int)$i);
            if($login_id!=$id){
            $write.=$iId."\n";
            }
        }
    }
    @$open=fopen(LOGIN_FILE,"w");
    @fwrite($open,$write);
    @fclose($open);
}
   
?>