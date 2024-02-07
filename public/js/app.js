
(function() {
	"use strict";

	var $app = $('#app');
	var $loading = $('#loading');

	var $booksList = $app.find('.books-list');
	var $bookPreview = $app.find('.book-preview');
	var $bookPanel = $bookPreview.find('.book-panel');
	var $activityPanel = $bookPreview.find('.activity-panel');

	var courseBookItemTpl = $('#courseBookItemTpl').html();
	var courseUnitItemTpl = $('#courseUnitItemTpl').html();
	var courseActivityItemTpl = $('#courseActivityItemTpl').html();

	// ---------------------------------------------------------------

	function onOpenCourse() {
		var $this = $(this);
		var id = $this.data('id');
		app.openCourse(id);
	}

	function closeCourse() {
		app.course = null;
		$bookPreview.addClass('closing');
		setTimeout(function() {
			$bookPreview.find('.book-panel').removeAttr('class').addClass('book-panel');
			$bookPreview.removeClass('open closing');
		}, 200);
		closeActivity();
	}

	function onOpenUnit() {
		var $this = $(this);
		var $unit = $this.parent();
		var id = $unit.data('id');
		if ($unit.hasClass('open')) {
			app.unit = null;
			$unit.removeClass('open');
			setTimeout(function() {
				$unit.find('.activities').empty();
			}, 500);
		} else {
			app.openUnit(id);
		}
	}

	function onOpenActivity() {
		var $this = $(this);
		var $activity = $this.parent();
		var id = $activity.data('id');
		app.openActivity(id);
	}

	function closeActivity() {
		app.activity = null;
		$activityPanel.removeClass('open');
		setTimeout(function() {
			$activityPanel.find('.title').text('');
			$activityPanel.find('.content').empty();
		}, 300);
	}

	function onNavigate() {
		var $this = $(this);
		var activity_id = app.activity.id;
		var current_id = null;
		var prev_id = null;
		var next_id = null;
		$bookPanel.find('.units .activity').each(function() {
			var $this = $(this);
			if (current_id) {
				if (!next_id) {
					next_id = $this.data('id');
				}
				return;
			}
			var id = $this.data('id');
			if (id == activity_id) {
				current_id = id;
			}
			if (!current_id) {
				prev_id = $this.data('id');
			}
		});
		if ($this.hasClass('nav-next') && next_id) {
			app.openActivity(next_id);
		}
		if ($this.hasClass('nav-prev') && prev_id) {
			app.openActivity(next_id);
		}
	}

	// ---------------------------------------------------------------

	class App {

		course = null;
		unit = null;
		activity = null;

		constructor() {
			console.log('Starting App...');

			if (fullBookData) {
				this.courses = new BooksCollection();
			} else {
				this.courses = new CoursesCollection();
			}
			// Load Courses
			this.courses.load(() => {
				this.render();
				$loading.hide();
			});

			// Attach App View Events
			$app.on('click', '.open-course', onOpenCourse);
			$app.on('click', '.close-course', closeCourse);
			$app.on('click', '.open-unit', onOpenUnit);
			$app.on('click', '.open-activity', onOpenActivity);
			$app.on('click', '.close-activity', closeActivity);
			$app.on('click', '.nav-button', onNavigate);

			// Attach App Mode Events
			$app.on('click', '#fullBookDataButton', function() { loadPage('full', true) });
			$app.on('click', '#layeredBookDataButton', function() { loadPage('full', false) });
			$app.on('click', '#editModeButton', function() { loadPage('edit', true) });
			$app.on('click', '#viewModeButton', function() { loadPage('edit', false) });

			console.log('App started!');
			console.log('-----------------------------------------------');
		}

		getCourse(course_id) {
			return this.courses?.get(course_id);
		}

		getUnit(unit_id) {
			return this.course?.units?.get(unit_id);
		}
		
		getActivity(activity_id) {
			return this.unit?.activities?.get(activity_id);
		}
		
		openCourse(course_id) {
			var course = this.getCourse(course_id);
			if (!course) {
				console.error('Curso ' + course_id + ' no encontrado.');
				return;
			}
			this.course = course;
			$loading.show();
			var onCourseLoaded = function() {
				//console.log('Loaded course ' + course_id, course);
				app.renderCourse();
				$loading.hide();
			};
			if (fullBookData) {
				this.courses.loadBook(course_id, onCourseLoaded);
			} else {
				this.courses.loadCourse(course_id, onCourseLoaded);
			}
		}

		openUnit(unit_id) {
			var unit = this.getUnit(unit_id);
			if (!unit) {
				console.error('Unidad ' + unit_id + ' no encontrada en el curso ' + this.course?.id + '.');
				return;
			}
			this.unit = unit;
			var $unit = $bookPreview.find('.unit[data-id="' + unit.id + '"]');
			$unit.find('.activities').text('...')
			this.course.units.loadUnit(unit_id, function() {
				//console.log('Loaded unit ' + unit_id, unit);
				app.renderUnit();
			});
		}

		openActivity(activity_id) {
			var activity = this.getActivity(activity_id);
			if (!activity) {
				console.error('Actividad ' + activity_id + ' no encontrada en la unidad ' + this.unit?.id + '.');
				return;
			}
			this.activity = activity;
			this.unit.activities.loadContents(activity_id, function(activity) {
				//console.log('Loaded activity contents ' + activity_id, activity);
				app.renderActivity();
			});
		}

		render() {
			console.log('Render Book List!');
			$booksList.empty();
			var courses = this.courses.getAll();
			console.log(courses);
			$.each(courses, function(index, course) {
				var bookItem = renderTemplate(courseBookItemTpl, course)
				$booksList.append(bookItem);
			});
		}

		renderCourse() {
			console.log('Render Course ' + this.course.id + ' > ' + this.course.name);
			$bookPreview.addClass('open');
			$bookPanel.removeAttr('class').addClass('book-panel ' + this.course.theme);
			$bookPanel.find('.title').text(this.course.name);
			$bookPanel.find('.units').empty();
			$bookPanel.find('.units').scrollTop(0);
			var units = this.course.units.getAll();
			if (units.length) {
				$.each(units, function(index, unit) {
					var unitItem = renderTemplate(courseUnitItemTpl, unit);
					$bookPanel.find('.units').append(unitItem);
				});
			} else {
				$bookPanel.find('.units').append('<div class="empty">Este libro no tiene contenidos.</div>');
			}
			if (editMode) {
				$bookPanel.find('.units').append('<div class="unit"><button class="add-unit-button">Add Unit</button></div>');
			}
		}

		closeCourse() {
			closeCourse();
		}

		renderUnit() {
			console.log('Render Unit ' + this.unit.id + ' > ' + this.unit.name);
			$bookPanel.find('.unit').removeClass('open');
			$bookPanel.find('.unit').find('.activities').empty();
			var $unit = $bookPanel.find('.unit[data-id="' + this.unit.id + '"]');
			$unit.addClass('open');
			$unit.find('.activities').empty();
			var activities = this.unit.activities.getAll();
			if (activities.length) {
				$.each(activities, function(index, activity) {
					var activityItem = renderTemplate(courseActivityItemTpl, activity);
					$unit.find('.activities').append(activityItem);
				});
			} else {
				$unit.find('.activities').append('<div class="activity empty">Esta unidad está vacía.</div>');
			}
			if (editMode) {
				$unit.find('.activities').append('<div class="activity"><button class="add-activity-button">Add Activity</button></div>');
			}
			$bookPanel.find('.units').scrollTop($bookPanel.find('.units').scrollTop() + $unit.position().top);
			setTimeout(function() {
				$bookPanel.find('.units').scrollTop($bookPanel.find('.units').scrollTop() + $unit.position().top);
			}, 50);
		}

		renderActivity() {
			console.log('Render Activity ' + this.activity.id + ' > ' + this.activity.name);
			$activityPanel.addClass('open');
			$activityPanel.find('.title').text(this.activity.name);
			var content = this.activity.content || '';
			if (!content && !editMode) {
				content = '<div class="activity empty">Esta actividad no tiene contenidos.</div>';
			}
			if (editMode) {
				content = '<textarea class="content-edit">' + content + '</textarea>';
			}
			$activityPanel.find('.content').html(content);
		}

		closeActivity() {
			closeActivity();
		}

		generateFullBook(num_units, num_activities, randomize) {
			$loading.show();
			var $coursesCollection = new CoursesCollection();
			$coursesCollection.generateFullBook({num_units, num_activities, randomize}, function(course) {
				app.courses.fill([course]);
				app.render();
				$loading.hide();
			});
		}

	}

	window.app = new App();
	window.$app = $app;
	window.$loading = $loading;

})();
