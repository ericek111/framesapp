<?php
require_once ("init.php");

//if(trim("SQL_SERVER") == "") {}
function sql_connect() {
	global $mysqli;
	$mysqli = new mysqli(SQL_SERVER, SQL_USERNAME, SQL_PASSWORD, SQL_DATABASE);
	
	/* check connection */
	if ($mysqli->connect_errno > 0) {
		logtofile("Cannot connect to MySQL server! (" . $mysqli->connect_errno . ") " . $mysqli->connect_error, "f");
		die("Cannot connect to MySQL server! (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		exit();
	}
	$mysqli->query("SET NAMES UTF8");
}

function sql_getrow($query) {
	sql_connect();
	global $mysqli;
	if (!$result = $mysqli->query($query)) {
		logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	$row = $result->fetch_row();
	return $row;
}

function sql_getrows($query) {
	sql_connect();
	global $mysqli;
	if (!$result = $mysqli->query($query)) {
		logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	return $result;
}

function sql_runquery($query) {
	sql_connect();
	global $mysqli;
	if (!$result = $mysqli->query($query)) {
		logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	if ($mysqli->affected_rows > 0) return true;
	else return false;
}

function sql_getcount($query) {
	sql_connect();
	global $mysqli;
	if (!$result = $mysqli->query($query)) {
		logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	
	return $mysqli->affected_rows;
}

function sql_iftableexists($table) {
	sql_connect();
	global $mysqli;
	$tablemysqli_escape_string($table);
	if (!$result = $mysqli->query("SHOW TABLES LIKE '" . $table . "'")) {
		logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
		die('There was an error running the query [' . $mysqli->error . ']');
	}
	if ($result->num_rows > 0) return true;
	else return false;
}

function setoption($option, $value) {
	sql_connect();
	global $mysqli;
	$value = json_encode($value);
	return sql_runquery("REPLACE INTO " . SQL_OPTIONSTABLE . " (option, value) VALUES ('" . $mysqli->escape_string($option) . "', '" . $mysqli->escape_string($value) . "'');");
}

function getoption($option) {
	sql_connect();
	global $mysqli;
	$sql = "SELECT 'value' FROM '" . SQL_OPTIONSTABLE . "' WHERE 'option' = '" . $option . "');";
	$data = sql_getrow($sql);
	return json_decode($data);
}
?>