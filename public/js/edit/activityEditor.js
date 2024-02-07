
(function() {
	"use strict";

	var addActivityFormTpl = $('#addActivityFormTpl').html();
	var editActivityFormTpl = $('#editActivityFormTpl').html();
	var deleteActivityFormTpl = $('#deleteActivityFormTpl').html();

	// ---------------------------------------------------------------

	function afterSaveActivity() {
		$loading.hide();
		modal.close();
		app.renderUnit();
	}

	// ---------------------------------------------------------------

	function onAddActivity() {
		modal.open({
			title: 'New Activity',
			ok: 'Add',
			content: renderTemplate(addActivityFormTpl),
		});		
	}

	function onEditActivity() {
		var $this = $(this);
		var $activity = $this.closest('.activity');
		var activity_id = $activity.data('id');
		var activity = app.getActivity(activity_id);
		if (!activity) {
			console.error('Actividad ' + activity_id + ' no encontrada en la unidad ' + app.unit?.id + '.');
			return;
		}
		modal.open({
			title: 'Edit Activity',
			ok: 'Save',
			content: renderTemplate(editActivityFormTpl, activity),
		});
	}

	function onDeleteActivity() {
		var $this = $(this);
		var $activity = $this.closest('.activity');
		var activity_id = $activity.data('id');
		var activity = app.getActivity(activity_id);
		if (!activity) {
			console.error('Actividad ' + activity_id + ' no encontrada en la unidad ' + app.unit?.id + '.');
			return;
		}
		modal.open({
			dialogClass: 'alert',
			title: 'Delete Activity',
			ok: 'Delete',
			content: renderTemplate(deleteActivityFormTpl, activity),
			hide: ['.close-dialog']
		});
	}

	// ---------------------------------------------------------------


	function onAddActivityForm(e) {
		e.preventDefault();
		var $form = $(this);
		var activityData = getFormData($form);
		console.log('Adding New Activity...');
		$loading.show();
		app.unit.activities.create(activityData, function() {
			console.log('Activity Created!');
			afterSaveActivity();
		});
	}

	function onEditActivityForm(e) {
		e.preventDefault();
		var $form = $(this);
		var activityData = getFormData($form);
		console.log('Editing Activity...');
		$loading.show();
		app.unit.activities.edit(activityData, function() {
			console.log('Activity Edited!');
			afterSaveActivity();
		});
	}

	function onDeleteActivityForm(e) {
		e.preventDefault();
		var $form = $(this);
		var activityData = getFormData($form);
		console.log('Deleting Activity...');
		$loading.show();
		app.unit.activities.delete(activityData, function() {
			console.log('Activity Deleted!');
			afterSaveActivity();
		});
	}

	// ---------------------------------------------------------------

	function onSaveActivityContent(e) {
		var id = app.activity.id;
		var content = $app.find('.activity-panel .content-edit').val();
		console.log('Saving Activity Content...');
		app.unit.activities.saveContents({id, content}, function() {
			console.log('Activity Content Saved!');
			afterSaveActivity();
			app.closeActivity();
		});
	}

	// ---------------------------------------------------------------

	class Edit {

		constructor() {
			// Attach Activity Edit Button Events
			$app.on('click', '.add-activity-button', onAddActivity);
			$app.on('click', '.edit-activity-button', onEditActivity);
			$app.on('click', '.delete-activity-button', onDeleteActivity);
			// Attach Activity Edit Form Events
			$modal.on('submit', '#addActivityForm', onAddActivityForm);
			$modal.on('submit', '#editActivityForm', onEditActivityForm);
			$modal.on('submit', '#deleteActivityForm', onDeleteActivityForm);
			// Attcah Activity Content Edit Events
			$app.on('click', '.save-activity-content', onSaveActivityContent);
		}
	}

	window.edit = new Edit();

})();