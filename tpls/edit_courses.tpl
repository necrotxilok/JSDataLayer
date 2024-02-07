
<!-- Edit Courses Templates -->

<template id="addBookFormTpl">
	<form id="addBookForm">
		<label>Book Name</label>
		<input type="text" class="form-field" data-field="name" value="New Book" required="required">
		<label>Book Theme</label>
		<select class="form-field" data-field="theme" required="required">
			<option value="green-book">Green Book</option>
			<option value="blue-book">Blue Book</option>
			<option value="white-book">White Book</option>
			<option value="red-book">Red Book</option>
			<option value="yellow-book">Yellow Book</option>
		</select>
		<input type="submit" class="submit">
	</form>
</template>

<template id="editBookFormTpl">
	<form id="editBookForm">
		<label>Book Name</label>
		<input type="text" class="form-field" data-field="name" value="${name}" required="required">
		<label>Book Theme</label>
		<select id="bookThemeSelector" class="form-field" data-field="theme" required="required">
			<option value="green-book">Green Book</option>
			<option value="blue-book">Blue Book</option>
			<option value="white-book">White Book</option>
			<option value="red-book">Red Book</option>
			<option value="yellow-book">Yellow Book</option>
		</select>
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>

<template id="deleteBookFormTpl">
	<form id="deleteBookForm">
		<label>Are you sure you want to delete "${name}" book?</label>
		<p><small>This operation cannot be undone.</small></p>
		<input type="hidden" class="form-field" data-field="id" value="${id}">
		<input type="submit" class="submit">
	</form>
</template>
