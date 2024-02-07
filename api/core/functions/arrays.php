<?php 

// ---------------------------------------

/**
 * Get field from array
 */
function array_get_field($arr, $field, $default = null) {
	if (empty($arr[$field])) {
		return $default;
	}
	return $arr[$field];
}

/**
 * Get fields from array
 */
function array_get_all_fields($arr, $fields, $listValues = false) {
	$result = [];
	if (is_string($fields)) {
		$fields = explode(' ', $fields);
	}
	foreach ($fields as $field) {
		if (empty($field)) continue;
		$value = array_get_field($arr, $field);
		if ($listValues) {
			$result[] = $value;
		} else {
			$result[$field] = $value;
		}
	}
	return $result;
}

/**
 * Get all fields from array, false if any field is missing
 */
function array_get_all_required_fields($arr, $fields, $listValues = false) {
	$result = array_get_all_fields($arr, $fields, $listValues);
	foreach ($result as $field => $value) {
		if (empty($value)) {
			return false;
		}
	}
	return $result;
}

// ---------------------------------------

/**
 * Search item in array
 */
function array_get_first_item($arr, $search, $field = 'id') {
	foreach ($arr as $item) {
		if ($item[$field] == $search) {
			return $item;
		}
	}
	return null;
}

/**
 * Search index of an item in array
 */
function array_get_first_index($arr, $search, $field = 'id') {
	foreach ($arr as $index => $item) {
		if ($item[$field] == $search) {
			return $index;
		}
	}
	return false;
}

/**
 * Filter items in array
 */
function array_filter_items($arr, $filters) {
	if (empty($arr)) {
		return [];
	}
	$filtered = [];
	foreach ($arr as $item) {
		$include = true;
		foreach($filters as $field => $value) {
			if ($item[$field] != $value) {
				$include = false;
				break;
			}
		}
		if ($include) {
			$filtered[] = $item;
		}
	}
	return $filtered;
}

// ---------------------------------------


/**
 * Update Item with new field values
 */
function update_item(&$item, &$newItem) {
	foreach ($newItem as $key => $value) {
		$item[$key] = $value;
	}
}

/**
 * Add item to array
 */
function array_add_item(&$arr, &$newItem) {
	if (count($arr)) {
		$last = end($arr);
		$id = $last['id'] + 1;
	} else {
		$id = 1;
	}
	$newItem['id'] = $id;
	$arr[] = $newItem;
}

/**
 * Update item in array
 */
function array_update_item(&$arr, &$newItem) {
	foreach ($arr as $index => $item) {
		if ($item['id'] == $newItem['id']) {
			update_item($item, $newItem);
			$arr[$index] = $item;
		}
	}
}

/**
 * Delete item in array
 */
function array_delete_item(&$arr, $id) {
	foreach ($arr as $index => $item) {
		if ($item['id'] == $id) {
			unset($arr[$index]);
		}
	}
	$arr = array_values($arr);
}

// ---------------------------------------
