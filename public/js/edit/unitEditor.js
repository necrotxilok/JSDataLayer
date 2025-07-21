
(function() {
	"use strict";

	var addUnitFormTpl = $('#addUnitFormTpl').html();
	var editUnitFormTpl = $('#editUnitFormTpl').html();
	var deleteUnitFormTpl = $('#deleteUnitFormTpl').html();

	// ---------------------------------------------------------------

	function afterSaveUnit() {
		$loading.hide();
		modal.close();
		app.renderCourse();
	}

	// ---------------------------------------------------------------

	function onAddUnit() {
		modal.open({
			title: 'New Unit',
			ok: 'Add',
			content: renderTemplate(addUnitFormTpl),
		});
		/*var $this = $(this);
		$this.prop('disabled', true);
		app.course.units.create({name: 'New Unit'}, function() {
			app.renderCourse();
			$this.prop('disabled', false);
		});*/
	}

	function onEditUnit() {
		var $this = $(this);
		var $unit = $this.closest('.unit');
		var unit_id = $unit.data('id');
		var unit = app.getUnit(unit_id);
		if (!unit) {
			console.error('Unidad ' + unit_id + ' no encontrada en el curso ' + app.course?.id + '.');
			return;
		}
		modal.open({
			title: 'Edit Unit',
			ok: 'Save',
			content: renderTemplate(editUnitFormTpl, unit),
		});
	}
 
	function onDeleteUnit() {
		var $this = $(this);
		var $unit = $this.closest('.unit');
		var unit_id = $unit.data('id');
		var unit = app.getUnit(unit_id);
		if (!unit) {
			console.error('Unidad ' + unit_id + ' no encontrada en el curso ' + app.course?.id + '.');
			return;
		}	
		modal.open({
			dialogClass: 'alert',
			title: 'Delete Unit',
			ok: 'Delete',
			content: renderTemplate(deleteUnitFormTpl, unit),
			hide: ['.close-dialog']
		});
	}

	// ---------------------------------------------------------------

	function onAddUnitForm(e) {
		e.preventDefault();
		var $form = $(this);
		var unitData = getFormData($form);
		console.log('Adding New Unit...');
		$loading.show();
		app.course.units.create(unitData, function() {
			console.log('Unit Created!');
			afterSaveUnit();
		});
	}

	function onEditUnitForm(e) {
		e.preventDefault();
		var $form = $(this);
		var unitData = getFormData($form);
		console.log('Editing Unit...');
		$loading.show();
		app.course.units.edit(unitData, function() {
			console.log('Unit Edited!');
			afterSaveUnit();
		});
	}

	function onDeleteUnitForm(e) {
		e.preventDefault();
		var $form = $(this);
		var unitData = getFormData($form);
		console.log('Deleting Unit...');
		$loading.show();
		app.course.units.delete(unitData, function() {
			console.log('Unit Deleted!');
			afterSaveUnit();
		});
	}

	// ---------------------------------------------------------------

	class Edit {

		constructor() {
			// Attach Unit Edit Button Events
			$app.on('click', '.add-unit-button', onAddUnit);
			$app.on('click', '.edit-unit-button', onEditUnit);
			$app.on('click', '.delete-unit-button', onDeleteUnit);
			// Attach Unit Edit Form Events
			$modal.on('submit', '#addUnitForm', onAddUnitForm);
			$modal.on('submit', '#editUnitForm', onEditUnitForm);
			$modal.on('submit', '#deleteUnitForm', onDeleteUnitForm);
		}
	}

	window.edit = new Edit();

})();