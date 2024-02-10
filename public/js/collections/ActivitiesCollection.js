
(function() {
	"use strict";

	class ActivitiesCollection extends ApiCollection {
		course_id = null;
		unit_id = null;
		loadedContents = {};
		constructor(course_id, unit_id) {
			super({
				api: 'api/activities.php?action={action}&course_id={course_id}&unit_id={unit_id}',
				params: { course_id, unit_id }
			});
			this.course_id = course_id;
			this.unit_id = unit_id;
		}
		loadContents(activity_id, callback) {
			var activity = this.get(activity_id);
			if (!activity) {
				return;
			}
			if (this.loadedContents[activity_id]) {
				callback && callback(activity);
				return;
			}			
			var _self = this;
			this.getData({action: 'get_contents', id: activity_id}, function(contents) {
				_self.loadedContents[activity_id] = true;
				Object.assign(activity, contents);
				callback && callback(activity);
			});
		}
		saveContents(contents, callback) {
			var activity = this.get(contents.id);
			if (!activity) {
				return;
			}
			this.postData({action: 'save_contents'}, contents, function(contents) {
				Object.assign(activity, contents);
				callback && callback(activity);
			});
		}
	}

	window.ActivitiesCollection = ActivitiesCollection;

})();
