<?php
	$editMode = !empty($_GET['edit']);
	$fullBookData = !empty($_GET['full']);

	$editModeClass = $editMode ? 'edit' : '';
	
	$fullBookDataButtonClass = $fullBookData ? 'active' : '';
	$layeredBookDataButtonClass = !$fullBookData ? 'active' : '';

	// ICONS: https://www.iconfinder.com/iconsets/material-core
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php if ($editMode) { ?>Edit<?php } else { ?>View<?php } ?> Books - JS Data Layer</title>

	<link rel="icon" type="image/x-icon" href="favicon.ico">

	<link rel="stylesheet" type="text/css" href="public/css/general.css">
	<link rel="stylesheet" type="text/css" href="public/css/book.css">
	<link rel="stylesheet" type="text/css" href="public/css/book_panel.css">
	<link rel="stylesheet" type="text/css" href="public/css/activity_panel.css">
	<link rel="stylesheet" type="text/css" href="public/css/book_themes.css">
</head>
<body>

	<div id="app">
		<div class="actions">
			<div class="group-button">
				<div id="fullBookDataButton" class="<?=$fullBookDataButtonClass?>" title="Full Book Data"><?=file_get_contents("public/icons/full.svg")?></div>
				<div id="layeredBookDataButton" class="<?=$layeredBookDataButtonClass?>" title="Layered Book Data"><?=file_get_contents("public/icons/layered.svg")?></div>
			</div>
			<?php if (!$editMode) { ?>
				<div id="editModeButton" class="circle-button" title="Edit Mode"><?=file_get_contents("public/icons/edit.svg")?></div>
			<?php } else { ?>
				<div id="viewModeButton" class="circle-button" title="View Mode"><?=file_get_contents("public/icons/view.svg")?></div>
			<?php } ?>
		</div>
		<h1><?php if ($editMode) { ?>Edit Books <span id="addBookButton" class="circle-button" title="Add New Book">+</span><?php } else { ?>View Books<?php } ?></h1>
		<div class="books-list"></div>
		<div class="book-preview">
			<div class="book-panel">
				<?php if ($editMode) { ?>
					<div class="book-actions">
						<div id="editBookButton" class="circle-button"><?=file_get_contents("public/icons/edit.svg")?></div>
						<div id="deleteBookButton" class="circle-button"><?=file_get_contents("public/icons/delete.svg")?></div>
					</div>
				<?php } ?>
				<div class="close-course">✕</div>
				<div class="title"></div>
				<div class="units"></div>
			</div>
			<div class="activity-panel <?=$editModeClass?>">
				<div class="close-activity">✕</div>
				<div class="title"></div>
				<div class="content"></div>
				<div class="activity-actions">
					<?php /*
						<button class="left nav-button nav-prev">Previous</button>
						<button class="right nav-button nav-next">Next</button>
					*/ ?>
					<?php if ($editMode) { ?>
						<button class="save-activity-content">Save</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div id="modal">
		<div class="dialog">
			<div class="dialog-header">
				<h1>Dialog Title</h1>
				<div class="close-dialog">✕</div>
			</div>
			<div class="dialog-content"></div>
			<div class="dialog-footer">
				<button class="primary ok">Ok</button>
				<button class="cancel">Cancel</button>
			</div>
		</div>
	</div>

	<div id="notifications"></div>

	<div id="loading">
		<div class="elements">
			<span class="dot dot-1">.</span>
			<span class="dot dot-2">.</span>
			<span class="dot dot-3">.</span>
		</div>
	</div>

	<div class="templates hidden">

		<!-- View Templates -->
		<template id="courseBookItemTpl">
			<div class="course book-item open-course" data-id="${id}">
				<div class="book ${theme}">
					<div class="side back"></div>
					<div class="pages"></div>
					<div class="side cover">
						<div class="title">${name}</div>
					</div>
				</div>
			</div>
		</template>
		<template id="courseUnitItemTpl">
			<div class="unit" data-id="${id}">
				<div class="name open-unit">${name}</div>
				<?php if ($editMode) { ?>
					<div class="group-button unit-actions">
						<div class="edit-unit-button"><?=file_get_contents("public/icons/edit.svg")?></div>
						<div class="delete-unit-button"><?=file_get_contents("public/icons/delete.svg")?></div>
					</div>
				<?php } ?>
				<div class="activities"></div>
			</div>
		</template>
		<template id="courseActivityItemTpl">
			<div class="activity" data-id="${id}">
				<div class="name open-activity">${name}</div>
				<?php if ($editMode) { ?>
					<div class="group-button activity-actions">
						<div class="edit-activity-button"><?=file_get_contents("public/icons/edit.svg")?></div>
						<div class="delete-activity-button"><?=file_get_contents("public/icons/delete.svg")?></div>
					</div>
				<?php } ?>				
			</div>
		</template>

		<?php if ($editMode) { ?>
			<?php require_once "public/tpls/edit_courses.tpl"; ?>
			<?php require_once "public/tpls/edit_units.tpl"; ?>
			<?php require_once "public/tpls/edit_activities.tpl"; ?>
		<?php } ?>

	</div>

	<div class="scripts hidden">
		<!-- Config Vars -->
		<script type="text/javascript">
			const editMode = <?=$editMode?'true':'false'?>;
			const fullBookData = <?=$fullBookData?'true':'false'?>;
		</script>

		<!-- Vendor -->
		<script type="text/javascript" src="public/vendor/jquery-3.7.1.min.js"></script>

		<!-- Functions -->
		<script type="text/javascript" src="public/js/functions.js"></script>

		<!-- Collections -->
		<script type="text/javascript" src="public/js/libs/Collection.js"></script>
		<script type="text/javascript" src="public/js/libs/RestCollection.js"></script>
		<script type="text/javascript" src="public/js/collections/CoursesCollection.js"></script>
		<script type="text/javascript" src="public/js/collections/UnitsCollection.js"></script>
		<script type="text/javascript" src="public/js/collections/ActivitiesCollection.js"></script>
		<script type="text/javascript" src="public/js/collections/BooksCollection.js"></script>

		<!-- App -->
		<script type="text/javascript" src="public/js/app.js"></script>
		<script type="text/javascript" src="public/js/notify.js"></script>
		<?php if ($editMode) { ?>
			<script type="text/javascript" src="public/js/modal.js"></script>
			<script type="text/javascript" src="public/js/edit/courseEditor.js"></script>
			<script type="text/javascript" src="public/js/edit/unitEditor.js"></script>
			<script type="text/javascript" src="public/js/edit/activityEditor.js"></script>
		<?php } ?>
	</div>
</body>
</html>