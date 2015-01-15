<? include("header.php"); ?>

<? if(!isset($_SESSION["user_name"])) {?>
<style>
html {
  margin:20px;
}
</style>
<script type="text/javascript">  
      function loginuser() {
        $("#tabs").tabs("option", "active", 1);
         $.ajax({
            type  : "POST",
            url : "<?=PATH."func/users.php"?>",
            data  : $("#loginform").serialize(),
            datatype : 'html',
            success: function(data) {     
              if(data == "invalid password or username") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("Username or password incorrect!");
              } else if(data == "user logged") {
                location.reload();
              } else {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("Error: " + data);
              }
            }
          });
      }
            function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
      }
      function registeruser() {
        if(!isEmail($("#registeremail").val())) {
              $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
              alert("E-mail isn't valid!");
        } else {
          $.ajax({
            type  : "POST",
            url : "<?=PATH."func/users.php"?>",
            data  : $("#registerform").serialize(),
            datatype : 'html',
            success: function(data) {     
              if(data == "0") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Username, e-mail or password missing!");
              } else if(data == "1") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Username or e-mail already exists!"); 
              } else if(data == "2") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Invalid e-mail!"); 
              } else if(data == "3") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Invalid username!"); 
              } else if(data == "4") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: false, timeout: 3000});
                alert("User created!"); 
              } else if(data == "5") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("MySQL query unsuccessful!"); 
              } else {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert(data)
              }
            }
          });
      }
      }        
</script>
<h3>You don't have permission to view this page!</h3><br />
      <b>Login:</b><br />
      <div id="loginformdiv">
        <form id="loginform" action='<?=PATH."func/users.php"?>' method="post">
          <input type="hidden" name="action" value="login" />
          Username: <input type="text" length="128" name="username" id="loginusername" /><br />
          Password: <input type="password" name="password" id="loginpass" /><br />
          <input type="hidden" value="{}" name="data" />
          <input type="button" onclick="loginuser()" value="Login!" name="loginbutt" />
        </form>
      </div>
      <br />
      You don't have an account? <b>Register!</b>
      <div id="registerformdiv">
        <form id="registerform" action="<?=PATH."func/users.php"?>" method="post">
          <input type="hidden" name="action" value="register" />
          Username: <input type="text" length="128" name="username" id="registerusername" /><br />
          E-mail: <input type="text" name="email" id="registeremail" /><br />
          Password: <input type="password" name="password" id="registerpass" /><br />
          <input type="hidden" value="{}" name="data" />
          <input type="button" onclick="registeruser()" value="Register!" name="registerbutt" />
        </form>
      </div>
