<?php

require_once "core/base.php";

$action = get_param("action");

if (!in_array($action, ['all', 'full', 'regenerate', 'create', 'edit', 'delete'])) {
	send_error("Invalid action!");
}

$bookStore = new BooksCollectionStore();
if (in_array($action, ['create', 'edit', 'delete'])) {
	$courseStore = new CoursesCollectionStore();
}

switch ($action) {

	case 'all':
		$courses = $bookStore->getAll();
		json_response($courses);
		break;

	case 'full':
		list($id) = list_all_required_params("id");
		//$book = $bookStore->getFullBook($id);
		//json_response($book);
		$jsonBook = $bookStore->getRawFullBook($id);
		json_raw_response($jsonBook);
		break;

	case 'regenerate':
		list($id) = list_all_required_params("id");
		$bookStore->regenerate($id);
		send_ok();
		break;

	case 'create':
		$bookData = get_all_required_params("name theme");
		$newBook = $courseStore->create($bookData);
		if ($config['dynamicBookUpdate']) {
			$bookStore->create($newBook);
		}
		json_response($newBook);
		break;

	case 'edit':
		$bookData = get_all_required_params("id name theme");
		if ($config['dynamicBookUpdate']) {
			$bookStore->update($bookData);
		}
		$newBook = $courseStore->update($bookData);
		json_response($newBook);
		break;

	case 'delete':
		list($id) = list_all_required_params("id");
		if ($config['dynamicBookUpdate']) {
			$bookStore->delete($id);
		}
		$courseStore->delete($id);
		send_ok();
		break;

}

send_error("Missing action!");
