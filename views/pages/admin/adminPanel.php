<?php
    if(!isset($_SESSION["user"]) || $_SESSION["user"]->idRole==1){
        header("Location: index.php?page=403");
    }
    require_once "models/users/functions.php";
    require_once "models/login/functions.php";
    $access=page_access_proc();
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="Real Estate, House, Apartment, Buy, Rent, User, Agent"/>
        <meta name="author" content="Nemanja Maksimović"/>
        <meta name="description" content="Site number 1 for real estate in Serbia! Advertisements for renting and selling apartments, houses, shops, offices, business premises in Belgrade, Serbia and abroad."/>
        <link rel="shortcut icon" href="assets/images/logo.ico"/>
    
        <title>Real Estate | Admin panel</title>

        <!-- Styles -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous"/>
        <link href="assets/cssAdmin/lib/font-awesome.min.css" rel="stylesheet">
        <link href="assets/cssAdmin/lib/themify-icons.css" rel="stylesheet">
        <link href="assets/cssAdmin/lib/menubar/sidebar.css" rel="stylesheet">
        <link href="assets/cssAdmin/lib/bootstrap.min.css" rel="stylesheet">
        <link href="assets/cssAdmin/style.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
    </head>

    <body>

        <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
            <div class="nano">
                <div class="nano-content">
                    <div class="logo"><a href="index.php?admin=home"><span>Real Estate</span></a></div>
                    <ul>
                        <?php if($_SESSION["user"]->idRole==2):?>
                        <li class="label">Home</li>
                        <li><a href="index.php?admin=home" id="home"><i class="ti-home"></i> Home</a></li>

                        <li class="label">User</li>
                        <li><a href="#" id="users"><i class="ti-user"></i> Users</a></li>
                        <li><a href="#" id="agents"><i class="ti-user"></i> Agents</a></li>
                        <li><a href="#" id="admins"><i class="ti-user"></i> Admins</a></li>

                        <li class="label">Export Excel</li>
                        <li><a href="models/users/export_excel.php" id="exportUsers"><i class="ti-export"></i> Export Users</a></li>
                        <li><a href="models/agents/export_excel.php" id="exportUsers"><i class="ti-export"></i> Export Agents</a></li>

                        <li class="label">Email</li>
                        <li><a href="#" id="new_emails"><i class="ti-email"></i> New emails</a></li>
                        <li><a href="#" id="readed_emails"><i class="ti-archive"></i> Readed emails</a></li>

                        <li class="label">Real Estate</li>
                        <li><a href="#" id="real_estates"><i class="ti-home"></i> Real estates </a></li>
                        <li><a href="#" id="new_real_estates"><i class="ti-archive"></i> New real estates </a></li>
                        <li><a href="#" id="deleted_real_estates"><i class="ti-trash"></i> Deleted real estates </a></li>
                        <li><a href="#" id="scheduled_visits"><i class="ti-timer"></i> Scheduled visits </a></li>

                        <?php elseif($_SESSION["user"]->idRole==3):?>

                        <li class="label">Visit</li>
                        <li><a href="#" id="visit_outcome" data-id="<?=$_SESSION["user"]->idUser?>"><i class="ti-home"></i> Visit Outcome </a></li>
                        
                        <?php endif;?>
                        <li class="label">Extra</li>
                        <?php if($_SESSION["user"]->idRole==3):?>

                        <li><a href="#" id="agent_profile" data-id="<?=$_SESSION["user"]->idUser?>"><i class="ti-user"></i> Profile</a></li>

                        <?php endif;?>
                        <li><a href="#" class="logout"><i class="ti-close"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /# sidebar -->


        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="float-left">
                            <div class="hamburger sidebar-toggle">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 p-r-0 title-margin-right">
                            <div class="page-header">
                                <div class="page-title">
                                    <h1>Hello, <span>Welcome <?=$_SESSION["user"]->firstName?></span></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /# row -->
                    <section id="main-content">
                        <?php if($_SESSION["user"]->idRole==2):?>
                            <h4 class="text-center">Access to the site over the past 24 hours as a percentage</h4>
                            <div class="tableContainer col-10 mx-auto">
                                <table id="access">
                                    <tr>
                                    <?php foreach($access as $i=>$ac):?>
                                        <th><?=$i?></th>
                                    <?php endforeach;?>
                                    </tr>
                                    <tr>
                                    <?php foreach($access as $i=>$ac):?>
                                        <td><?=$ac?>%</td>
                                    <?php endforeach;?>
                                    </tr>
                                </table>
                            </div>
                            <table id="loginUser" class="mx-auto">
                                <tr><th>Number of login users</th></tr>
                                <tr><td><?=user_login_count()?></td></tr>
                            </table>
                        <?php endif;?>
                        
                    </section>
                    <div class="col-12" id="paginacija"></div>
                </div>
            </div>
        </div>
        <!-- jquery vendor -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.nanoscroller.min.js"></script>
        <!-- nano scroller -->
        <script src="assets/js/sidebar.js"></script>
        <!-- sidebar -->
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/main.js"></script>
        <!-- scripit init-->

    </body>

</html>
