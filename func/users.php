<?php
// Initialization of basic functions
require_once("init.php");

function validate_username($username) {
  $allowed = array(".", "-", "_");
  if(ctype_alnum(str_replace($allowed, '', $username ))) return $username;
  else return "invalid username";
}

function validate_email($email) { return (filter_var($email, FILTER_VALIDATE_EMAIL)); }

function check_userid_value($id) {
  if(strpos($id,'@') !== false) return "email";
  elseif(is_numeric($id)) return "id";
  else return "username";
}

function user_exists($id) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
  if($result = $mysqli->query($sql)){
     if($result->num_rows > 0) return true;
     else return false;
  } else {
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
} 
  
function get_user($id) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  return $result->fetch_row();
}

function get_users() {
  sql_connect();
  global $mysqli;
  $sql = "SELECT * FROM `users`";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  return $result;
}
  
function getrank($rank) {
  if(is_numeric($rank)) {
    $rank = intval($rank);
    if($rank == 0) { return "Visitor"; }
    elseif($rank == 1) { return "Waiting"; }
    elseif($rank == 2) { return "Registered"; }
    elseif($rank == 3) { return "Moderator"; }
    elseif($rank == 4) { return "Editor"; }
    elseif($rank == 5) { return "Redactor"; }
    elseif($rank == 6) { return "Master"; }
    elseif($rank == 7) { return "Administrator"; }
    elseif($rank == 8) { return "Owner"; }
    else { return "Visitor"; }
  } else {
    if($rank = "Visitor") { return 0; }
    elseif($rank == "Waiting") { return 1; }
    elseif($rank == "Registered") { return 2; }
    elseif($rank == "Moderator") { return 3; }
    elseif($rank == "Editor") { return 4; }
    elseif($rank == "Redactor") { return 5; }
    elseif($rank == "Master") { return 6; }
    elseif($rank == "Administrator") { return 7; }
    elseif($rank == "Owner") { return 8; }
    else { return 0; }
  }
}

function get_userdata($id) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `data` FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

function add_user($username, $email, $password, $data, $rank) {
  if(!isset($username) || !isset($email) || !isset($rank)) return 0;
  else {
    if(!validate_email($email)) return 2;
    else {
      $username = validate_username($username);
      if($username == "invalid username") return 3;
      else {
        sql_connect();
        global $mysqli;
        if(user_exists($email) || user_exists($username)) return 1;
        else {
          $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `data`, `rank`, `created`) VALUES ('', '$username', '" . $email . "', '$password', '" . mysql_real_escape_string($data) . "', '$rank', CURRENT_TIMESTAMP);";
          if($result = $mysqli->query($sql)){
            if($mysqli->affected_rows > 0) {
              logtofile("User " . $username . "/" . $email . "/" . $data . "/" . $rank . " created!", "i");
              if(sendveriflink($email)) return 4;
              else return 6;
            }
            else return 5;
          } else {
            logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
            die('There was an error running the query [' . $mysqli->error . ']');
          }

        }
      }
    }
  }
}

function get_user_rank($id) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `rank` FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

function get_user_id($id) {
  if(check_userid_value($id) == "id") {
    if(user_exists($id)) return $id;
    else return false;
  } else {
    sql_connect();
    global $mysqli;
    $sql = "SELECT `id` FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
    if(!$result = $mysqli->query($sql)){
      logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
      die('There was an error running the query [' . $mysqli->error . ']');
    }
    $row = $result->fetch_row();
    return $row[0];
  }
}

function get_user_email($id) {
  if(check_userid_value($id) == "email") {
    if(user_exists($id)) return $id;
    else return false;
  } else {
    sql_connect();
    global $mysqli;
    $sql = "SELECT `email` FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
    if(!$result = $mysqli->query($sql)){
      logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
      die('There was an error running the query [' . $mysqli->error . ']');
    }
    $row = $result->fetch_row();
    return $row[0];
  }
}

function get_username($id) {
  if(check_userid_value($id) == "username") {
    if(user_exists($id)) return $id;
    else return false;
  } else {
    sql_connect();
    global $mysqli;
    $sql = "SELECT `username` FROM `users` WHERE `" . check_userid_value($id) . "` = '$id'";
    if(!$result = $mysqli->query($sql)){
      logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
      die('There was an error running the query [' . $mysqli->error . ']');
    }
    $row = $result->fetch_row();
    return $row[0];
  }
}

function user_waiting($id) {
  if(is_numeric(get_user_rank($id))) {
    if(get_user_rank($id) == 1) return true;
    else return false;
  } else {
    return false;
  }
}

function is_super_admin($id) {
  if(is_numeric(get_user_rank($id))) {
    if(get_user_rank($id) == 8) return true;
    else return false;
  } else {
    return false;
  }
}

function is_admin($id) {
  if(is_numeric(get_user_rank($id))) {
    if(get_user_rank($id) == 7) return true;
    else return false;
  } else {
    return false;
  }
}

function edit_user($id, $value, $new) {
  sql_connect();
  global $mysqli;
  $sql = "UPDATE `users` SET `$value`=`$new` WHERE `" . check_userid_value($id) . "` = '$id'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  if($mysqli->affected_rows > 0) return true;
  else return false;
}

function getpassword($user) {
  sql_connect();
  global $mysqli;
  $sql = "SELECT `password` FROM `users` WHERE `" . check_userid_value($user) . "` = '$user'";
  if(!$result = $mysqli->query($sql)){
    logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
    die('There was an error running the query [' . $mysqli->error . ']');
  }
  $row = $result->fetch_row();
  return $row[0];
}

function is_users_password($user, $password) {
  if(getpassword($user) == $password) return true;
  else return false;
}
if(isset($_POST["action"])) {
  if($_POST["action"] == "register") {
    //parse_str($_POST['email'], $data);
    $resp = add_user($_POST["username"], mysql_real_escape_string($_POST["email"]), md5($_POST["password"]), "{}", 1);
    echo $resp;
  } elseif($_POST["action"] == "verifyuser") {
    if(user_exists($_POST["username"])) {
    if(is_users_password($_POST["username"], strtolower($_POST["password"]))) echo "user verified";
    else echo "invalid password"; }
    else echo "invalid username";
  } elseif($_POST["action"] == "login") {
    if(user_exists($_POST["username"])) {
      if(is_users_password($_POST["username"], strtolower(md5($_POST["password"])))) {
        if(!isset($_SESSION)) session_start();
        $_SESSION["user_name"] = $_POST["username"];
        $_SESSION["user_mail"] = get_user_email($_POST["username"]);
        $_SESSION["user_password"] = $_POST["password"];
        $_SESSION["user_rank"] = get_user_rank($_POST["username"]);
        echo "user logged";
        ltf("User " . $_SESSION["user_name"] . " logging in!", "i");
      } else { 
        echo "invalid password or username"; 
      } 
    }
    else echo "invalid password or username";
  } elseif($_POST["action"] == "logout") {
    if(isset($_SESSION)) {
      ltf("User " . $_SESSION["user_name"] . " logging out!", "i");
      session_destroy();
      echo "logged out";
    }
  }
}
?>