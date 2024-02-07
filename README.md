# JS Data Layer

A simple book library to apply different strategies to manage data 
between front-end and back-end.

![logo](https://github.com/necrotxilok/jsdatalayer/assets/5145866/58f1629c-e3db-4b07-b11c-638ac8e08d6b)

## Setup

Configure project folder into any Web Server with PHP like Apache 
or Nginx.

If you don't have a web server you can start the Built-in Web Server 
with next command (but is not recomended):

```cmd
php -S localhost:8000
```

## Sample Books

If you want to start the project with some simple books created you 
can copy contents from `api/sample-data/` to `api/data`.

## Usage

The application use a JS Class called RestCollection which allows to
configure any API in back-end to load and manage data with simple 
methods in the front-end.

Any loaded data will be cached in memory using a secondary JS Class
called Collection so data will be loaded from server only once.

Books are composed by 3 collections:

- **CourseCollection**: Basic information of the book.
- **UnitCollection**: The units of the book.
- **ActivitiesCollection**: The activities in each unit from the book.

This collections manage all operations with all elments of the book.

**Layered Book Data**

This is the default loading behaviour.

When this behaviour is activated, any data from any collection in the 
book will be loaded from server in layers. This means, each content
will be fetched from server only when is open the first time.

If you open a book then the units will be loaded. If you open an unit 
then the activities on that unit will be loaded. And finally when 
you open an activity the content will be loaded.

**Full Book Data**

In this behaviour a new collection will be activated to manage all
book data (BookCollection) replacing (CourseCollection).

Then, when you open a book, all the contents of the book will be 
loaded in memory using a single API and storing the hierarchy 
data into its respective collections (UnitsCollection and
ActivitiesCollection).

In the back-end a new data structure will be stored in json format
with all book information. 

This will act as a copy of the real book stored in database to 
perform a fast read and a non-destructive update of the book when 
modifying any part of the book.

If the full book is removed will be regenerated on read but it
will have more cost to the server.

**Edit Mode**

In Edit Mode, when the user creates, modifies or deletes any part
of the book (courses, units or activities), the app use RestCollection 
settings to automatically send operation to the server and retrive 
only new information.

Thanks to RestCollection the app have a permanent and consistent 
data between front-end and back-end without need of reload to
ensure the app is updated.

## App properties 

The app is accesible from console using the global variable `app`.

Some of the main properties are:

```javascipt
// CourseCollection|BookCollection
app.courses

// Current Course Data (If loaded)
app.course

// UnitCollection
app.course.units

// Current Unit Data (If loaded)
app.unit

// ActivitiesCollection
app.unit.activities

// Current Activity Data (If loaded)
app.activity
```

## App methods

Any operation in the front can be executed from console using this
methods:

```javascipt
// Open
app.openCourse(course_id);
app.openUnit(unit_id);
app.openActivity(activity_id);

// Close
app.closeCourse();
app.closeActivity();

// Get Loaded Data
app.getCourse(course_id);
app.getUnit(unit_id);
app.getActivity(activity_id);

// Render
app.render();
app.renderCourse();
app.renderUnit();
app.renderActivity();
```

## Book generator

To test all the performance, the application has a method allowing to 
create a new book of any number of units and activities with some 
randomness. 

For example:

```javascipt
// To Create a Full Book with 10 units and 5 activities per unit:
app.generateFullBook(10, 5);

// To Create a Full Book with up to 10 units and up to 5 activities
// per unit in a randomized reduction percentage of 0.5:
app.generateFullBook(10, 5, 0.5);
``` 





