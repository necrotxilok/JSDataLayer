<?php 

class ActivitiesCollectionStore extends CollectionStore {

	function __construct($settings = null) {
		parent::__construct([
			'name' => 'activities'
		]);
	}

	function getAllByUnit($unit_id) {
		return $this->getFiltered(['unit_id' => $unit_id]);
	}

	function getContents($id) {
		$activity = $this->get($id);
		$course_id = $activity['course_id'];
		$content = get_HTML("contents/$course_id/activities/$id/content");
		return [
			'content' => $content
		];
	}

	function saveContents($data) {
		$id = $data['id'];
		$activity = $this->get($id);
		$course_id = $activity['course_id'];
		$content = array_get_field($data, 'content');
		save_HTML("contents/$course_id/activities/$id/content", $content);
		return [
			'content' => $content
		];
	}

	function delete($id, $save = true) {
		$activity = $this->get($id);
		$course_id = $activity['course_id'];
		delete_HTML("contents/$course_id/activities/$id/content");
		parent::delete($id, $save);
	}

	function deleteAll($unit_id) {
		$activities = $this->getAllByUnit($unit_id);
		foreach ($activities as $activity) {
			$this->delete($activity['id'], false);
		}
		$this->save();
	}

}
