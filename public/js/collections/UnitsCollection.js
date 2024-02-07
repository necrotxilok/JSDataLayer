
(function() {
	"use strict";

	class UnitsCollection extends RestCollection {
		course_id = null;
		constructor(course_id) {
			super({
				api: 'api/units.php?action={action}&course_id={course_id}',
				params: { course_id }
			});
			this.course_id = course_id;
		}
		loadUnit(unit_id, callback) {
			var unit = this.get(unit_id);
			if (!unit) {
				return;
			}
			if (!unit.activities) {
				unit.activities = new ActivitiesCollection(this.course_id, unit_id);
			}
			unit.activities.load(callback);
		}
	}

	window.UnitsCollection = UnitsCollection;

})();
