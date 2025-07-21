
(function() {
	"use strict";

	class Collection {

		type = 'collection';
		pk = 'id';
		sort = null;
		data = {};

		values = [];
		sorted = false;

		constructor(settings) {
			if (settings) {
				if (settings.pk) this.pk = settings.pk;
				if (settings.sort) this.sort = settings.sort;
			}
		}

		get(id) {
			return this.data[id] || null;
		}

		getAll() {
			if (!this.sorted) {
				this.values = Object.values(this.data);
				if (typeof this.sort == 'function') {
					this.values.sort(this.sort);
				}
				this.sorted = true;
			}
			return this.values;
		}

		add(item) {
			if (!item) {
				return;
			}
			var id = item[this.pk];
			this.data[id] = Object.assign(this.data[id] || {}, item);
			this.sorted = false;
		}

		addItems(items) {
			if (!items || !Object.values(items).length) {
				return;
			}
			for (var item of Object.values(items)) {
				this.add(item);
			}
		}

		save(item) {
			if (!item) {
				return;
			}
			var id = item[this.pk];
			this.data[id] = Object.assign(this.data[id] || {}, item);
			this.sorted = false;
		}

		delete(id) {
			if (!id) {
				return;
			}
			delete this.data[id];
			this.sorted = false;
		}

		reset() {
			this.data = {};
			this.sorted = false;
		}

	}

	window.Collection = Collection;

})();
