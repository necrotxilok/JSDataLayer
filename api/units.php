<?php

require_once "core/base.php";

$action = get_param("action");

if (!in_array($action, ['all', 'create', 'edit', 'delete'])) {
	send_error("Invalid action!");
}

$unitStore = new UnitsCollectionStore();
$bookStore = new BooksCollectionStore();

switch ($action) {

	case 'all':
		list($course_id) = list_all_required_params("course_id");
		$allUnits = $unitStore->getAllByCourse($course_id);
		json_response($allUnits);
		break;

	case 'create':
		$unitData = get_all_required_params("course_id name");
		$newUnit = $unitStore->create($unitData);
		if ($config['dynamicBookUpdate']) {
			$bookStore->addItem('unit', $newUnit);
		}
		json_response($newUnit);
		break;

	case 'edit':
		$unitData = get_all_required_params("id course_id name");
		if ($config['dynamicBookUpdate']) {
			$bookStore->updateItem('unit', $unitData);
		}
		$newUnit = $unitStore->update($unitData);
		json_response($newUnit);
		break;

	case 'delete':
		list($id) = list_all_required_params("id");
		if ($config['dynamicBookUpdate']) {
			$unit = $unitStore->get($id);
			$bookStore->deleteItem('unit', $unit);
		}
		$unitStore->delete($id);
		send_ok();
		break;
	
}

send_error("Missing action!");
