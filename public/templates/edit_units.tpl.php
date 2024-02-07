
<!-- Edit Units Templates -->

<template id="addUnitFormTpl">
	<form id="addUnitForm">
		<label>Unit Name</label>
		<input type="text" class="form-field" data-field="name" value="New Unit" required="required">
		<input type="submit" class="submit">
	</form>
</template>

<template id="editUnitFormTpl">
	<form id="editUnitForm">
		<label>Unit Name</label>
		<input type="text" class="form-field" data-field="name" value="${name}" required="required">
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>

<template id="deleteUnitFormTpl">
	<form id="deleteUnitForm">
		<label>Are you sure you want to delete "${name}" unit?</label>
		<p><small>This operation cannot be undone.</small></p>
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>
