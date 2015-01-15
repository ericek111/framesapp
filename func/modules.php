<?
require_once("init.php");

function getmodules() {
	$path = realpath($_SERVER['DOCUMENT_ROOT']) . MODDIR;
	$dirs = scandir($path);
	$modlist = [];
	$i = 0;
	foreach ($dirs as $result) {
    if($result === '.' or $result === '..') continue;
    if(is_dir($path . '/' . $result)) {
    	if(file_exists($path . $result . "/info.php")) {
    		include($path . $result . "/info.php");
    		$current = array($i => array(
    			"name" => $name,
    			"displayname" => $displayname,
    			"description" => $description,
    			"url" => $url,
    			"author" => $author
    		));
    		$modlist = $modlist + $current;
    		$i++;
    	}
	  }
	  
	}
	return $modlist;
}

function showmodules() {
	$modlist = getmodules();
	$i = 0;
	if(count($modlist) > 0) {
	foreach($modlist as $mod) {
		$displayname = $mod["displayname"];
		$name = $mod["name"];
		$description = $mod["description"];
		$url = $mod["url"];
		$author = $mod["author"];
		echo("<b>Module:</b> " . $displayname . " <i>(" . $name . ")</i><br />");
  	echo("&nbsp;&nbsp;&nbsp;&nbsp;<b>Description:</b> " . $description . "<br />");
  	echo("&nbsp;&nbsp;&nbsp;&nbsp;<b>URL:</b> <a href='" . $url . "'>" . $url . "</a><br />");
  	echo("&nbsp;&nbsp;&nbsp;&nbsp;<b>Author:</b> " . $author . "<br />");
  	$i++;
	}
	}
}
function loadmodules() {
	$modlist = getmodules();
	$i = 0;
	if(count($modlist) > 0) {
	foreach($modlist as $mod) {
		require_once(realpath($_SERVER['DOCUMENT_ROOT']) . MODDIR . $mod["name"] . "/main.php");
  	$i++;
	}
	}
}
?>