<?php 

$config = [
	"dynamicBookUpdate" => true
];

require_once "functions/debug.php";
require_once "functions/request.php";
require_once "functions/response.php";
require_once "functions/arrays.php";
require_once "functions/files.php";

require_once "libs/CollectionStore.php";
require_once "collections/CoursesCollectionStore.php";
require_once "collections/UnitsCollectionStore.php";
require_once "collections/ActivitiesCollectionStore.php";

require_once "collections/BooksCollectionStore.php";

require_once "generate_full_book.php";
