<?php
define("BACKUPER_VERSIONSPATH", formatpath(ABSPATH . "versions/"));
define("BACKUPER_BUILDSPATH", formatpath(BACKUPER_VERSIONSPATH . "builds/"));
define("BACKUPER_PATCHESPATH", formatpath(BACKUPER_VERSIONSPATH . "patches/"));
define("BACKUPER_DIFFCONTEXT", 3);
define("BACKUPER_DIFFSHORTMODE", false);
define("BACKUPER_VERSIONSLISTFILEPATH", BACKUPER_VERSIONSPATH . "versionslist");
define("BACKUPER_CURRENTVERSIONFILEPATH", BACKUPER_VERSIONSPATH . "currentversion");
define("BACKUPER_DOWNLOADEDPATCHES", formatpath(BACKUPER_VERSIONSPATH . "downloaded/"));
$currentversionarr = explode("/", file_get_contents(BACKUPER_CURRENTVERSIONFILEPATH));
define("BACKUPER_CURRENTPATCH", $currentversionarr[0]);
define("BACKUPER_CURRENTBUILD", $currentversionarr[1]);
define("BACKUPER_CURRENTVERSION", "");

function versions_getversionlist() {
	$handle = fopen(BACKUPER_VERSIONSLISTFILEPATH, "r");
	$verlist = array();
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$verlistarr = explode("/", $line);
			$verlistarr = array("patch" => $verlistarr[0], "build" => $verlistarr[1], "version" => $verlistarr[2]);
			array_push($verlist, $verlistarr);
		}
		fclose($handle);
	} else return false;
	return $verlist;
}

$backuper_versionslist = versions_getversionlist();

function versions_getlatestbuildnum() {
	if (!(new \FilesystemIterator(BACKUPER_BUILDSPATH))->valid()) return 0;
	else return (count(glob(BACKUPER_BUILDSPATH . "*")));
}

function versions_getlatestpatchnum() {
	if (!(new \FilesystemIterator(BACKUPER_PATCHESPATH))->valid()) return 0;
	else return (count(glob(BACKUPER_PATCHESPATH . "*")));
}

function versions_newbuild($verbose = false) {
	$latestbuildnum = versions_getlatestbuildnum();
	$newbuildnum = $latestbuildnum + 1;
	@mkdir(formatpath(BACKUPER_BUILDSPATH . $newbuildnum . "/"), 0777, true);
	$files = backuper_getfiles(ABSPATH, false, true);
	$folders = backuper_getdirs(ABSPATH, false, true);
	foreach ($folders as $folder) {
		$dest = formatpath(BACKUPER_BUILDSPATH . $newbuildnum . "/" . $folder . "/");
		if (!file_exists($dest)) {
			mkdir($dest, 0777, false);
			if ($verbose) echo "<b>Creating directory:</b> " . $dest . EOL;
		}
	}
	foreach ($files as $file) {
		if (!is_dir(ABSPATH . $file)) {
			$dest = formatpath(BACKUPER_BUILDSPATH . $newbuildnum . "/" . $file);
			$src = formatpath(ABSPATH . $file);
			copy($src, $dest);
			if ($verbose) echo "Copying: " . $src . " <b> TO </b>" . $dest . EOL;
		}
	}
	return true;
}

function versions_createpatch($oldbuild, $newbuild, $verbose = false) {
	$oldpath = formatpath(BACKUPER_BUILDSPATH . $oldbuild . DIRECTORY_SEPARATOR);
	$newpath = formatpath(BACKUPER_BUILDSPATH . $newbuild . DIRECTORY_SEPARATOR);
	if (!file_exists($oldpath)) return 1;
	if (!file_exists($newpath)) return 2;
	$latestpatchnum = versions_getlatestpatchnum();
	$newpatchnum = $latestpatchnum + 1;
	$patchpath = BACKUPER_PATCHESPATH . $newpatchnum . DIRECTORY_SEPARATOR;
	$oldbuildfiles = backuper_getfiles($oldpath, true, true);
	$oldbuildfolders = backuper_getdirs($oldpath, true, true);
	$newbuildfiles = backuper_getfiles($newpath, true, true);
	$newbuildfolders = backuper_getdirs($newpath, true, true);
	
	$newfiles = array_diff($newbuildfiles, $oldbuildfiles);
	$newfolders = array_diff($newbuildfolders, $oldbuildfolders);
	$deletedfiles = array_diff($oldbuildfiles, $newbuildfiles);
	$deletedfolders = array_diff($oldbuildfolders, $newbuildfolders);
	$commonfiles = array_diff($oldbuildfiles, $newfiles);
	$commonfiles = array_diff($commonfiles, $deletedfiles);
	$commonfiles = array_diff($commonfiles, $deletedfolders);
	$editedfiles = array();
	
	foreach ($commonfiles as $file) {
		$src = formatpath($oldpath . $file);
		$dest = formatpath($newpath . $file);
		if (!filesaresame($src, $dest)) {
			if ($verbose) echo "Edited file: " . $file . EOL;
			array_push($editedfiles, $file);
		}
	}
	
	foreach ($newfiles as $file) {
		if ($verbose) echo "New file: " . $file . EOL;
	}
	
	foreach ($newfolders as $folder) {
		if ($verbose) echo "New folder: " . $folder . EOL;
	}
	
	if (count($newfiles) > 0 || count($newfolders) > 0 || count($deletedfolders) > 0 || count($deletedfiles) > 0 || count($editedfiles) > 0) {
		mkdir($patchpath, 0777, true);
		
		foreach ($newfolders as $newfolder) {
			$dest = formatpath($patchpath . $newfolder);
			if (!file_exists($dest)) {
				mkdir($dest, 0777, true);
				if ($verbose) echo "<b>Creating directory:</b> " . $dest . EOL;
			}
		}
		
		foreach ($newfiles as $newfile) {
			$src = formatpath($newpath . $newfile);
			$dest = formatpath($patchpath . $newfile);
			if (!file_exists(dirname($dest))) {
				mkdir(dirname($dest), 0777, true);
				if ($verbose) echo "<b>Creating directory:</b> " . $dest . EOL;
			}
			copy($src, $dest);
			if ($verbose) echo "Copying: " . $src . " <b> TO </b>" . $dest . EOL;
		}
		
		foreach ($editedfiles as $file) {
			$src = formatpath($oldpath . $file);
			$dest = formatpath($newpath . $file);
			$diffile = formatpath($patchpath . $file . ".patch");
			if (!file_exists(dirname($diffile))) {
				mkdir(dirname($diffile), 0777, true);
				if ($verbose) echo "<b>Creating directory:</b> " . dirname($diffile) . EOL;
			}
			//file_put_contents($diffile, '');
			xdiff_file_diff($src, $dest, $diffile, BACKUPER_DIFFCONTEXT, BACKUPER_DIFFSHORTMODE);
			if ($verbose) echo "<b>Diffing file:</b> " . $file . "<b> TO </b>" . $diffile . EOL;
		}
		return true;
	}
	return false;
}
/**
 * Add version to list
 * @param  int $patch
 * @param  int $build
 * @param  string $version
 * @return bool
 */

