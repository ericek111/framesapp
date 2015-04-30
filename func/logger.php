<?php
require_once ("init.php");

// Alias for logtofile()
function ltf($text, $level) {
		logtofile($text, $level);
}

function leveltoint($lvlstr) {
		if (strtolower($lvlstr) == "d" || strtolower($lvlstr) == "debug") {
				$lvlint = 0;
		} elseif (strtolower($lvlstr) == "i" || strtolower($lvlstr) == "info") {
				$lvlint = 1;
		} elseif (strtolower($lvlstr) == "w" || strtolower($lvlstr) == "warn") {
				$lvlint = 2;
		} elseif (strtolower($lvlstr) == "e" || strtolower($lvlstr) == "error") {
				$lvlint = 3;
		} elseif (strtolower($lvlstr) == "f" || strtolower($lvlstr) == "fatal") {
				$lvlint = 4;
		} else {
				$lvlint = 5;
		}
		return $lvlint;
}

function inttolevel($lvlint) {
		if ($lvlint == 0) {
				$lvlstr = "debug";
		} elseif ($lvlint == 1) {
				$lvlstr = "info";
		} elseif ($lvlint == 2) {
				$lvlstr = "warn";
		} elseif ($lvlint == 3) {
				$lvlstr = "error";
		} elseif ($lvlint == 4) {
				$lvlstr = "fatal";
		} else {
				$lvlstr = "";
		}
		return $lvlstr;
}

// Log entry to file specified in config.
function logtofile($text, $level) {
		if (leveltoint(LOGLEVEL) <= leveltoint($level)) {
				if (strtolower($level) == "d" || strtolower($level) == "debug") {
						$level = "[DEBUG] ";
				} elseif (strtolower($level) == "i" || strtolower($level) == "info") {
						$level = "[INFO] ";
				} elseif (strtolower($level) == "w" || strtolower($level) == "warn") {
						$level = "[WARN] ";
				} elseif (strtolower($level) == "e" || strtolower($level) == "error") {
						$level = "[ERROR] ";
				} elseif (strtolower($level) == "f" || strtolower($level) == "fatal") {
						$level = "[FATAL] ";
				} else {
						$level = "";
				}
				$text = date("Y-m-d H:m:s ") . $level . $text . "\n";
				$fh = fopen(LOGFILE, 'a') or die("can't open file");
				fwrite($fh, $text);
				fclose($fh);
		}
}
if (isset($_GET["action"])) {
		if ($_GET["action"] == "showlog") {
				echo file_get_contents(LOGFILE);
		} elseif ($_GET["action"] == "clearlog") {
				file_put_contents(LOGFILE, "");
				echo ("cleared");
		} elseif ($_GET["action"] == "testwrited") {
				logtofile("Test.", "d");
		} elseif ($_GET["action"] == "testwritei") {
				logtofile("Test.", "i");
		} elseif ($_GET["action"] == "testwritew") {
				logtofile("Test.", "w");
		} elseif ($_GET["action"] == "testwritee") {
				logtofile("Test.", "e");
		} elseif ($_GET["action"] == "testwritef") {
				logtofile("Test.", "f");
		}
}
?>