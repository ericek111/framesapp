<?
require_once("init.php");
function pagepath($page, $admin=false) {
	if($admin) {
		return CONTDIR . "themes/" . gettheme() . "/admin/" . $page; 
	} else {
		return CONTDIR . "themes/" . gettheme() . "/" . $page; 
	}
}
function abspagepath($page) { return ABSPATH . "content/themes/" . gettheme() . "/" .  $page; }
function page_exists($page) { return file_exists(abspagepath($page)); }
function getpage($page) {
		include(abspagepath($page));
}
?>