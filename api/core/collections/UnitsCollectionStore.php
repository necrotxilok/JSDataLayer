<?php 

class UnitsCollectionStore extends CollectionStore {

	function __construct($settings = null) {
		parent::__construct([
			'name' => 'units'
		]);
	}

	function getAllByCourse($course_id) {
		return $this->getFiltered(['course_id' => $course_id]);
	}

	function delete($id, $save = true) {
		$activitiesStore = new ActivitiesCollectionStore();
		$activitiesStore->deleteAll($id);
		parent::delete($id, $save);
	}

	function deleteAll($course_id) {
		$units = $this->getAllByCourse($course_id);
		foreach ($units as $unit) {
			$this->delete($unit['id'], false);
		}
		$this->save();
	}

}
