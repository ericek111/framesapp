<?php
require_once("func/init.php");
$i = 0;
if(count($_GET) > 0) {
	foreach($_GET as $key => $value) {
		if (strpos($key,'admin') !== false) {
			do_action("admin_indexbeginloading");
			getpage("admin/index.php");
			do_action("admin_indexendloading");
		} else {
			if($i == 0) {
				do_action("page_indexbeginloading");
				getpage("index.php");
				do_action("page_indexendloading");
				$i++;
			}
		}
	}
} else {
	do_action("page_indexbeginloading");
	getpage("index.php");
	do_action("page_indexendloading");
}
/*if(isset($_GET["admin"]) || isset($_GET["admin/"])) {
} else {
}*/
?>
