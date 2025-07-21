
(function() {
	"use strict";

	var $modal = $('#modal');

	// ---------------------------------------------------------------

	function openModal(modalData) {
		$modal.find('*:not(.hidden)').show();
		$modal.find('.dialog-header h1').text(modalData.title || '');
		$modal.find('.dialog-content').html(modalData.content || '');
		$modal.find('.dialog-footer .ok').html(modalData.ok || 'Ok');
		$modal.find('.dialog-footer .cancel').html(modalData.cancel || 'Cancel');
		if (modalData.dialogClass) {
			$modal.find('.dialog').addClass(modalData.dialogClass);
		}
		if (modalData.hide) {
			$.each(modalData.hide, function(i, selector) {
				$modal.find(selector).hide();
			});
		}
		$modal.okAction = modalData.okAction || function() {
			$modal.find('.dialog-content form .submit').click();
		};
		$modal.cancelAction = modalData.cancelAction || null;
		$modal.addClass('open');
		$modal.find('form input').first().focus();
	}

	function closeModal() {
		$modal.addClass('closing');
		setTimeout(function() {
			$modal.find('.dialog').removeAttr('class').addClass('dialog');
			$modal.find('.dialog-header h1').text('');
			$modal.find('.dialog-content').html('');
			$modal.find('.dialog-footer .ok').html('Ok');
			$modal.find('.dialog-footer .cancel').html('Cancel');
			$modal.find('*:not(.hidden)').show();
			$modal.removeClass('open closing');
		}, 200);
	}

	function onOkModal() {
		$modal.okAction && $modal.okAction();
	}

	function onCancelModal() {
		$modal.cancelAction && $modal.cancelAction();
		closeModal();
	}

	// ---------------------------------------------------------------

	class Modal {

		constructor() {
			// Attach Modal Events
			$modal.on('click', '.dialog-footer .ok', onOkModal);
			$modal.on('click', '.dialog-footer .cancel', onCancelModal);
			$modal.on('click', '.close-dialog', onCancelModal);
		}

		open(modalData) {
			openModal(modalData);
		}

		close() {
			closeModal();
		}

	}

	window.modal = new Modal();
	window.$modal = $modal;

})();