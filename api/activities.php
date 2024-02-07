<?php

require_once "core/base.php";

$action = get_param("action");

if (!in_array($action, ['all', 'create', 'edit', 'delete', 'get_contents', 'save_contents'])) {
	send_error("Invalid action!");
}

$activityStore = new ActivitiesCollectionStore();
$bookStore = new BooksCollectionStore();

switch ($action) {

	case 'all':
		list($unit_id) = list_all_required_params("unit_id");
		$allActivities = $activityStore->getAllByUnit($unit_id);
		json_response($allActivities);
		break;

	case 'create':
		$activityData = get_all_required_params("course_id unit_id name");
		$newActivity = $activityStore->create($activityData);
		if ($config['dynamicBookUpdate']) {
			$bookStore->addItem('activity', $newActivity);
		}
		json_response($newActivity);
		break;

	case 'edit':
		$activityData = get_all_required_params("id course_id unit_id name");
		if ($config['dynamicBookUpdate']) {
			$bookStore->updateItem('activity', $activityData);
		}
		$newActivity = $activityStore->update($activityData);
		json_response($newActivity);
		break;

	case 'delete':
		list($id) = list_all_required_params("id");
		if ($config['dynamicBookUpdate']) {
			$activity = $activityStore->get($id);
			$bookStore->deleteItem('activity', $activity);
		}
		$activityStore->delete($id);
		send_ok();
		break;

	// ----------------------------------

	case 'get_contents':
		list($activity_id) = list_all_required_params("id");
		$contents = $activityStore->getContents($activity_id);
		json_response($contents);
		break;
	
	case 'save_contents':
		$contentsData = get_all_required_params("id content");
		if ($config['dynamicBookUpdate']) {
			$activity = $activityStore->get($contentsData['id']);
			update_item($activity, $contentsData);
			$bookStore->updateItem('activity', $activity);
		}
		$contents = $activityStore->saveContents($contentsData);
		json_response($contents);
		break;

}

send_error("Missing action!");
