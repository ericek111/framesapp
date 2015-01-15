<?
require_once("../config.php");
require_once(ABSPATH . "func/mysql.php");
require_once(ABSPATH . "func/files.php");
require_once(ABSPATH . "func/functions.php");
require_once(ABSPATH . "func/logger.php");
require_once(ABSPATH . "func/users.php");

function downfile($key, $file) {
	if(file_exists($file)) {
    header('Content-Description: File Transfer');
  	header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
	  exit;
	}
}

if(isset($_SERVER['QUERY_STRING'])) {
	if(ckey_exists(mysql_real_escape_string($_SERVER['QUERY_STRING']))) {
		sql_connect();
  	global $mysqli;
  	$sql = "SELECT `origname` FROM `files` WHERE `addr` = '" . mysql_real_escape_string($_SERVER['QUERY_STRING']) . "'";
  	if(!$result = $mysqli->query($sql)){
    	logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
	    die('There was an error running the query [' . $mysqli->error . ']');
  	}
  	$row = $result->fetch_row();
  	$file = FILELOC . $_SERVER['QUERY_STRING'];
  	$origname = $row[0];
  	#echo $file;
		if(file_exists($file)) {
    	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename='.$origname);
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
	    exit;
		}
	}	else {
		echo "File doesn't exists!";
	} 
} else {
	echo "File is not set!";
}
?>