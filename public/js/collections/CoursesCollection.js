
(function() {
	"use strict";

	/**
	 * BooksCollection
	 *
	 * This collection use only one layer (Courses) to manage courses.
	 * Each layer will be loaded with its own API on demand.
	 *
	 */

	class CoursesCollection extends ApiCollection {
		constructor() {
			super({
				api: 'api/courses.php?action={action}',
				params: {}
			});
		}
		loadCourse(course_id, callback) {
			var course = this.get(course_id);
			if (!course) {
				return;
			}
			if (!course.units) {
				course.units = new UnitsCollection(course_id);
			}
			course.units.load(callback);
		}
		generateFullBook(settings, callback) {
			var params = {};
			for (var key in settings) {
				var value = settings[key];
				if (value) {
					params[key] = value;
				}
			}
			var _self = this;
			this.getData({
				action: 'generate_full_book', 
				...params
			}, function(course) {
				callback && callback(course);
			});
		}
	}

	window.CoursesCollection = CoursesCollection;

})();
