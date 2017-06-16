<?php
header('Cache-Control: public');
header('Expires: ' . gmdate('D, d M Y H:i:s', (time() + 86400)) . ' GMT'); // expirar em um dia
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header('Content-Type: text/html; charset=utf-8');

session_start();

$url = explode("/", $_SERVER["REQUEST_URI"]);
$pagina = $url[2];

if (isset($pagina)) {
    if (!file_exists($pagina . ".html")) {
        Header("Location: calender");
    }
    if ($pagina !== "login" && !isset($_SESSION["login_id"])) {
        Header("Location: login");
    }
} else {
    $pagina = "login";
}

function getActive($go, $pagina) {
    if ($go == $pagina) {
        return "class='active'";
    }
    return '';
}

$usuario = null;
$usuarioNome = "";
$icone = "icon-locked";

// Obtém o cookie da pessoa logada
if (isset($_COOKIE['usuario'])) {
    $usuario = json_decode($_COOKIE['usuario']);
    $usuarioNome = $usuario->login;
    $icone = "icon-unlocked";

    if (!isset($_SESSION["login_id"])) {
        $_SESSION["login_id"] = $usuario->id;
        $_SESSION["login_tipo"] = $usuario->tipo;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>               
        <meta name="theme-color" content="#000000">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Agendamento Sala Reunião</title>

        <!-- Bootstrap core CSS -->

        <link href="production/css/bootstrap.min.css" rel="stylesheet">

        <link href="production/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="production/css/animate.min.css" rel="stylesheet">

        <!-- Custom styling plus plugins -->
        <link href="production/css/custom.css" rel="stylesheet">
        <link href="production/css/icheck/flat/green.css" rel="stylesheet">
        <!-- editor -->
        <!--<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">-->
        <link href="production/css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
        <link href="production/css/editor/index.css" rel="stylesheet">
        <link href="production/css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
        <!-- select2 -->
        <link href="production/css/select/select2.min.css" rel="stylesheet">
        <!-- switchery -->
        <link rel="stylesheet" href="production/css/switchery/switchery.min.css" />
        <link rel="stylesheet" href="css/app.css" />
        <link rel="stylesheet" href="css/jquery-ui.min.css" />
        <script src="production/js/jquery.min.js"></script>
        <script src="js/libs/jquery.uploadPreview.min.js"></script>
        <script type="text/javascript" src="production/js/maps/gdp-data.js"></script>
        <script src="production/js/nprogress.js"></script>
        <script>
            $(document).ready(function () {
                $("body").on("click", "#menu_toggle", function () {                
                    $("#pro-fileb").toggle();                    
                });
            });
        </script>
    </head>
    <body class="nav-md">        
        <div class="container body">
            <?php if ($pagina != 'login') { ?>
                <div class="main_container">

                    <div class="col-md-3 left_col hidden-print">
                        <div class="left_col scroll-view">

                            <div class="profile-menu" id="pro-fileb" style="border: 0;">
                                <!--<a href="index.html" class="site_title"></a>-->
                                <div class="profile_pic">
                                    <img src="img/imagem/sem_imagem.jpg" alt="..." class="img-circle profile_img">
                                </div>
                                <div class="profile_info">
                                    <span style="color:white;">Bem-vindo,</span>
                                    <h2 style="color:white;"><?php echo $usuario->login ?></h2>
                                </div>
                            </div>
                            <div class="clearfix"></div>                         
                            <br />
                            <!-- sidebar menu -->
                            <div id="sidebar-menu" class="hidden-print main_menu">

                                <div class="menu_section">                                    
                                    <ul class="nav side-menu">                                        
                                        <li><a><i class="fa fa-cog"></i> Gerenciar <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu" style="display: none">
                                                <li><a href="salas">Salas</a>
                                                </li>
                                                <li><a href="calender">Agenda</a>
                                                </li>
                                                <li><a href="usuarios">Usuários</a>
                                                </li>
                                                <li><a href="agendamentos">Meus Agendamentos</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>                                
                            </div>                         
                        </div>
                    </div>

                    <!-- top navigation -->
                    <div class="top_nav hidden-print">

                        <div class="nav_menu">
                            <nav class="" role="navigation">
                                <div class="nav toggle">
                                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                                </div>

                                <ul class="nav navbar-nav navbar-right">
                                    <li class="">
                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <img src="img/imagem/sem_imagem.jpg" alt=""><?php echo $usuario->login ?>
                                            <span class=" fa fa-angle-down"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                            <li>
                                                <a class="logout" id='linkSair' href="#">
                                                    <i class="fa fa-sign-out pull-right"></i>Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                    <!-- /top navigation -->

                    <!-- page content -->
                    <div class="right_col" role="main">
                        <div class="">
                        <?php } ?>

                        <?php require($pagina . ".html") ?>

                    </div>
                    <!-- /page content -->                                    

                </div>

            </div>
        </div>

        <div id="custom_notifications" class="custom-notifications dsp_none">
            <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
            </ul>
            <div class="clearfix"></div>
            <div id="notif-group" class="tabbed_notifications"></div>
        </div>

        <script src="production/js/bootstrap.min.js"></script>

        <!-- chart js -->
        <script src="production/js/chartjs/chart.min.js"></script>
        <!-- bootstrap progress js -->
        <script src="production/js/progressbar/bootstrap-progressbar.min.js"></script>
        <script src="production/js/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- icheck -->
        <script src="production/js/icheck/icheck.min.js"></script>
        <!-- tags -->
        <script src="production/js/tags/jquery.tagsinput.min.js"></script>
        <!-- switchery -->
        <script src="production/js/switchery/switchery.min.js"></script>        
        <script type="text/javascript" src="production/js/moment.min2.js"></script>  
        <script type="text/javascript" src="production/js/datepicker/daterangepicker.js"></script>
        <!-- input mask -->
        <script type="text/javascript" src="js/libs/meiomask.min.js"></script>
        <!-- /input mask -->
        <script src="production/js/editor/bootstrap-wysiwyg.js"></script>
        <script src="production/js/editor/external/jquery.hotkeys.js"></script>
        <script src="production/js/editor/external/google-code-prettify/prettify.js"></script>

        <!-- select2 -->
        <script src="production/js/select/select2.full.js"></script>
        <!-- form validation -->
        <script type="text/javascript" src="production/js/parsley/parsley.min.js"></script>
        <!-- textarea resize -->
        <script src="production/js/textarea/autosize.min.js"></script>
        <script>
            autosize($('.resizable_textarea'));
        </script>
        <!-- Autocomplete -->
        <script type="text/javascript" src="production/js/autocomplete/countries.js"></script>
        <script src="production/js/autocomplete/jquery.autocomplete.js"></script>

        <script src="production/js/custom.js"></script>
        <script type="text/javascript" src="js/libs/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/libs/jquery.cookie.min.js"></script>
        <script type="text/javascript" src="js/libs/knockout.min.js"></script>             
        <script type="text/javascript" src="js/app.js"></script>
        <!-- dropzone -->
        <script src="production/js/dropzone/dropzone.js"></script>



        <script type="text/javascript" src="js/<?= $pagina ?>.js"></script> 
        <script type="text/javascript">
            $(document).ready(function () {
                $.uploadPreview({
                    input_field: "#image-upload",
                    preview_box: "#image-preview",
                    label_field: "#image-label"
                });                
            });
        </script>
        <!-- input_mask -->
        <script type="text/javascript">
            $(document).ready(function () {
                $(':input').setMask();
                $('input[data-mask]').each(function () {
                    $(this).setMask($(this).data('mask'));
                });
                $('input[name=placa]').setMask('aaa-9999');
                $("#placa").setMask('aaa-9999');
            });
        </script>.
        <!-- /input mask -->
        <script>
            NProgress.done();
        </script>  
    </body>
</html>