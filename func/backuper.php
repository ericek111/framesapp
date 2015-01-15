<?php
define("BACKUPER_IGNORESTRING", "!!!backuper_ignorethisfile!!!");
define("BACKUPER_IGNOREFOLDER", ".backuper_ignorefolder");
require_once("init.php");

function backuper_backapp() {
	return backuper_backfolder(ABSPATH);
}
function backuper_backfolder($folder) {
	$source = substr(str_replace('/', DIRECTORY_SEPARATOR, $folder), 0, -1);
	$destination = ABSPATH . "/backups/backup_" . date('Y-m-d_H-i-s') . ".zip";
	if (!extension_loaded('zip') || !file_exists($source)) {
		return false;
	}
	$ignoredfolders = [];
	$zip = new ZipArchive();
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		return false;
	}
	
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);
			
			// Ignore "." and ".." folders
			if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;
			
			$file = realpath($file);
			if (is_dir($file) === true) {
				if (file_exists($file . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
					array_push($ignoredfolders, $file);
					echo "<b>" . $file . "</b></br>";
				} else {
					if (!arrayvalue_contains($ignoredfolders, $file)) {
						$save = substr($file, strlen($source) + 1);
						$save = str_replace("\\", "/", $save);
						$zip->addEmptyDir($save);
					} else {
						array_push($ignoredfolders, $file);
					}
				}
			} else if (is_file($file) === true) {
				if (!arrayvalue_contains($ignoredfolders, $file)) {
					if (file_exists(dirname($file) . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
						if (!in_array($file, $ignoredfolders)) array_push($ignoredfolders, dirname($file));
					} else {
						$firstlines = getfirstlines($file, 5, true);
						if ((strpos($firstlines, BACKUPER_IGNORESTRING) !== false) || (strpos($file, BACKUPER_IGNOREFOLDER) !== false)) {
						} else {
							$save = substr($file, strlen($source) + 1);
							$save = str_replace("\\", "/", $save);
							$zip->addFromString($save, file_get_contents($file));
						}
					}
				} else {
					echo "Hovno! " . $file . "<br />";
				}
			}
		}
	} else if (is_file($source) === true) {
		$zip->addFromString(basename($source), file_get_contents($source));
	}
	return $zip->close();
}

if (isset($_GET["action"])) {
	if ($_GET["action"] == "backupapp") {
		if (backuper_backapp()) {
			echo "Successfully backuped!";
		} else {
			echo "Error occured!";
		}
	}
}
?>