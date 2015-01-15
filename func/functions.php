<?php
function isemail($email) {
	//return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
	return true;
}

function randstr($length = 6) {
	$validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
	$validCharNumber = strlen($validCharacters);
	
	$result = "";
	
	for ($i = 0; $i < $length; $i++) {
		$index = mt_rand(0, $validCharNumber - 1);
		$result.= $validCharacters[$index];
	}
	
	return $result;
}

function do_action($tag) {
	global $frames_filter;
	if (isset($frames_filter[$tag]) && function_exists($frames_filter[$tag])) {
		call_user_func_array($frames_filter[$tag], array());
	}
}

function add_action($tag, $callback) {
	global $frames_filter;
	$frames_filter[$tag] = $callback;
}

function getfirstlines($file, $num, $tostring = false) {
	$fh = fopen($file, 'r');
	$i = 0;
	while (!feof($fh)) {
		if ($i < $num) {
			$data[] = fgets($fh);
		} else {
			break;
		}
		$i++;
	}
	fclose($fh);
	if ($tostring) {
		$string = "";
		foreach ($data as $line) {
			$string = $string . $line;
		}
		return $string;
	} else {
		return $data;
	}
}

function str_contains($string, $value) {
	if (strpos($string, $value) !== false) {
		return true;
	}
	return false;
}

function arrayvalue_contains($array, $text) {
	foreach ($array as $val) {
		if (strpos($text, $val) !== false) {
			return true;
		}
	}
	return false;
}

function filesaresame($file1, $file2) {
	return (filesize($file1) == filesize($file2) && md5_file($file1) == md5_file($file2));
}

function formatpath($path) {
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	$path = str_replace("/", DIRECTORY_SEPARATOR, $path);
	return $path;
}

function zip_extract($src, $dest = "") {
	if ($dest == "") {
		$dest = dirname($src);
	}
	$zip = new ZipArchive;
	if ($zip->open($src) === true) {
		$zip->extractTo($dest);
		$zip->close();
		return true;
	} else {
		return false;
	}
}
?>