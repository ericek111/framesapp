<?
require_once("init.php");

function cfile_exists($hash) {
	sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `files` WHERE `hash` = '$hash'";
  if($result = $mysqli->query($sql)){
     if($result->num_rows > 0) return true;
     else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
}

function ckey_exists($key) {
	sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `files` WHERE `addr` = '$key'";
  if($result = $mysqli->query($sql)){
     if($result->num_rows > 0) return true;
     else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
}

function addfile($filename, $user, $hash, $key) {
  sql_connect();
  global $mysqli;
  	$sql = "INSERT INTO `files` (`id`, `hash`, `addr`, `origname`, `user`, `created`) VALUES ('', '$hash', '$key', '" . mysql_real_escape_string($filename) . "', '$user', CURRENT_TIMESTAMP);";
  	if(!$result = $mysqli->query($sql)){
    	logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
	    die('There was an error running the query [' . $mysqli->error . ']');
  	} else {
  		if($mysqli->affected_rows > 0) {
	  		return true;
    	}	else return false;
  	}
}

function getfileaddr($hash) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `addr` FROM `files` WHERE `hash` = '$hash'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

function getfileorigname($hash) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `origname` FROM `files` WHERE `hash` = '$hash'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["action"])) {
	if($_POST["action"] == "uploadfile") {
	if(user_exists($_POST["username"])) {
		if(is_users_password($_POST["username"], strtolower($_POST["password"]))) {
			if(isset($_FILES["sendfile"]["name"])) {
				$filename = $_FILES["sendfile"]["name"];
				$filesum = md5_file($_FILES["sendfile"]["tmp_name"]);
				$key = randstr(6);
				while(ckey_exists($key)) {
					$key = randstr(6);
  			}
				$newaddr = "http://" . $_SERVER['HTTP_HOST'] . PATH . "f/" . $key;
				if(cfile_exists($filesum)) {
					echo "http://" . $_SERVER['HTTP_HOST'] . PATH . "f/" . getfileaddr($filesum);
				} else {
					move_uploaded_file($_FILES["sendfile"]["tmp_name"], FILELOC . $key);
					addfile($filename, $_POST["username"], $filesum, $key);
					echo $newaddr;
				}
			} else {
				echo "file is not set!";
			}
		} else {
			echo "invalid password.";
		}
	} else {
		echo "invalid username.";
	}
}
}
?>