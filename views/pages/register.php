<?php
    if(isset($_SESSION["user"]) && !isset($_GET["id"])){
        header("Location: index.php?page=403");
    }
    if(isset($_GET["id"]) && isset($_SESSION["user"])){
        if($_GET["id"]!=$_SESSION["user"]->idUser){
            header("Location: index.php?page=403");
        }
        require_once "models/users/functions.php";
        $id=$_GET["id"];
        $user=user_get_one($id);
    }
?>
<div class="container-fluid" id="formRegister">
    <div class="row">
        <div class="col-10 mx-auto flex2">
            <?=(isset($user))?"<h1>EDIT PROFILE</h1>":"<h1>REGISTER</h1>"?>
            <p class="crta2"></p>
        </div>
        <div class="col-10 mx-auto flex2">
            <form class="flex">
                <div class="col-md-5 col-10 mx-auto blokReg flex3">
                    <label for="firstName">First name</label>
                    <input type="text" id="firstName" name="firstName" value="<?=(isset($user))?$user->firstName:""?>">
                </div>
                <div class="col-md-5 col-10 mx-auto blokReg flex3">
                    <label for="lastName">Last name</label>
                    <input type="text" id="lastName" name="lastName" value="<?=(isset($user))?$user->lastName:""?>">
                    
                </div>
                <div class="col-md-5 col-10 mx-auto blokReg flex3">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?=(isset($user))?$user->email:""?>">
                </div>
                <div class="col-md-5 col-10 mx-auto blokReg flex3">
                    <label for="phone">Phone number</label>
                    <input type="text" id="phone" name="phone" value="<?=(isset($user))?$user->phone:""?>">
                </div>
                <?php if(isset($user)):?>

                    <div class="col-md-5 col-10 mx-auto blokReg flex3">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword">
                    </div>
                    <input type="hidden" name="user" id="user" value="<?=$user->idUser?>">

                <?php else:?>

                    <div class="col-md-5 col-10 mx-auto blokReg flex3">
                        <label for="password">Password</label>
                        <input type="password" id="password">
                    </div>

                <?php endif;?>
                    <div class="col-md-5 col-10 mx-auto blokReg flex3">
                        <label for="passwordConfirm"><?=(isset($user))?"Enter old password to confirm changes":"Password Confirm"?></label>
                        <input type="password" id="passwordConfirm">
                    </div>
                <?php if(!isset($user)):?>
                    <div class="col-md-5 col-10 mx-auto flex1" id="captchaContainer">
                        <img src="models/captcha/captcha.php" class="col-5" id="captcha" alt="captcha">
                        <div class="captchaTextContainer col-6 blokReg flex3">
                            <label for="captchaText">Type the word:</label>
                            <input type="text" id="captchaText">
                        </div>
                    </div>
                <?php endif;?>
                <div class="col-md-5 col-10 mx-auto flex3">
                    <button type="button" class="posalji" id="<?=(isset($user))?"editProfile":"registration"?>">Submit</button>
                </div>
            </form>
        </div>     
        <div id="formRegisterErrors" class="col-8 mx-auto"></div>
    </div>
</div>