<? } else { ?>
    <script>
      $(document).ready(function() {
            $(function($) {
              $('#timeinmenu').jclock();
            });
        var tabCookieName = "lixko2_lasttab";
        var cont = window.location.hash.substr(1);
        var acttab = 0;
        if(cont.indexOf("tab") > -1) {
          cont = cont.substr(3);
          if(isNaN(cont)) {
            acttab = $.cookie(tabCookieName);
          } else {
            acttab = cont;
          }
        }
        $("#tabs").tabs({
        active : (acttab || 0),
        activate : function( event, ui ) {
          var newIndex = ui.newTab.parent().children().index(ui.newTab);
          if(newIndex == "1") loadlog(true);
          $.cookie(tabCookieName, newIndex);
          window.location.hash = "tab" + newIndex;
          }
        });

        $("#autoupdate").change(function() {startLoop(); loadlog(false); });
        if($("#tabs").tabs('option', 'active') == "1") loadlog(true);
      function updatelog() {
        setTimeout( updatelog(), 1500 );
      }
      $("#container").fadeIn(500);

      });
      $(function(){
        var moveBlanks = function(a, b) {
          if ( a < b ){
            if (a == "") return 1;
            else return -1;
          }
          if ( a > b ){
            if (b == "") return -1;
            else return 1;
          }
          return 0;
        };
        var moveBlanksDesc = function(a, b) {
          if ( a < b ) return 1;
          if ( a > b ) return -1;
          return 0;
        };
        var table = $("table").stupidtable({
        "moveBlanks": moveBlanks,
        "moveBlanksDesc": moveBlanksDesc,
      });

      table.on("beforetablesort", function (event, data) {
        // data.column - the index of the column sorted after a click
        // data.direction - the sorting direction (either asc or desc)
        $("#msg").text("Sorting index " + data.column);
        $.cookie("lixko2_stupidtablesorting", data.column, { expires: 14 });
      });

      table.on("aftertablesort", function (event, data) {
        var th = $(this).find("th");
        th.find(".arrowr").remove();
        th.find(".arrowl").remove();
        $("#stupidtable th").find("span").removeClass("activesortspan");
        var dir = $.fn.stupidtable.dir;

        var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
        th.eq(data.column).append('<div class="arrowr">' + arrow +'</div>');
        th.eq(data.column).prepend('<div class="arrowl">' + arrow +'</div>');
        th.eq(data.column).find("span").addClass("activesortspan");
      });

      $("#stupidtable th").click(function() {

        $("#stupidtable th").removeClass("activesort");
        $(this).addClass("activesort");
      });
      /*$("table.stupidtable tr").slice(1).click(function(){
        $(".awesome").removeClass("awesome");
        $(this).addClass("awesome");
      });*/

    });
      function loginuser() {
        $("#tabs").tabs("option", "active", 1);
         $.ajax({
            type  : "POST",
            url : "<?=PATH."func/users.php"?>",
            data  : $("#loginform").serialize(),
            datatype : 'html',
            success: function(data) {     
              if(data == "invalid password or username") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("Username or password incorrect!");
              } else if(data == "user logged") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: false, timeout: 3000});
                alert("Logged in! Welcome, " + $("#loginusername").val()); 
                location.reload();
              } else {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("Error: " + data);
              }
            }
          });
      }
      function logoutuser() {
         $.ajax({
            type  : "POST",
            url : "<?=PATH."func/users.php"?>",
            data  : { action: "logout" },
            datatype : 'html',
            success: function(data) {     
              if(data == "logged out") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: false, timeout: 3000});
                alert("Logged out! Bye! :("); 
                //$("#userfield").html($("#loginusername").val() + " | " + "<a href='#' onclick='logoutuser()'>Logout</a>");
                location.reload();
              } else {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("Error: " + data);
              }
            }
          });
      }
      function sendtestmail() {
        $.get( "<?=PATH?>func/mail.php?a=" + $("#sendmailcode").val(), function(data) {
          if(data == "sent") {
            $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: true});
            alert("Test e-mail sent!");
            $('#logcontent').html(data);
            loadlog(false);
          } else {
            $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
            alert("Error whilst trying to send e-mail [1]! " + data);
          }
        })
        .fail(function(data) {
          $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
          alert("Error whilst trying to send e-mail [2]!");
        });
      }
      function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
      }
      function registeruser() {
        if(!isEmail($("#registeremail").val())) {
              $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
              alert("E-mail isn't valid!");
        } else {
          $.ajax({
            type  : "POST",
            url : "<?=PATH."func/users.php"?>",
            data  : $("#registerform").serialize(),
            datatype : 'html',
            success: function(data) {     
              if(data == "0") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Username, e-mail or password missing!");
              } else if(data == "1") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Username or e-mail already exists!"); 
              } else if(data == "2") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Invalid e-mail!"); 
              } else if(data == "3") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: false, timeout: 3000});
                alert("Invalid username!"); 
              } else if(data == "4") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: false, timeout: 3000});
                alert("User created!"); 
              } else if(data == "5") {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert("MySQL query unsuccessful!"); 
              } else {
                $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: false, timeout: 3000});
                alert(data)
              }
            }
          });
      }
      }        
      function loadlog(showalert) {
        $.get( "<?=PATH?>func/logger.php?action=showlog", function(data) {
          if(data == "error") {
            $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true});
            alert("Error whilst trying to load logfile! [1]");
          } else {
            if(showalert) {
              $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: true, timeout: 2000});
              alert("Logfile loaded!");
            }
            $('#logcontent').html(data).fadeIn(500);
          }
        })
        .fail(function(data) {
          $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true});
          alert("Error whilst trying to load logfile! [2]");
        });
      }

      function clearlog() {
        $.get( "<?=PATH?>func/logger.php?action=clearlog", function(data) {
          if(data == "cleared") {
            $.noty.consumeAlert({layout: 'topCenter', type: 'success', dismissQueue: true});
            alert("Logfile cleared!");
            $('#logcontent').html(data);
            loadlog(false);
          } else {
            $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
            alert("Error whilst trying to clear logfile! [1]" + data);
          }
        })
        .fail(function(data) {
          $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true, timeout: 3000});
          alert("Error whilst trying to clear logfile! [2]");
        });
      }

      function wakeup() {
        var invocations = 0;
        if (++invocations >= 200) {
          $.noty.consumeAlert({layout: 'topCenter', type: 'warning', dismissQueue: true, timeout: 3000});
          alert("Stopped loading!");
          clearInterval(handle);
        } else {
          loadlog(false);
        }
      }

      function notif() {
        $.noty.consumeAlert({layout: 'topRight', type: 'success', dismissQueue: true});
        alert("This is the same but noty consumed with options");
       }

      function testtofile(level) {
        $.get( "<?=PATH?>func/logger.php?action=testwrite" + level, function(data) {
          $.noty.consumeAlert({layout: 'top', type: 'success', dismissQueue: true, timeout: 1000});
          alert("Test entry written!");
          loadlog(false);  
        });
       }

      function leveltoint(lvlstr) {
        var lvlint;
          if(lvlstr.toLowerCase() == "d" || lvlstr.toLowerCase() == "debug") {
            lvlint=0;
          } else if(lvlstr.toLowerCase() == "i" || lvlstr.toLowerCase() == "info") {
            lvlint = 1;
          } else if(lvlstr.toLowerCase() == "w" || lvlstr.toLowerCase() == "warn") {
            lvlint = 2;
          } else if(lvlstr.toLowerCase() == "e" || lvlstr.toLowerCase() == "error") {
            lvlint = 3;
          } else if(lvlstr.toLowerCase() == "f" || lvlstr.toLowerCase() == "fatal") {
            lvlint = 4;
          } else {
            lvlint = 5;
          }
          return lvlint;
       }

      function inttolevel(lvlint) {
        var lvlstr;
        if(lvlint == 0) {
          lvlstr = "debug";
        } else if(lvlint == 1) {
          lvlstr = "info";
        } else if(lvlint == 2) {
          lvlstr = "warn";
        } else if(lvlint == 3) {
          lvlstr = "error";
        } else if(lvlint == 4) {
          lvlstr = "fatal";
        } else {
          lvlstr = "";
        }
        return lvlstr;
      }

                  /*$('#submitbut').change(function(){
                 $.ajax({
                       type: "GET",
                       url: "send.php",
                       data: "query="+document.form.textarea.value,
                       success: function(msg){
                        document.getElementById("Div_Where_you_want_the_response").innerHTML = msg                         }
                     })
            });*/

      function startLoop() {
        if(!$("#autoupdate").is(":checked")) clearInterval(myInterval);  // stop
        else myInterval = setInterval( "loadlog(false)", <?=LOGUPDATEFREQUENCY?> );  // run   
      }
    </script>
