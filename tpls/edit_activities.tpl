
<!-- Edit Activities Templates -->

<template id="addActivityFormTpl">
	<form id="addActivityForm">
		<label>Activity Name</label>
		<input type="text" class="form-field" data-field="name" value="New Activity" required="required">
		<input type="submit" class="submit">
	</form>
</template>

<template id="editActivityFormTpl">
	<form id="editActivityForm">
		<label>Activity Name</label>
		<input type="text" class="form-field" data-field="name" value="${name}" required="required">
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>

<template id="deleteActivityFormTpl">
	<form id="deleteActivityForm">
		<label>Are you sure you want to delete "${name}" activity?</label>
		<p><small>This operation cannot be undone.</small></p>
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>
