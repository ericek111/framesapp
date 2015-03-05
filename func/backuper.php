<?php
require_once("init.php");

if (isset($_GET["action"])) {
	if ($_GET["action"] == "backupapp") {
		if (backuper_backapp(true)) {
			echo "Successfully backuped!";
		} else {
			echo "Error occured!";
		}
	} else if ($_GET["action"] == "createbuild") {
		versions_newbuild();
	}
}
?>