</head>
<body>
  <div id="page">
  <div id="al">
  <div id="tabs">
  <div id="menu">
  <ul class="tablist">
    <? do_action("admincp_begintablist"); ?>
    <? $taborder = 1; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Debug</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Log</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Users</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Modules</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Log viewer</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Register</a></li>
    <? $taborder++; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Mails</a></li>
    <? $GLOBALS["taborder"] = $taborder; do_action("admincp_endtablist"); ?>
  </ul>
  <div id="timeinmenu"></div>
  <div id="userfield"><ul><? 
    if(isset($_SESSION["user_name"])) {
      echo "<li>" . $_SESSION["user_name"] . "</li><li><a href='../'>Page</a></li><li><a href='#' onclick='logoutuser()'>Logout</a></li>";
    }
  ?></ul></div>
  </div>
  <div id="container">
  <? do_action("admincp_begintabs"); ?>
  <? $taborder = 1; ?>
    <div id="tabs-<?=$taborder?>">
      <b>Path: </b><?=PATH?><br />
      <b>Date: </b><?=date("Y-m-d H:m:s"); ?><br />
      <b>Logfile: </b><?=LOGFILE?><br />
      <b>Absolute path: </b> <?=ABSPATH?><br />
      <b>Requested URI: </b> <?=$_SERVER["REQUEST_URI"]?><br />
      <b>Session var_dump:</b>
      <? var_dump($_SESSION); ?>
    </div>
    <? $taborder++; ?>
    <div id="tabs-<?=$taborder?>">
      <input type="submit" name="submitbut" id="submitbut" value="Clear log!" onclick=clearlog(); />
      Auto-refresh:<input type="checkbox" name="autoupdate" id="autoupdate" /> 
      <input type="submit" name="writedebug" id="writedebug" value="Write DEBUG!" onclick=testtofile("d"); />
      <input type="submit" name="writeinfo" id="writeinfo" value="Write INFO!" onclick=testtofile("i"); />
      <input type="submit" name="writewarn" id="writewarn" value="Write WARN!" onclick=testtofile("w"); />
      <input type="submit" name="writeerror" id="writeerror" value="Write ERROR!" onclick=testtofile("e"); />
      <input type="submit" name="writefatal" id="writefatal" value="Write FATAL!" onclick=testtofile("f"); />
      <pre><div id="logcontent"></div></pre>
      <br />
    </div>
    <? $taborder++; ?>
    <div id="tabs-<?=$taborder?>">
      <?php if(LOGLEVEL == "debug") {
        $array = [ "firstname" => "Erik", "lastname" => "Bročko", "city" => "Trnava"];
        $jsoned = json_encode($array);
        echo($jsoned . "<br />");
        $original = json_decode($jsoned, true);
        echo("<b>First: </b>" . $original["firstname"] . " <b>Last: </b>" . $original["lastname"]);
        if(isset($original["city"])) { echo(" <b>City:</b>". $original["city"]); } else {echo(" <b>City</b> is not set! <br />"); }    ?>
        <!-- <b>User 1: <?php if(user_exists(1)){ echo("Exists!"); } ?></b><br /> -->
        <br /><b>Userdata: <?=get_userdata(1)?></b><br /> 
       <?php } ?>
      <form>
        Name:
        <input type="submit" value="Register" name="registerbut" id="registerbut" />
      </form>
      <table class="gridtable">
        <tr>
          <th colspan="5" class="head">MySQL</th>
          <th colspan="3" class="head">JSON userdata</th>
        </tr>
        <tr>
          <th><b>#</b></th>
          <th><b>Username</b></th>
          <th><b>E-mail</b></th>
          <th><b>Rank</b></th>
          <th><b>Created</b></th>
          <th><b>First name</b></th>
          <th><b>Last name</b></th>
          <th><b>City</b></th>
          <th><b>Actions</b></th>
        </tr>
        <?php
          sql_connect();
          global $mysqli;
          $sql = "SELECT * FROM `users`";
          if(!$result = $mysqli->query($sql)){
            logtofile("There was an error running the query! (" . $mysqli->errno . ") " . $mysqli->error, "f");
            die('There was an error running the query [' . $mysqli->error . ']');
          } 
          while($row = mysqli_fetch_array($result)) {
            $userdata = json_decode($row["data"], true); ?>
            <tr>
              <td><?=$row['id']?></td>
              <td><?=$row['username']?></td>
              <td><?=$row['email']?></td>
              <td><?=getrank($row['rank'])?></td>
              <td><?=$row['created']?></td>
              <td><? if(isset($userdata['firstname'])) echo $userdata['firstname']; ?></td> 
              <td><? if(isset($userdata['lastname'])) echo $userdata['lastname']; ?></td> 
              <td><? if(isset($userdata['city'])) echo $userdata['city']; ?></td> 
              <td><a href="#" onclick="removeuser(<?=$row['username']?>)"><img class="removeuser" src="<?=THEMESDIR?><?=CPTHEME?>/img/remove_user_whitebg.png" alt="Remove user" /></a>
                  <a href="#" onclick="edituser(<?=$row['username']?>)"><img class="edituser" src="<?=THEMESDIR?><?=CPTHEME?>/img/usersettings.png" alt="User settings" /></a>
              </td> 
            </tr>     
          <?php } ?>
      </table> 
    </div>
    <? $taborder++; ?>
    <div id="tabs-<?=$taborder?>">
      <? showmodules(); ?>
    </div>
    <? $taborder++; ?>
      <div id="tabs-<?=$taborder?>">
        <p id="msg">&nbsp;</p>
        <div id="stupidtable" style="border: none">
          <table class="stupidtable">
            <thead>
              <tr>
                <th data-sort="int"><span>Date</span></th>
                <th data-sort="int"><span>Level</span></th>
                <th data-sort="string-ins"><span>Text</span></th>
                <th data-sort="int"><span>Data</span></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $handle = fopen(LOGFILE, "r");
                if ($handle) {
                  while (($line = fgets($handle)) !== false) {
                    $date = substr($line, 0, 19);
                    $rest = substr($line, 19);
                    $level = substr($rest, 2);
                    $act = "";
                    $pos = 0;
                    $lvlint = 0;
                    while($act != "]" || $pos < 6) {
                      $act = substr($level, $pos, 1);
                      if($act != "]") {
                        $lvlint = leveltoint($act);
                        break;
                      }
                      $pos++;
                    }
                    if(substr($rest, 7, 1) == "]") $rest = substr($rest, 9);
                    else $rest = substr($rest, 8);
                    $data = "";
                    $act = "";
                    $pos = 0;
                    if(substr($rest, -2, 1) == "]") {
                      while($act != "[") {
                        $act = substr($level, $pos, 1);
                        if($act == "[") {
                          break;
                        }
                        $data = $act . $data;
                        $pos--;
                      }
                    }
                    $data = substr($data, 0, -3);
                    $rest = substr($rest, 0, $pos - 1);
                    echo "
                      <tr>
                      <td data-sort-value=" . strtotime($date) . ">$date</td>
                      <td data-sort-value=$lvlint><div class='level$lvlint'>" . inttolevel($lvlint) . "</div></td>
                      <td>$rest</td>";
                      if(trim($data) == "") echo("<td data-sort-value=0></td>");
                      else echo("<td>$data</td>");
                      echo "</tr>";
                      //echo $date . "/" . $lvlint . "/" . $rest . "/" . $data . "<br />";
                  }
                } else {
                  echo('<script type="text/javascript"> $.noty.consumeAlert({layout: "topCenter", type: "error", dismissQueue: true});
                  alert("Failed to load log file!"); </script>'); } 
                  fclose($handle);
                ?>
            </tbody>
  </table>
  </div>
  </div>
  <? $taborder++; ?>
  <div id="tabs-<?=$taborder?>">
    <div id="registerformdiv">
      <form id="registerform" action="<?=PATH."func/users.php"?>" method="post">
        <input type="hidden" name="action" value="register" />
        Username: <input type="text" length="128" name="username" id="registerusername" /><br />
        E-mail: <input type="text" name="email" id="registeremail" /><br />
        Password: <input type="password" name="password" id="registerpass" /><br />
        <input type="hidden" value="{}" name="data" />
        <input type="button" onclick="registeruser()" value="Register!" name="registerbutt" />
      </form>
    </div>
  </div>
  <? $taborder++; ?>
  <div id="tabs-<?=$taborder?>">
    <input type="text" value="" id="sendmailcode" /> <input type="button" value="Send test mail!" onclick="sendtestmail()" />
  </div>

  <? $GLOBALS["taborder"] = $taborder; do_action("admincp_endtabs"); ?>
</div>
</div>
</div>

<? if(TIMEMETER) { ?>
<div id="timeplaceholder">
<div id="time">
<?=round(((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000),3)?> ms
</div>
</div>
<? } ?>
<script type="text/javascript">
$( "#time" ).animate({
  opacity: 1
}, 1000, "linear", function() {
  setTimeout(function(){
  $( "#time" ).animate({
  opacity: 0
}, 500, "linear", function() {
});
}, 3000);
});

</script>
<? } ?>
<? include("footer.php"); ?>