function versions_addversion($patch, $build, $version = "") {
	$versionarray = array($build => array("patch" => $patch, "build" => $build, "version" => $version));
	$versionsliststr = "";
	foreach ($versionarray as $versionentry) {
		$versionsliststr.= $patch . "/" . $build . "/" . $version . "\n";
	}
	if (file_put_contents(BACKUPER_VERSIONSLISTFILEPATH, $versionsliststr, FILE_APPEND | LOCK_EX)) return true;
	else return false;
}

function versions_setversion($patch, $build, $version = "") {
	if (file_put_contents(BACKUPER_CURRENTVERSIONFILEPATH, $patch . "/" . $build . "/" . $version)) return true;
	else return false;
}

function versions_isxdiffloaded() {
	return extension_loaded("xdiff");
}

function versions_fullrelease($version = "", $verbose = false) {
	$oldbuild = versions_getlatestbuildnum() - 1;
	$newbuild = versions_getlatestbuildnum();
	$oldcurpatch = BACKUPER_CURRENTPATCH;
	$oldcurbuild = BACKUPER_CURRENTBUILD;
	$oldcurversion = BACKUPER_CURRENTVERSION;
	$newrelpatch = $oldcurpatch + 1;
	$newrelbuild = $newbuild + 1;
	$newrelversion = $version;
	
	if ($verbose) echo "Setting new version on this installation..." . EOL;
	if (versions_setversion($newrelpatch, $newrelbuild, $newrelversion)) {
		if ($verbose) echo "Adding version to versions list..." . EOL;
		if (versions_addversion($newrelpatch, $newrelbuild, $newrelversion)) {
			if ($verbose) echo "Creating new build..." . EOL;
			if (versions_newbuild($verbose)) {
				if ($verbose) echo "Creating new patch..." . EOL;
				if (versions_createpatch($oldbuild, $newbuild, $verbose)) {
					if ($verbose) echo "Zipping patch..." . EOL;
				} else {

				}
			}
		} else {
			unlink(BACKUPER_BUILDSPATH . $newrelbuild);
		}
	}
	
	if ($verbose) echo "New version released!" . EOL;
	return true;
}

function versions_getfilesfrompatch($patch) {
	$downloadedpath = BACKUPER_DOWNLOADEDPATCHES . $patch;
	$unzip = false;
	if (file_exists($downloadedpath)) {
		if (is_dir($downloadedpath)) {
			$unzip = false;
		}
	} elseif (file_exists($downloadedpath . ".zip")) {
		$zippath = $downloadedpath . ".zip";
		$unzip = true;
	} else {
		return false;
	}
	
	if ($unzip) {
		if (!zip_extract($zippath, $downloadedpath)) return false;
	}
	return backuper_getfiles($downloadedpath, false, true);
}

function versions_applypatch($patch, $reverse = false) {
	$downloadedpath = BACKUPER_DOWNLOADEDPATCHES . $patch . DIRECTORY_SEPARATOR;
	$patchfiles = versions_getfilesfrompatch($patch);
	foreach ($patchfiles as $file) {
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		if ($ext == "patch") {
			if ($reverse) xdiff_file_patch(ABSPATH . $file, $downloadedpath . $file, ABSPATH . $file, XDIFF_PATCH_REVERSE);
			else xdiff_file_patch(ABSPATH . $file, $downloadedpath . $file, ABSPATH . $file);
		} else {
			copy($downloadedpath . $file, ABSPATH . $file);
		}
	}
	unlink($downloadedpath);
	return true;
}

function versions_rollbackpatch($patch) {
	versions_applypatch($patch, true);
}
?>