<?php
/* This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * COPYING file for more details. */

/*
 mode is either :
   'output' PHP reads the file and returns its content on the request
   'redirect' PHP only picks a file then send a new URL
*/
$config['mode'] = 'output';
$config['allow_mode_change'] = true;

// Utility functions
// http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

// File fetching functions

function get_directory($name = NULL)
{
	if($name == NULL) {
		// pick a random directory
		$dirs = array_filter(glob("*"), 'is_dir');
		$index = array_rand($dirs);
		return $dirs[$index];
	}

	if (is_dir($name)) {
		return $name;
	} else {
		throw new Exception($name . " is not a directory");
	}
}

// TODO cache
// Returns objects with MIME and path
function get_image_in_directory($directory)
{
	if (is_dir($directory) == false) {
		throw new Exception($directory . " is not a directory");
	}

	$files = scandir($directory);
	$images = array();
	$finfo = new finfo(FILEINFO_MIME);
	foreach($files as $file) {
		$path = $directory . '/' . $file;
		$mime = $finfo->file($path);
		if(startsWith($mime, "image/") == true) {
			$image = (object) array('MIME' => $mime, 'path' => $path);
			array_push($images, $image);
		}
	}

	if(count($images) == 0) {
		throw new Exception("No image found");
	} else {
		$index = array_rand($images);
		return $images[$index];
	}
}

function output_image($image_object)
{
	header('Content-Type: '. $image_object->MIME);
	header('Content-Length: '. filesize($image_object->path));
	readfile($image_object->path);
}

function redirect_to_image($image_object)
{
	$base_url = $_SERVER['HTTP_HOST'];
	header('Location: http://'. $base_url .'/'. $image_object->path);
}

// Main script

$dir_param = $_GET['dir'] ?: NULL;
$mode = $_GET['mode'] ?: NULL;
if(!$mode or $config['allow_mode_change'] == false) {
	$mode = $config['mode'];
}

$dir = get_directory($dir_param);
$i = get_image_in_directory($dir);

if($mode == 'redirect') {
	redirect_to_image($i);
} else {
	output_image($i);
}

?>