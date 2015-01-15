<? include("header.php"); ?>
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
          $.cookie(tabCookieName, newIndex);
          window.location.hash = "tab" + newIndex;
          }
        });

        $("#autoupdate").change(function() {startLoop(); loadlog(false); });
      $("#container").fadeIn(500);

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
      function openloginuser() {
      	$("#tabs").tabs("option", "active", 1);
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
    </script>
</head>
<body>
  <div id="page">
  <div id="al">
  <div id="tabs">
  <div id="menu">
  <ul class="tablist">
    <? do_action("index_begintablist"); ?>
    <? $taborder = 1; ?>
    <li><a href="#tabs-<?=$taborder?>" class="tab">Debug</a></li>
    <? $taborder++; ?>
    <li style="display:none"><a href="#tabs-<?=$taborder?>" class="tab">Login</a></li>
    <? $GLOBALS["taborder"] = $taborder; do_action("index_endtablist"); ?>
  </ul>
  <div id="timeinmenu"></div>
  <div id="userfield"><ul><? 
  	if(isset($_SESSION["user_name"])) {
  		echo "<li>" . $_SESSION["user_name"] . "</li><li><a href='./admin'>Admin</a></li><li><a href='#' onclick='logoutuser()'>Logout</a></li>";
  	} else {
	  	echo '<a href="#" onclick=openloginuser() class="tab">Login</a>';
	  }
  ?></ul></div>
  </div>
  <div id="container">
  <? do_action("index_begintabs"); ?>
  <? $taborder = 1; ?>
    <div id="tabs-<?=$taborder?>">
      <b>Path: </b><?=PATH?><br />
      <b>Date: </b><?=date("Y-m-d H:m:s"); ?><br />
      <b>Logfile: </b><?=LOGFILE?><br />
      <b>Absolute path: </b> <?=ABSPATH?><br />
      <b>Requested URI: </b> <?=$_SERVER["REQUEST_URI"]?>
    </div>
    <? $taborder++; ?>
    <div id="tabs-<?=$taborder?>">
    	<b>Login:</b><br />
    	<div id="loginformdiv">
    	  <form id="loginform" action='<?=PATH."func/users.php"?>' method="post">
        	<input type="hidden" name="action" value="login" />
        	Username: <input type="text" length="128" name="username" id="loginusername" /><br />
        	Password: <input type="password" name="password" id="loginpass" /><br />
        	<input type="hidden" value="{}" name="data" />
        	<input type="button" onclick=loginuser() value="Login!" name="loginbutt" />
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
    </div>

  <? $GLOBALS["taborder"] = $taborder; do_action("index_endtabs"); ?>
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
<? include("footer.php"); ?>