<?php 

// ---------------------------------------

function get_server_protocol() {
	return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
}

// ---------------------------------------

/**
 * Generate JSON Response
 */
function json_response($data) {
	header('Content-Type: application/json');
	echo json_encode($data, JSON_NUMERIC_CHECK);
	exit;
}

/**
 * Send OK and exit
 */
function send_ok() {
	json_response(["OK" => 1]);
	exit;
}

/**
 * Not found and exit
 */
function not_found($msg = "Not Found!") {
	header(get_server_protocol() . ' 404 Not Found');
	json_response(["msg" => $msg]);
	exit;
}

/**
 * Send error and exit
 */
function send_error($msg) {
	header(get_server_protocol() . ' 400 Bad Request');
	json_response(["msg" => $msg]);
	exit;
}

// ---------------------------------------

/**
 * Return JSON RAW Response
 */
function json_raw_response($json) {
	header('Content-Type: application/json');
	echo $json;
	exit;
}

// ---------------------------------------
