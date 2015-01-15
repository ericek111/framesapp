<?
function mertex_newtab() {
	$taborder = $GLOBALS["taborder"];
	$taborder++;
  $GLOBALS["taborder"] = $taborder;
	?>
	<div id="tabs-<?=$taborder?>">
	<h3>Connected players:</h3>
	<div id="mertexstatusdiv">
	</div>
		<script type="text/javascript">
			function updatestatus() {
        $.get( "<?=MODDIR?>mertex-status/getstatus.php?action=getstatus", function(data) {
        	console.log(data);
          if(data == "") {
            $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true});
            alert("Error whilst trying to load server status! [1]");
          } else {
          	var status = jQuery.parseJSON(data);
          	var text = "";
          	for (key in status) {
          		if(status[key]["ping"] > 0) {
          			text = text + "<span class='online'><b>" + status[key]["name"] + "</b></span>:<br />";
          			text = text + "&nbsp;&nbsp;<b>Ping:</b> " + status[key]["ping"] + "<br />";
          			text = text + "&nbsp;&nbsp;<b>Players:</b><br />";
          			for (player in status[key]["players"]) {
          				text = text + "&nbsp;&nbsp;&nbsp;&nbsp;<b>" + status[key]["players"][player].split("/")[0] + ":</b><br />";
          				text = text + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>UUID:</b> " + status[key]["players"][player].split("/")[1] + "<br />";
          				//if(player < status[key]["players"].length - 1) { text = text + ", "; }
          			}
          		} else {
          			text = text + "<span class='offline'><b>" + status[key]["name"] + "</b></span> - <span class='offline'><b>OFFLINE</b></span>!<br />";
          		}
        			console.log(status[key]);
						}
						$("#mertexstatusdiv").html(text);
          }
        })
        .fail(function(data) {
          $.noty.consumeAlert({layout: 'topCenter', type: 'error', dismissQueue: true});
          alert("Error whilst trying to load server status! [2]");
        });
			}
      updatestatus();
		</script>
	</div>
	<?
}
function mertex_registertab() {
	$taborder = $GLOBALS["taborder"];
	$taborder++;
  $GLOBALS["taborder"] = $taborder;
	?>
	<li><a href="#tabs-<?=$taborder?>" class="tab">Mertex.eu</a></li>
	<?
}

function mertex_registercss() {
	echo '<link rel="stylesheet" type="text/css" href="' . MODDIR . "mertex-status" . "/style.css" . '">';
}
add_action("index_endtablist", "mertex_registertab");
add_action("index_endtabs", "mertex_newtab");
add_action("index_headerendloading", "mertex_registercss");

?>