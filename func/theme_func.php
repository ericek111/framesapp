<?
function theme_exists($theme) { return is_dir(ABSPATH . "content/themes/" . THEME); }
function gettheme() { return THEME; } 
function getthemepath($theme) {
	if(isset($theme)) return PATH . "content/themes/" . $theme;
	else return PATH . "content/themes/" . gettheme();
}
function getabsthemepath($theme) {
	if(isset($theme)) return ABSPATH . "content/themes/" . $theme;
	else return ABSPATH . "content/themes/" . gettheme();
}
?>