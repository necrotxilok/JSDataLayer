
(function() {
	"use strict";

	var addBookFormTpl = $('#addBookFormTpl').html();
	var editBookFormTpl = $('#editBookFormTpl').html();
	var deleteBookFormTpl = $('#deleteBookFormTpl').html();

	// ---------------------------------------------------------------

	function afterSaveBook() {
		$loading.hide();
		modal.close();
		app.render();
	}

	// ---------------------------------------------------------------

	function onAddBook() {
		modal.open({
			title: 'New Book',
			ok: 'Add',
			content: renderTemplate(addBookFormTpl),
		});
	}

	function onEditBook() {
		var course = app.course;
		if (!course) {
			console.error('Curso no encontrado.');
			return;
		}	
		modal.open({
			title: 'Edit Book',
			ok: 'Save',
			content: renderTemplate(editBookFormTpl, app.course),
		});
		$modal.find('#bookThemeSelector').val(app.course.theme);
	}

	function onDeleteBook() {
		var course = app.course;
		if (!course) {
			console.error('Curso no encontrado.');
			return;
		}	
		modal.open({
			dialogClass: 'alert',
			title: 'Delete Book',
			ok: 'Delete',
			content: renderTemplate(deleteBookFormTpl, app.course),
			hide: ['.close-dialog']
		});
	}

	// ---------------------------------------------------------------

	function onAddBookForm(e) {
		e.preventDefault();
		var $form = $(this);
		var bookData = getFormData($form);
		console.log('Adding New Book...');
		$loading.show();
		app.courses.create(bookData, function() {
			console.log('Book Created!');
			afterSaveBook();
		});
	}

	function onEditBookForm(e) {
		e.preventDefault();
		var $form = $(this);
		var bookData = getFormData($form);
		console.log('Editing Book...');
		$loading.show();
		app.courses.edit(bookData, function() {
			console.log('Book Edited!');
			afterSaveBook();
			app.renderCourse();
		});
	}

	function onDeleteBookForm(e) {
		e.preventDefault();
		var $form = $(this);
		var bookData = getFormData($form);
		console.log('Deleting Book...');
		$loading.show();
		app.courses.delete(bookData, function() {
			console.log('Book Deleted!');
			afterSaveBook();
			app.closeCourse();
		});
	}

	// ---------------------------------------------------------------

	class Edit {

		constructor() {
			// Attach Course Edit Button Events
			$app.on('click', '#addBookButton', onAddBook);
			$app.on('click', '#editBookButton', onEditBook);
			$app.on('click', '#deleteBookButton', onDeleteBook);
			// Attach Course Edit Form Events
			$modal.on('submit', '#addBookForm', onAddBookForm);
			$modal.on('submit', '#editBookForm', onEditBookForm);
			$modal.on('submit', '#deleteBookForm', onDeleteBookForm);
		}
	}

	window.edit = new Edit();

})();