<?php
//
// These lines must be clear, because backuper will ignore file, if there will be BACKUPER_IGNORESTRING on the first 5 lines.
//

define("BACKUPER_IGNORESTRING", "!!!backuper_ignorethisfile!!!");
define("BACKUPER_IGNOREFOLDER", ".backuper_ignorefolder");

function zipfile($files, $dest) {
}
function backuper_getfiles($folder, $verbose = false, $relpath = false, $ignoringfiles = true, $folders = true) {
	if ($verbose) echo "<b> BACKUPING FROM </b>" . $folder . EOL;
	$source = formatpath($folder);
	$filesarr = array();
	$ignoredfolders = array();
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);
			// Ignore "." and ".." folders
			if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;
			$file = realpath($file);
			if (is_dir($file) === true && $folders) {
				if ($ignoringfiles) {
					if (file_exists($file . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
						array_push($ignoredfolders, $file);
						if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
						else array_push($filesarr, $file);
						if ($verbose) echo "<b>Ignoring folder:</b> " . $file . "</br>";
					} else {
						if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
						else array_push($filesarr, $file);
					}
				} else {
					if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
					else array_push($filesarr, $file);
				}
			} else if (is_file($file) === true) {
				if ($ignoringfiles) {
					if (str_contains($file, BACKUPER_IGNOREFOLDER)) {
						if (!in_array(dirname($file), $ignoredfolders)) {
							if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
							else array_push($filesarr, $file);
							array_push($ignoredfolders, dirname($file));
							if ($verbose) echo "Ignoring file [1]: " . $file . "<br />";
						}
					} elseif (file_exists(dirname($file) . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
						if (!in_array(dirname($file), $ignoredfolders)) {
							array_push($ignoredfolders, dirname($file));
							if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
							else array_push($filesarr, $file);
							if ($verbose) echo "Ignoring file [2]: " . $file . "<br />";
						}
					} elseif (!arrayvalue_contains($ignoredfolders, dirname($file))) {
						if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", formatpath($file))));
						else array_push($filesarr, $file);
					} else {
						if ($verbose) echo "Ignoring file [3]: " . $file . "<br />";
					}
				} else {
					$save = substr($file, strlen($source) + 1);
					if ($relpath) array_push($filesarr, formatpath(str_replace($source, "", $file)));
					else array_push($filesarr, $file);
				}
			}
		}
		return $filesarr;
	} else if (is_file($source) === true) {
		array_push($filesarr, basename($source));
		return $filesarr;
	} else {
		return $filesarr;
	}
}

function backuper_getdirs($folder, $verbose = false, $relpath = false) {
	$source = substr(str_replace('/', DIRECTORY_SEPARATOR, $folder), 0, -1);
	$foldersarr = array();
	$ignoredfolders = array();
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);
			// Ignore "." and ".." folders
			if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;
			
			$file = realpath($file);
			if (is_dir($file) === true && !in_array($file, $foldersarr)) {
				if (file_exists($file . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
					array_push($ignoredfolders, $file);
					if ($verbose) echo "<b>Ignoring folder:</b> " . $file . "</br>";
				} else {
					if (!arrayvalue_contains($ignoredfolders, $file)) {
						$save = substr($file, strlen($source) + 1);
						$save = str_replace("\\", "/", $save);
						if ($relpath) array_push($foldersarr, formatpath($save));
						else array_push($foldersarr, formatpath(ABSPATH . $save));
					} else {
						array_push($ignoredfolders, str_replace('/', DIRECTORY_SEPARATOR, ABSPATH . $save));
					}
				}
			} else if (is_file($file) === true) {
				if (!arrayvalue_contains($ignoredfolders, $file)) {
					if (file_exists(dirname($file) . DIRECTORY_SEPARATOR . BACKUPER_IGNOREFOLDER)) {
						if (!in_array($file, $ignoredfolders)) array_push($ignoredfolders, dirname($file));
					}
				} else {
					if ($verbose) echo "Ignoring file: " . $file . "<br />";
				}
			}
		}
		return $foldersarr;
	} else if (is_file($source) === true) {
		array_push($filesarr, basename($source));
		return $foldersarr;
	} else {
		return $foldersarr;
	}
}

function backuper_backfolder($folder, $verbose = false) {
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
	$files = backuper_getfiles($folder, $verbose, true, true, false);
	if (is_dir($source)) {
		foreach ($files as $file) {
			if (!is_dir(ABSPATH . $file)) {
				if ($verbose) echo "Backuping file: " . $file . "<br />";
				$zip->addFromString($file, file_get_contents(ABSPATH . $file));
			}
		}
	} else if (is_file($source)) {
		if ($verbose) echo "<b>Backuping only one file: </b>" . $source . "<br />";
		$zip->addFromString(basename($source), file_get_contents($source));
	}
	return $zip->close();
}

function backuper_backapp($verbose = false) {
	return backuper_backfolder(ABSPATH, $verbose);
}
?>