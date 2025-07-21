<?php 

class BooksCollectionStore {

	function getAll() {
		$courseStore = new CoursesCollectionStore();
		$courses = $courseStore->getAll();
		return $courses;
	}

	function get($id) {
		$courseStore = new CoursesCollectionStore();
		$course = $courseStore->get($id);
		return $course;
	}

	function getRawFullBook($id) {
		// Check Exists
		$this->get($id);
		// Get Full Book
		$filename = "books/$id";
		if (!exists_JSON($filename)) {
			$this->regenerate($id);
		}
		$jsonBook = get_JSON_RAW($filename);
		return $jsonBook;
	}

	function getFullBook($id) {
		// Check Exists
		$this->get($id);
		// Get Full Book
		$filename = "books/$id";
		if (!exists_JSON($filename)) {
			$this->regenerate($id);
		}
		$book = get_JSON($filename);
		return $book;
	}

	// ---------------------------------------------

	function regenerate($id) {
		// Get Course Info
		$book = $this->get($id);
		// Get Course Units
		$unitStore = new UnitsCollectionStore();
		$book['units'] = $unitStore->getAllByCourse($book['id']);
		// Get Unit Activities
		$activityStore = new ActivitiesCollectionStore();
		foreach ($book['units'] as &$unit) {
			$unit['activities'] = $activityStore->getAllByUnit($unit['id']);
			// Get Activity Contents
			foreach ($unit['activities'] as &$activity) {
				$contents = $activityStore->getContents($activity['id']);
				update_item($activity, $contents);
			}
		}
		// Save Full Book
		$this->save($book);
	}

	// ---------------------------------------------

	function create($item) {
		// Save Full Book
		$this->save($item);
	}

	function update($item) {
		$id = $item['id'];
		$book = $this->getFullBook($id);
		update_item($book, $item);
		$this->save($book);
	}

	function delete($id) {
		$bookFile = "data/books/$id.json";
		delete_full_path($bookFile);
	}

	// ---------------------------------------------

	function addItem($type, $item) {
		if (!in_array($type, ['unit', 'activity'])) {
			return;
		}
		$id = $item['course_id'];
		$book = $this->getFullBook($id);
		switch ($type) {
			case 'unit':
				if (empty($book['units'])) {
					$book['units'] = [];
				}
				array_add_item($book['units'], $item);
				break;
			case 'activity':
				$index = array_get_first_index($book['units'], $item['unit_id']);
				if ($index !== false) {
					if (empty($book['units'][$index]['activities'])) {
						$book['units'][$index]['activities'] = [];
					}
					array_add_item($book['units'][$index]['activities'], $item);
				}
				break;
		}
		// Save Full Book
		$this->save($book);
	}

	function updateItem($type, $item) {
		if (!in_array($type, ['unit', 'activity'])) {
			return;
		}
		$id = $item['course_id'];
		$book = $this->getFullBook($id);
		switch ($type) {
			case 'unit':
				array_update_item($book['units'], $item);
				break;
			case 'activity':
				$index = array_get_first_index($book['units'], $item['unit_id']);
				if ($index !== false) {
					array_update_item($book['units'][$index]['activities'], $item);
				}
				break;
		}
		// Save Full Book
		$this->save($book);
	}

	function deleteItem($type, $item) {
		if (!in_array($type, ['unit', 'activity'])) {
			return;
		}
		$id = $item['course_id'];
		$book = $this->getFullBook($id);
		switch ($type) {
			case 'unit':
				array_delete_item($book['units'], $item['id']);
				break;
			case 'activity':
				$index = array_get_first_index($book['units'], $item['unit_id']);
				if ($index !== false) {
					array_delete_item($book['units'][$index]['activities'], $item['id']);
				}
				break;
		}
		// Save Full Book
		$this->save($book);
	}

	// ---------------------------------------------

	protected function save($book) {
		$id = $book['id'];
		$filename = "books/$id";
		save_JSON($filename, $book);		
	}

}
