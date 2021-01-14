<div class="container-fluid">
        <div class="row">
            <div class="col-12 d-none d-sm-flex" id="topHeader">
                <div class="col-10 flex1 mx-auto">
                    <div class="col-4 text-left"><i class="fas fa-phone"></i> +381 323 234</div>
                    <div class="col-4"><i class="fas fa-paper-plane"></i> realestate&#64;gmail&#46;com</div>
                    <div class="col-4 text-right">
                        <ul>
                            <li><a href="https://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-square"></i></a></li>
                            <li><a href="https://twitter.com/explore" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="https://www.youtube.com/" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <header class="row">
            <div class="col-lg-10 col-11 mx-auto flex">
                <div id="logo" class="col-2">
                    <a href="index.php?page=home"><img src="assets/images/logo.png" alt="logo"/></a>
                </div>
                <div id="header" class="col-lg-10 col-md-8 flex1">
                    
                    <?php 
                    require "models/nav/navigation.php";
                    echo top_nav();
                    ?>
                </div>
                <div class="col-2 d-lg-none" id="barsContainer">
                    <div id="bars">
                        <div class="bar1"></div>
                        <div class="bar2"></div>
                        <div class="bar3"></div>
                    </div>
                </div>
            </div>
            <div id="header2">
                <?=bottom_nav();?>
            </div>
        </header>
    </div>