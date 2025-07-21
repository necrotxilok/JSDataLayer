(function() {
	"use strict";

	var $notifications = $('#notifications');

	// ---------------------------------------------------------------

	function openNotify(type, msg) {
		var $notify = $('<div class="notify ' + type + '">' + msg + '</div>');
		$notifications.append($notify);
		setTimeout(function() {
			$notify.remove();
		}, 5000);
		$loading.hide();
	}

	function closeAll() {
		$notifications.empty();
	}

	// ---------------------------------------------------------------

	class Notify {

		success(msg) {
			openNotify('success', msg);
		}

		error(msg) {
			openNotify('error', msg);
		}

		close() {
			closeAll();
		}

	}

	window.notify = new Notify();
	window.$notifications = $notifications;

})();
