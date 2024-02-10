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

![imagen](https://github.com/necrotxilok/jsdatalayer/assets/5145866/f2faf08d-a94f-413c-b6f2-206c7a132556)


## Usage

The application use a JS class called **ApiCollection** which allows to
configure any API in back-end to load and manage data with simple 
methods in the front-end.

Any loaded data will be cached in memory using a secondary JS class
called **Collection** so data will be loaded from server only once.

The JS **Collection** class will indexate all objects by **ID** making 
easy and fast getting data from the collection or interact with 
any operation like adding new items, update or delete them.

Books are composed by 3 collections:

- **CoursesCollection**: Basic information of the book.
- **UnitsCollection**: The units of the book.
- **ActivitiesCollection**: The activities in each unit from the book.

This collections manage all operations with all elments of the book.

### Layered Book Data

This is the default loading behaviour.

When this behaviour is activated, any data from any collection in the 
book will be loaded from server in layers. This means, each content
will be fetched from server only when is open the first time.

If you open a book then the units will be loaded. If you open an unit 
then the activities on that unit will be loaded. And finally when 
you open an activity the content will be loaded.

### Full Book Data

In this behaviour a new collection will be activated to manage all
book data (BooksCollection) replacing (CoursesCollection).

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

### Edit Mode

In Edit Mode, when the user creates, modifies or deletes any part
of the book (courses, units or activities), the app use ApiCollection 
settings to automatically send operation to the server and retrive 
only new information.

Thanks to ApiCollection the app have a permanent and consistent 
data between front-end and back-end without need of reload to
ensure the app is updated.


## Collection Class

Here are the main properties and methods of the Collection Class:

| Property | Description |
| - | - |
| **type** | Type of the class. |
| **pk** | Primary Key of the items contained in the collection. |
| **sort** | (Optional) Method to keep collection data sorted. |
| **data** | Object where the items are stored indexed by **pk** |
| **values** | The final sorted list of items. |
| **sorted** | Flag to regenerate the sorted list of items. |

| Method | Description |
| - | - |
| **constructor** | Create new collection with the given settings.  |
| **get** | Get an item of the collection by **pk**. |
| **getAll** | Get all items in the collection sorted. |
| **add** | Add a single item to the collection. |
| **addItems** | Add a list of items to the collection. |
| **save** | Update the item with same **pk** in the collection. |
| **delete** | Delete the item with the given **pk**. |
| **reset** | Reset collection data to an empty collection. |


## ApiCollection Class

Here are the main properties and methods of the Collection Class:

| Property | Description |
| - | - |
| **type** | Type of the class. |
| **pk** | Primary Key of the items contained in the collection. |
| **api** | String with the path to the server API. Can contain tokens with curly brackets like `{id}` and they will be automatically replaced by the given params. |
| **params** | Default params to send to the server API in all requests. |
| **collection** | Stores the data with a Collection Class. |
| **sort** | (Optional) Method to keep the collection data sorted. |
| **loaded** | True after the collection is fully loaded with the API data. It used to avoid a new API call to server. |

| Method | Description |
| - | - |
| **constructor** | Create new rest collection with the given settings.  |
| **get** | Get an item of the collection by **pk**. |
| **getAll** | Get all items in the collection sorted. |
| **fill** | Fill the collection with a list of items without fetching data from server. |
| **getData** | Get data from server using GET and launch callback on success. |
| **postData** | Send data to server using POST and launch callback on success. |
| **load** | Get data from server using the action param `all` and store the results in the collection. |
| **refresh** | Reset the collection and reload data from server. |
| **create** | Sends a new item to the server using the action param `create` and adds the new item to the collection if success. |
| **edit** | Sends a modified item with a known **pk** to the server using the action param `edit` and updates the item in the collection if success. |
| **delete** | Sends the **pk** value of an item to the server using the action param `delete` and deletes the item in the collection if success. |


## App Properties 

The app is accesible from console using the global variable `app`.

Some of the main properties are:

| Property | Description |
| - | - |
| **app.courses** | CoursesCollection|BooksCollection. |
| **app.course** | Current open course data if loaded. |
| **app.course.units** | UnitsCollection. |
| **app.unit** | Current open unit data if loaded. |
| **app.unit.activities** | ActivitiesCollection. |
| **app.activity** | Current open activity data if loaded. |


## App Methods

Any operation in the front can be executed from console using this
methods:

### Open

| Method | Description |
| - | - |
| **app.openCourse(course_id)** | Load data from server (first time) and render course in the app. |
| **app.openUnit(unit_id)** | Load data from server (first time) and render the unit in the course. |
| **app.openActivity(activity_id)** | Load data from server (first time) and render the content of activity in a page. |

### Close

| Method | Description |
| - | - |
| **app.closeCourse()** | Close current Course and the Activity Page if open. |
| **app.closeActivity()** | Close current Activity page if open. |

### Get Loaded Data

| Method | Description |
| - | - |
| **app.getCourse(course_id)** | Get data from the given course id if loaded. |
| **app.getUnit(unit_id)** | Get data from the given unit id if loaded. |
| **app.getActivity(activity_id)** | Get data from the given activity id if loaded. |


## Book Generator

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





