<?php
define('DOCUMENT_ROOT', dirname(realpath(__FILE__)) . '/');
define('TEMPLATE_DIR', DOCUMENT_ROOT."template/");
include_once 'includes/Response.class.php';
include_once 'includes/Avy.class.php';
include_once 'includes/UserInterface.class.php';

$Avy = new Avy("__DASHBOARD__", false);
$page = "";
$Triggers = Triggers::getInstance();
if(isset($_GET['p'])){
	$page = $_GET['p'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport"
              content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/dashboard.css">
        <script src="js/liquid.js" type="text/javascript"></script>
        <script type="text/javascript"
        src="node/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
        <script src="js/jquery-1.12.0.min.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/jquery.touchpunch.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.easypiechart.min.js"></script>
        <script type="text/javascript" src="js/jquery.mmenu.min.all.js"></script>
        <script src="js/serverstats.js"></script>
        <script type="text/javascript">
            $(window).on('load resize', function () {
                var win = $(this); //this = window

                $('body').height(win.height());
                $('body').width(win.width());
            });

            $(function () {
                $('nav#MainMenu').mmenu();
            });
        </script>
    </head>
    <body>
        <div class="header">
            <a href="#MainMenu"></a>
            AVY
            <div class="recording-spinner inactive">
                <div id='bars'>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                    <div class='bar'></div>
                </div>
            </div>
        </div>
        <?php
            /**
             * Display Audio Visual Presentation of AVy
             */
            include_once TEMPLATE_DIR.'avy-voice.template.php';
        ?>
        <div class="site-content">
            <?php
                if($page == '' || !isset($_GET['p']) || $page == 'dashboard')
                {
                    include_once TEMPLATE_DIR.'serverstats.template.php';
                }else{
                    include_once TEMPLATE_DIR.'function.template.php';
                }
            ?>
        </div>
        <nav id="MainMenu">
            <ul>
                <li <?php if($page == 'dashboard' || $page == ''){ echo 'class="Selected"';}?>><a href="?p=dashboard">Dashboard</a></li>
                <li <?php if($page == 'Switches'){ echo 'class="Selected"';}?>><a href="?p=Switches">Switches</a></li>
                <li <?php if($page == 'MultiMedia'){ echo 'class="Selected"';}?>><a href="?p=MultiMedia">Multimedia</a></li>
                <li><span>Remote</span>
                    <ul>
                        <li <?php if($page == 'TotalControl'){ echo 'class="Selected"';}?>><a href="?p=TotalControl">Total Control</a></li>
                        <li <?php if($page == 'TV'){ echo 'class="Selected"';}?>><a href="?p=TV">TV</a></li>
                        <li <?php if($page == 'Cable'){ echo 'class="Selected"';}?>><a href="?p=Cable">Cable</a></li>
                        <li <?php if($page == 'Blueray'){ echo 'class="Selected"';}?>><a href="?p=Blueray">Blueray</a></li>
                        <li <?php if($page == 'AVR'){ echo 'class="Selected"';}?>><a href="?p=AVR">AV Reciever</a></li>
                    </ul>
                </li>
                <li <?php if($page == 'Gmail'){ echo 'class="Selected"';}?>><a href="?p=Gmail">Email</a></li>
                <li <?php if($page == 'gCalendar'){ echo 'class="Selected"';}?>><a href="?p=gCalendar">Schedule</a></li>
                <li <?php if($page == 'Core'){ echo 'class="Selected"';}?>><a href="?p=Core">Core</a></li>
            </ul>
        </nav>
    </body>
</html>
