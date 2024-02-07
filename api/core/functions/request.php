<?php 

// ---------------------------------------

/**
 * Get param value from request (if exists)
 */
function get_param($param, $default = null) {
	return array_get_field($_REQUEST, $param, $default);
}

/**
 * Get params from request
 */
function get_all_params($params) {
	return array_get_all_fields($_REQUEST, $params);
}

/**
 * List required params from request
 */
function list_all_required_params($params) {
	$values = array_get_all_required_fields($_REQUEST, $params, true);
	if (!$values) {
		send_error("Required params: $params.");
	}
	return $values;
}

/**
 * Get requiered params from request
 */
function get_all_required_params($params) {
	$data = array_get_all_required_fields($_REQUEST, $params);
	if (!$data) {
		send_error("Required params: $params.");
	}
	return $data;
}

// ---------------------------------------

