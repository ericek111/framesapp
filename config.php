<?php
require_once("func/functions.php");

do_action("config_beginloading");

// Installation path (relative to htdocs folder)
//define("PATH", DIRECTORY_SEPARATOR . "linux" . DIRECTORY_SEPARATOR . "frames" . DIRECTORY_SEPARATOR);
define("PATH", "/frames/");

// Absolute installation path 
define("ABSPATH", realpath($_SERVER['DOCUMENT_ROOT']) . PATH);

// Absolute path to logfile
define("LOGFILE", ABSPATH . "log.txt");

// Relative (to website root) path to logfile
define("LOGFILEREL", PATH . "log.txt");

// Log and info level
define("LOGLEVEL", "d");

// Location of folder for uploaded files
define("FILELOC", ABSPATH . "files/");

// Selected theme (/content/themes/[value]/[files])
define("THEME", "default");

// Selected theme for Admin Control Panel (/content/themes/[value]/[files])
define("CPTHEME", "default");

// MySQL Settings
define("SQL_SERVER", "127.0.0.1");
define("SQL_USERNAME", "root");
define("SQL_PASSWORD", "");
define("SQL_DATABASE", "lixko");
define("SQL_PREFIX", "");
define("SQL_OPTIONSTABLE", SQL_PREFIX . "options");

// E-mail headers:
$headers = "MIME-Version: 1.0rn"; 
$headers .= "Content-type: text/html; charset=iso-8859-1rn"; 
$headers  .= "From: eb.skola@gmail.com\r\n"; 
define("HEADERS", $headers);

// Turns on/off short message about speed of page load.
define("TIMEMETER", true);

// Log update frequency (automatic reload of logfile on page every X ms):
define("LOGUPDATEFREQUENCY", 3000);

// Content directory:
define("CONTDIR", PATH . "content/");

// Modules directory:
define("MODDIR", CONTDIR . "modules/");

// Themes path:
define("THEMESDIR", CONTDIR . "themes/");

define("EOL", "<br />");
// Array for user callbacks.
$frames_filter = Array();

do_action("config_end");
?>
