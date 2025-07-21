<?php

require_once "core/base.php";

$action = get_param("action");

if (!in_array($action, ['all', 'create', 'edit', 'delete', 'generate_full_book'])) {
	send_error("Invalid action!");
}

$courseStore = new CoursesCollectionStore();
$bookStore = new BooksCollectionStore();

switch ($action) {

	case 'all':
		$allCourses = $courseStore->getAll();
		json_response($allCourses);
		break;

	case 'create':
		$courseData = get_all_required_params("name theme");
		$newCourse = $courseStore->create($courseData);
		if ($config['dynamicBookUpdate']) {
			$bookStore->create($newCourse);
		}
		json_response($newCourse);
		break;

	case 'edit':
		$courseData = get_all_required_params("id name theme");
		if ($config['dynamicBookUpdate']) {
			$bookStore->update($courseData);
		}
		$newCourse = $courseStore->update($courseData);
		json_response($newCourse);
		break;

	case 'delete':
		list($id) = list_all_required_params("id");
		if ($config['dynamicBookUpdate']) {
			$bookStore->delete($id);
		}
		$courseStore->delete($id);
		send_ok();
		break;

	case 'generate_full_book':
		list($num_units, $num_activities) = list_all_required_params("num_units num_activities");
		$randomize = get_param('randomize', 0);
		$newCourse = generate_full_book($num_units, $num_activities, $randomize);
		json_response($newCourse);
		break;
	
}

send_error("Missing action!");
