<?php 

function set_int_range($val, $min, $max) {
	$val = intval($val);
	$min = intval($min);
	$max = intval($max);
	if ($val < $min) $val = $min;
	if ($val > $max) $val = $max;
	return $val;
}

function set_float_range($val, $min, $max) {
	$val = floatval($val);
	$min = floatval($min);
	$max = floatval($max);
	if ($val < $min) $val = $min;
	if ($val > $max) $val = $max;
	return $val;
}

function get_rand($max, $reduction_percentage) {
	$max = max($max, 1);
	if ($reduction_percentage == 0) {
		return $max;
	}
	$reduction = $max * $reduction_percentage;
	$min = max($max - $reduction, 1);
	return rand($min, $max);
}

function generate_word() {
	$dictionary = ["ad", "adipisicing", "aliqua", "aliquip", "amet", "anim", "aute", "cillum", "commodo", "consectetur", "consequat", "culpa", "cupidatat", "deserunt", "do", "dolor", "dolore", "duis", "ea", "eiusmod", "elit", "enim", "esse", "est", "et", "eu", "ex", "excepteur", "exercitation", "fugiat", "id", "in", "incididunt", "ipsum", "irure", "labore", "laboris", "laborum", "lorem", "magna", "minim", "mollit", "nisi", "non", "nostrud", "nulla", "occaecat", "officia", "pariatur", "proident", "qui", "quis", "reprehenderit", "sint", "sit", "sunt", "tempor", "ullamco", "ut", "velit", "veniam", "voluptate"];
	return $dictionary[rand(0, count($dictionary) - 1)];
}

function generate_phrase($num_words, $randomize) {
	$phrase = "";
	$words = get_rand($num_words, $randomize);
	for ($w = 0; $w < $words; $w++) {
		$word = generate_word();
		if ($w == 0) {
			$word = ucfirst($word);
		}
		$phrase .= $word . " ";
	}
	$phrase = trim($phrase);
	return $phrase;
}

function generate_content($num_titles, $num_paragraphs, $num_words, $randomize = 0) {
	// Settings
	$num_titles = set_int_range($num_titles, 0, 5);
	$num_paragraphs = set_int_range($num_paragraphs, 1, 10);
	$num_words = set_int_range($num_words, 10, 100);
	$randomize = set_float_range($randomize, 0, 0.75);
	// Generate
	$text = "";
	$titles = get_rand($num_titles, $randomize);
	for ($t = 0; $t < $titles; $t++) { 
		$title = generate_phrase(5, $randomize);
		$text .= "<h1>$title</h1>\n\n";
		$paragraphs = get_rand($num_paragraphs, $randomize);
		for ($p = 0; $p < $paragraphs; $p++) { 
			$paragraph = generate_phrase($num_words, $randomize);
			$text .= "<p>$paragraph.</p>\n\n";
		}
	}
	return $text;
}

function generate_full_book($num_units, $num_activities, $randomize = 0) {
	global $config;

	// Settings
	$num_units = set_int_range($num_units, 1, 100);
	$num_activities = set_int_range($num_activities, 1, 100);
	$randomize = set_float_range($randomize, 0, 0.75);
	$themes = ["green-book", "blue-book", "white-book", "red-book", "yellow-book"];

	// Init Stores
	$courseStore = new CoursesCollectionStore();
	$unitStore = new UnitsCollectionStore();
	$activityStore = new ActivitiesCollectionStore();

	// Create Course
	$course = $courseStore->create([
		"name" => "Full Book $num_units", 
		"theme" => $themes[rand(0, count($themes) - 1)]
	]);
	$course_id = $course['id'];

	// Create Units
	$units = get_rand($num_units, $randomize);
	for ($unitIndex = 1; $unitIndex <= $units; $unitIndex++) { 
		$unit = $unitStore->create([
			"course_id" => $course_id,
			"name" => "Unit $unitIndex"
		], false);
		$unit_id = $unit['id'];
		// Create Activities
		$activities = get_rand($num_activities, $randomize);
		for ($activityIndex = 1; $activityIndex <= $activities; $activityIndex++) { 
			$activity = $activityStore->create([
				"course_id" => $course_id,
				"unit_id" => $unit_id,
				"name" => "Activity $unitIndex.$activityIndex"
			], false);
			$activity_id = $activity['id'];

			// Create Activity Contents
			$content = generate_content(3, 5, 50, $randomize);
			$activityStore->saveContents([
				"id" => $activity_id,
				"content" => $content
			]);
		}
	}

	// Save Units and Activities
	$unitStore->save();
	$activityStore->save();

	if ($config['dynamicBookUpdate']) {
		$bookStore = new BooksCollectionStore();
		$bookStore->regenerate($course_id);
	}

	return $course;
}
