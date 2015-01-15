<?
function newtab() {
	$taborder = $GLOBALS["taborder"];
	$taborder++;
	$GLOBALS["taborder"] = $taborder;
	?>
	<div id="tabs-<?=$taborder?>">
		This is page from module!
	</div>
	<?
}
function registertab() {
	$taborder = $GLOBALS["taborder"];
	$taborder++;
	$GLOBALS["taborder"] = $taborder;
	?>
	<li><a href="#tabs-<?=$taborder?>" class="tab">Custom module page</a></li>
	<?
}
add_action("admincp_endtablist", "registertab");
add_action("admincp_endtabs", "newtab");
?>