
(function() {
	"use strict";

	var onSuccess = function(response, xhr, success, fail) {
		var contentType = xhr.getResponseHeader('Content-Type');
		if (!contentType.includes('json')) {
			var error = 'ERROR: Invalid response format.';
			console.log(error);
			fail && fail(error);
			return;
		}
		success && success(response);
	}

	var onError = function(xhr, fail) {
		var error = 'Unexpected error from server.';
		var contentType = xhr.getResponseHeader('Content-Type');
		if (contentType.includes('json')) {
			try {
				var response = JSON.parse(xhr.responseText);
				error = response.msg;
			} catch (e) {}
		}
		error = 'ERROR: ' + error;
		console.log(error);
		fail && fail(error);
	}

	var getData = function(url, success, fail) {
		$.get(url, function(response, status, xhr) {
			onSuccess(response, xhr, success, fail);
		}).fail(function(xhr) {
			onError(xhr, fail);
		});
	}

	var postData = function(url, data, success, fail) {
		$.post(url, data, function(response, status, xhr) {
			onSuccess(response, xhr, success, fail);
		}).fail(function(xhr) {
			onError(xhr, fail);
		});
	}

	var getUrl = function(base, params) {
		var url = base;
		if (params) {
			for (var param in params) {
				var token = '{' + param + '}';
				var value = params[param];
				if (url.includes(token)) {
					url = url.replace(token, value);
				} else {
					url += '&' + param + '=' + value;
				}
			}
		}
		return url;
	}

	class ApiCollection {

		type = 'api_collection';
		pk = 'id';
		api = null;
		params = {};
		collection = null;
		sort = null;
		loaded = false;

		constructor(settings) {
			if (settings) {
				if (settings.pk) this.pk = settings.pk;
				if (settings.api) this.api = settings.api;
				if (settings.params) this.params = settings.params;
				if (settings.sort) this.sort = settings.sort;
			}
			this.collection = new Collection({
				pk: this.pk,
				sort: this.sort,
			});
		}

		// Collection Methods

		get(id) {
			return this.collection.get(id);
		}

		getAll() {
			return this.collection.getAll();
		}

		fill(items, reset = false) {
			this.loaded = true;
			if (reset) {
				this.collection.reset();
			}
			this.collection.addItems(items);
		}

		// General API Methods

		getData(params, callback) {
			var url = getUrl(this.api, {...this.params, ...params});
			getData(url, function(response) {
				callback && callback(response);
			}, function(error) {
				//callback && callback({error});
				notify && notify.error(error);
			});
		}

		postData(params, data, callback) {
			var url = getUrl(this.api, {...this.params, ...params});
			postData(url, data, function(response) {
				callback && callback(response);
			}, function(error) {
				//callback && callback({error});
				notify && notify.error(error);
			});
		}

		// Basic API Methods

		load(callback) {
			if (this.loaded) {
				callback && callback(this.collection);
				return;
			}
			var _self = this;
			this.getData({action: 'all'}, function(items) {
				_self.loaded = true;
				_self.collection.addItems(items);
				callback && callback(_self.collection);
			});
		}

		refresh(callback) {
			this.loaded = false;
			this.collection.reset();
			this.load(callback);
		}

		create(data, callback) {
			var _self = this;
			this.postData({action: 'create'}, data, function(item) {
				_self.collection.add(item);
				callback && callback(item);
			});
		}

		edit(data, callback) {
			var _self = this;
			this.postData({action: 'edit'}, data, function(item) {
				_self.collection.save(item);
				callback && callback(item);
			});
		}

		delete(data, callback) {
			var _self = this;
			var id = data[this.pk];
			this.postData({action: 'delete'}, {id}, function() {
				_self.collection.delete(id);
				callback && callback();
			});
		}

	}

	window.ApiCollection = ApiCollection;

})();
