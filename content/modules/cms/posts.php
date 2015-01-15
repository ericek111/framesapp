<?
function cms_getpostbyid($id) {
	$row = sql_getrow("SELECT * FROM " . CMS_POSTSTABLE . " WHERE id = " . $id . ";");
	return $row;
}
function cms_getpostbypermalink($permalink) {
	$row = sql_getrow("SELECT * FROM " . CMS_POSTSTABLE . " WHERE permalink = '" . $permalink . "';");
	return $row;
}
function cms_getpostbytitle($title) {
	$row = sql_getrow("SELECT * FROM " . CMS_POSTSTABLE . " WHERE id = '" . $title . "';");
	return $row;
}
function cms_getpostid($permalink) {
	$row = sql_getrow("SELECT id FROM " . CMS_POSTSTABLE . " WHERE permalink = '" . $permalink . "';");
	return $row[0];
}
function cms_getpostpermalink($id) {
	$row = sql_getrow("SELECT permalink FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getposttitle($id) {
	$row = sql_getrow("SELECT title FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getpostauthor($id) {
	$row = sql_getrow("SELECT author FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getpoststatus($id) {
	$row = sql_getrow("SELECT status FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getpostcontent($id) {
	$row = sql_getrow("SELECT content FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getpostcreateddate($id) {
	$row = sql_getrow("SELECT created FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_getpostplanneddate($id) {
	$row = sql_getrow("SELECT planned FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return $row[0];
}
function cms_postplanned($id) {
	$row = sql_getrow("SELECT planned FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
	return (trim($row[0]) != "");
}
function cms_postexists($id) {
	$count = sql_getcount("SELECT id FROM " . CMS_POSTSTABLE . " WHERE id = " . $id . ";");
	return ($count > 0);
}
function cms_permalinkexists($permalink) {
	$count = sql_getcount("SELECT id FROM " . CMS_POSTSTABLE . " WHERE permalink = '" . $permalink . "';");
	return ($count > 0);
}
function cms_createpost($author, $title, $category, $permalink, $content, $status, $planned = "") {
	if(sql_runquery("INSERT INTO `posts` (`id`, `author`, `category`, `title`, `permalink`, `content`, `status`, `created`) VALUES ('', '" . $author . "', '" . $category . "', '" . $title . "', '" . $permalink . "', '" . $content . "', '" . $status . "', CURRENT_TIMESTAMP);")) {
		logtofile("New post (" . $title . ") by " . get_username($author) . " created at " . date("Y-m-d H:m:s") . "! " . $status, "i");
		return true;
	}
}
function cms_moveposttotrash($id) {
	return sql_runquery("UPDATE " . CMS_POSTSTABLE . " SET status=6 WHERE id = '" . $id . "';");
}
function cms_deletepost($id) {
	return sql_runquery("DELETE FROM " . CMS_POSTSTABLE . " WHERE id = '" . $id . "';");
}
function cms_approvepost($id) {
	if(cms_postplanned($id)) {
		return sql_runquery("UPDATE " . CMS_POSTSTABLE . " SET status=4 WHERE id = '" . $id . "';");
	} else {
		return sql_runquery("UPDATE " . CMS_POSTSTABLE . " SET status=3 WHERE id = '" . $id . "';");
	}
}
?>