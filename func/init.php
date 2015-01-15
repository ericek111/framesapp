<?php
require_once("config.php");
if(!isset($_SESSION)) session_start();
do_action("init_beginloading");
require_once(ABSPATH . "func/logger.php");
require_once(ABSPATH . "func/mysql.php");
require_once(ABSPATH . "func/functions.php");
require_once(ABSPATH . "func/theme_func.php");
require_once(ABSPATH . "func/page_func.php");
require_once(ABSPATH . "func/functions.php");
require_once(ABSPATH . "func/mail.php");
require_once(ABSPATH . "func/users.php");
require_once(ABSPATH . "func/files.php");
require_once(ABSPATH . "func/modules.php");
loadmodules();
do_action("init_endloading");
?>
