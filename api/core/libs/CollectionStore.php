<?php 

class CollectionStore {

	protected $name;
	protected $primaryKey = 'id';

	protected $data = [];

	function __construct($settings = null) {
		if (!empty($settings)) {
			$this->name = $settings['name'];
			
			if (!empty($settings['primaryKey'])) {
				$this->primaryKey = $settings['primaryKey'];
			}
		}

		$this->data = array_values(get_JSON($this->name));
	}

	// ------------------------------------------------

	function getAll() {
		return $this->data;
	}

	function getFiltered($filters) {
		return array_filter_items($this->data, $filters);
	}

	function get($id) {
		$item = array_get_first_item($this->data, $id, $this->primaryKey);
		if (!$item) {
			not_found("Item $id not found in " . $this->name . "!");
		}
		return $item;
	}

	// ------------------------------------------------

	function create($item, $save = true) {
		array_add_item($this->data, $item);
		if ($save) {
			save_JSON($this->name, $this->data);
		}
		return $item;
	}

	function update($item, $save = true) {
		// Check if exists
		$id = $item[$this->primaryKey];
		$this->get($id);
		// Update Course
		array_update_item($this->data, $item);
		if ($save) {
			save_JSON($this->name, $this->data);
		}
		return $item;
	}

	function delete($id, $save = true) {
		// Check if exists
		$this->get($id);
		// Delete Course
		array_delete_item($this->data, $id);
		if ($save) {
			save_JSON($this->name, $this->data);
		}
	}

	// ------------------------------------------------

	function save() {
		save_JSON($this->name, $this->data);
	}

}