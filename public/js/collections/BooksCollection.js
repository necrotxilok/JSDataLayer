
(function() {
	"use strict";

	/**
	 * BooksCollection
	 *
	 * This collection use all layers (Courses/Units/Activities) to manage courses.
	 * Fetching a Book will retrive Units and Activities at once.
	 *
	 */

	class BooksCollection extends ApiCollection {
		loadedBooks = {};
		constructor() {
			super({
				api: 'api/books.php?action={action}',
				params: {}
			});
		}
		loadBook(id, callback) {
			var book = this.get(id);
			if (!book) {
				return;
			}
			if (this.loadedBooks[book.id]) {
				if (!book.units) {
					book.units = new UnitsCollection(book.id);
				}
				callback && callback(book);
				return;
			}			
			var _self = this;
			book.units = new UnitsCollection(book.id);
			this.getData({action: 'full', id: book.id}, function(bookData) {
				_self.loadedBooks[book.id] = true;
				book.units.fill(bookData.units);
				for (var unit of book.units.getAll()) {
					var activities = unit.activities || [];
					unit.activities = new ActivitiesCollection(book.id, unit.id);
					unit.activities.fill(activities);
					for (var activity of activities) {
						unit.activities.loadedContents[activity.id] = true;
					}
				}
				callback && callback(book);
			});
		}
		delete(data, callback) {
			var id = data[this.pk];
			this.loadedBooks[id] = false;
			super.delete(data, callback);
		}
	}

	window.BooksCollection = BooksCollection;

})();
