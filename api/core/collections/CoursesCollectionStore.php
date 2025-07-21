<?php 

class CoursesCollectionStore extends CollectionStore {

	function __construct($settings = null) {
		parent::__construct([
			'name' => 'courses'
		]);
	}

	function delete($id, $save = true) {
		$unitsStore = new UnitsCollectionStore();
		$unitsStore->deleteAll($id);
		parent::delete($id, $save);
	}

}
