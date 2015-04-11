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
		versions_newbuild(true);
	} else if ($_GET["action"] == "createpatch") {
		echo("<hr /> === CREATING NEW BUILD === <br />");
		versions_newbuild(true);
		if(versions_isxdiffloaded()) {
			echo("<hr /> === CREATING PATCH === <br />");
			versions_createpatch(versions_getlatestbuildnum() - 1, versions_getlatestbuildnum(), true);
		} else {
			echo("<b>XDIFF</b> is not loaded!<br />");
		}
	} else if ($_GET["action"] == "fullrelease") {
		versions_fullrelease("", true);
	}
}
?>