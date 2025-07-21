<?php 

// ---------------------------------------

/**
 * Create Full Path
 */
function create_full_path($path) {
	if (!file_exists(dirname($path))) {
		mkdir(dirname($path), 0755, true);
	}
}

/**
 * Delete File And Empty Paths
 */
function delete_full_path($path) {
	$deleted = @unlink($path);
	if ($deleted) {
		$empty = true;
		while ($empty && ($path = dirname($path)) && $path != "data") {
			$empty = @rmdir($path);
		} 
	}
	return $deleted;
}


// ---------------------------------------

/**
 * Get HTML Data from file
 */
function get_HTML($filename) {
	return @file_get_contents("data/$filename.html");
}

/**
 * Save data into HTML file
 */
function save_HTML($filename, $html) {
	$path = "data/$filename.html";
	create_full_path($path);
	return @file_put_contents($path, $html);
}

/**
 * Delete HTML file (And path if empty)
 */
function delete_HTML($filename) {
	return delete_full_path("data/$filename.html");
}

// ---------------------------------------

/**
 * Get JSON Data from file
 */
function get_JSON($filename) {
	$json = @file_get_contents("data/$filename.json");
	$data = json_decode($json, true);
	if (!$data) {
		return [];
	}
	return $data;
}

/**
 * Get JSON RAW file contents
 */
function get_JSON_RAW($filename) {
	$json = @file_get_contents("data/$filename.json");
	if (!$json) {
		return '[]';
	}
	return $json;
}

/**
 * Save data into JSON file
 */
function save_JSON($filename, $data) {
	$path = "data/$filename.json";
	create_full_path($path);
	$json = json_encode($data,  JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
	return @file_put_contents($path, $json);
}

/**
 * Check if JSON file Exists
 */
function exists_JSON($filename) {
	$path = "data/$filename.json";
	return file_exists($path);
}

// ---------------------------------------
