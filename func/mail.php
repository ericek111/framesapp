<?
require_once("init.php");
function sendveriflink($email) {
  $headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$hash = md5(microtime() . "" . rand(0, 9999));
  sql_connect();
  global $mysqli;
  $sql = "INSERT INTO `veriflinks` (`id`, `hash`, `email`, `created`) VALUES ('', '$hash', '$email', CURRENT_TIMESTAMP);";
  $link = "http://" . $_SERVER['HTTP_HOST'] . PATH . "func/users.php?a=v&id=" . $hash;
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  } else {
  	if($mysqli->affected_rows > 0) {
      if(mail($email, 'Verification e-mail for Frames app', "Click on this link to verify your account, please: <a href='$link'>$link</a><br />This link will be deactivated and your account will be deleted after 7 days.<br /><br />Sent in " . date('Y-m-d G:i:s', time()), $headers)) {
        return true;
      }
    }
  	else return false;
  }
}

function veriflinksent($email) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `veriflinks` WHERE `email` = '$email'";
  if($result = $mysqli->query($sql)){
     if($result->num_rows > 0) return true;
     else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
}

function veriflinkexists($link) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `veriflinks` WHERE `hash` = '$link'";
  if($result = $mysqli->query($sql)){
     if($result->num_rows > 0) return true;
     else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
}
function getveriflink($email) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `hash` FROM `veriflinks` WHERE `email` = '$email'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}
function getverifemail($link) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `email` FROM `veriflinks` WHERE `hash` = '$link'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

function veriflinkvalid($link) {

}

function verifuser($link) {
  sql_connect();
  global $mysqli;
  $mail = getverifemail($link);
  logtofile("E-mail retrieved! [" . $mail . "]", "d");
  $sql = "UPDATE `users` SET `rank`=2 WHERE `email`='" . $mail . "' AND rank=1;";
  $username = get_username($mail);
  logtofile("Username retrieved! [" . $username . "]", "d");
  if($result = $mysqli->query($sql)) {
    logtofile("Update query executed! $sql [" . $mysqli->affected_rows . "]", "d");
    if($mysqli->affected_rows > 0) {
    $sql = "DELETE FROM `veriflinks` WHERE `hash`='$link'";
      if($result = $mysqli->query($sql)){
        if($mysqli->affected_rows > 0) return true;
        else return false;
        logtofile("Delete query executed! $sql [" . $mysqli->affected_rows . "]", "d");
        logtofile("Account $username activated!", "d");
      } else {
      logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
      die('There was an error running the query [' . $mysqli->error . ']');
      }
    }
    else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }

}

if(isset($_GET["a"])) {
  if($_GET["a"] == "sendtestmail5893") {
    if(mail('eb.skola@gmail.com', 'Test e-mail from Frames', "Test e-mail. Sent in " . date('Y-m-d G:i:s', time()))) {
      echo "sent";
      logtofile("Test e-mail sent!", "i");
    }
  }
  if($_GET["a"] == "v") {
    if(isset($_GET["id"])) {
      if(veriflinkexists($_GET["id"])) {
        if(verifuser($_GET["id"])) {
          echo "Account successfully activated!";
        } else {
          echo "Account wasn't activated!";
        }
      } else {
        echo "Invalid verification ID!";
      }
    } else {
      echo "Verification ID is not set!";
    }
  }
}
?>