<?php
define("CMS_POSTSTABLE", "posts");
function cms_registerpoststab() {
		$taborder = $GLOBALS["taborder"];
		$taborder++;
		$GLOBALS["taborder"] = $taborder;
		echo '<li><a href="#tabs-' . $taborder . '" class="tab">Posts</a></li>';
}
function cms_poststab() {
		$taborder = $GLOBALS["taborder"];
		$taborder++;
		$GLOBALS["taborder"] = $taborder;
?>
	<div id="tabs-<?php echo $taborder
?>">
		There should go posts!
	</div>
	<?php
}
require_once ("categories.php");
require_once ("posts.php");
require_once ("tags.php");
add_action("admincp_endtablist", "cms_registerpoststab");
add_action("admincp_endtabs", "cms_poststab");

if (isset($_GET["action"])) {
		if ($_GET["action"] == "setoption") {
				if (setoption("cms_test1", "value1")) {
						echo "Option set!";
				} else {
						echo "Error whilst trying to set option 'cms_test1' with string value 'value1'!";
				}
		} else if ($_GET["action"] == "getoption") {
				echo getoption("cms_test1");
		} else if ($_GET["action"] == "createpost") {
				if (cms_createpost(get_user_id("ericek111"), "CMS text #1", 2, "cms-test-1", "I am just testing my CMS.", 2)) {
						echo "Post created!";
				} else {
						echo "Error - post creating!";
				}
		} else if ($_GET["action"] == "approvepost") {
				if (cms_approvepost($_GET["id"])) {
						echo "Post approved!";
				} else {
						echo "Error - post approving!";
				}
				die();
		}
}
?>