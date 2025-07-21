
function loadPage(param, value) {
	var params = {
		edit: editMode,
		full: fullBookData,
	};
	params[param] = value;
	var urlParams = [];
	$.each(params, function(p, v) {
		if (v) {
			urlParams.push(p + '=1');
		}
	});
	var url = window.location.pathname;
	if (urlParams.length) {
		url += '?' + urlParams.join('&');
	}
	window.location = url;
}

function renderTemplate(html, data) {
	$.each(data, function(key, value) {
		html = html.replace('${' + key + '}', value);
	});
	return html;
}

function getFormData(selector) {
	var formData = {};
	$(selector).find('.form-field').each(function() {
		var $field = $(this);
		var field = $field.data('field');
		var value = $field.val();
		formData[field] = value;
	});
	return formData;
}
