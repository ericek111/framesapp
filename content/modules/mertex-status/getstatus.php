<?
if(isset($_GET["action"])) {
	if($_GET["action"] == "getstatus") {
		echo file_get_contents("http://mertex.eu:63210/status");
	}
}
?>