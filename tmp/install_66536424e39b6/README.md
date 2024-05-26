# Depot

## Introduction

This project is also based on the desperation to sort and find my electronic
components. Some integrated circuits (ICs) accumulated in various boxes and
the question of whether I own that or the other IC, had to be painstakingly
researched. Above all, there were at one time several places where components
were stored or partly kept at a plant. 
The idea was born, but it took long time to implement it.
Klosterneuburg, October 2023 Thomas Kuschel KW4NZ.

## Workflow (since 0.0.1)

In git we start to make a new branch named b1_basic_backend, where we start
developing our project. The first run with simply renaming entries from a
copy of the component **com_banners** did not work as expected.
So let us start at the very beginning:

## Adding basic files for component (b1_basic_backend)

With the git branch **b1_basic_backend**
Add the following basic six files:

1. admin

	- src/Extension/DepotComponent.php: The main extension file for the component.
	- services/provider.php: It tells Joomla how to initialize or boot the component.
	- src/Controller/DisplayController.php: The default Controller for the component.
	- src/View/Parts/HtmlView.php: The Html View for the "Parts" page.
	- tmpl/parts/default.php: The layout file for the "Parts" page.
	
2. depot.xml: XML manifest file that tells Joomla! how to install the component.

#### Description of each file:

##### 1. DepotComponent.php
This file contains class for the extension. The class extends MVCComponent.
Compare this version with the original at [Tech Fry Tutorium](https://www.techfry.com/joomla/adding-basic-files-for-component).
```php
namespace KW4NZ\Component\Depot\Administrator\Extension;

use Joomla\CMS\Extension\MVCComponent;

class DepotComponent extends MVCComponent
{

}
```

##### 2. provider.php
This is a special file that tells Joomla how to initialize the component - which
services it requires and how they should be provided.

The service provider file registers dependencies the component will use.
Here, we have included two dependencies:

- **DispatcherFactory** is needed to create the Dispatcher class instance,
	and then Joomla will call dispatch() on this Dispatcher object, as the
	next step in running the component.

- **MVCFactory** is needed to create the Controller, View, Model and Table class
	instances on behalf of the component.


##### 3. DisplayController.php
This is a **default controller** for the component. It simply sets its default
view and leaves the rest to its parent.

When you view the component through URL, Joomla uses the **controller** to execute
the **task**. The task is the name of method in the controller file.
If you do not pass the controller or task in the URL, it defaults to Display
Controller and display Task.

The default view is the name of the component. So, here we need to override the
default view to `parts`.

##### 4. HtmlView.php
This file contains class HtmlView that extends BaseHtmlView. The BaseHtmlView is
the base class for a Joomla View.

The **view gets the data from the model to be output by the layout file.**

For example:
```php
$this->msg = $this->get('Msg');
```

This method converts the get('Msg') call into a getMsg() call on the model, which
is the method which you have to provide in the model.

The view file displays data using the template layout file - $tpl, which defaults
to default.php.

##### 5. default.php
This file holds the template for the page. When no specific layout is
requested for a view, Joomla will load the template in the **default.php** file.

```php
<h2>Welcome to Depot Component!</h2>
```

##### 6. depot.xml
This file tells Joomla how to install the component and what file are included.

In the administration part, we include a link to the menu and include files
and folders (services, src, tmpl and so on) which are in the parent folder
admin of the component. While installing the component, these will get copied
to the Joomla administrator/components/com_depot.

#### Installation the component
Create a **.zip** file of the com_depot directory. Then inside the Joomla
Administration upload this .zip package.

Now you should see a new link "Depot" in the "Compnents" section of the menu.
If you click it, you should see the default "Depot" page.

#### Language files
We create two language files for the system and the component **Depot** at
the directory /admin/language/en-GB/ naming it

- com_depot.ini
- com_depot.sys.ini

---

## Creating and managing Joomla database (b2_database)

With the new git branch **b2_database** we continue our workflow.

Joomla usually manage its content with a database. In our component, we use
a MariaDB database. At the time of development of this component we have PHP8.2,
and we use Mariadb database with version from 11.1,
client 15.2 for Linux (x86_64) using readline 5.1
We support MySQL and MariaDB; not tested support for PostgreSQL.

In the manifest file \<component_name\>.xml, here the file **depot.xml**,
there are installation instructions to "install", "uninstall", and 
"update" the Joomla extension/component.

The SQL plain text files are stored in and as:

- admin/sql/
	- install.mysql.utf8.sql
	- uninstall.mysql.utf8.sql

- admin/sql/updates/mysql/
	- 0.0.1.sql
	- 0.0.4.sql
	- 0.0.5.sql

#### Database table installation
1. When the component is installed for the first time, the file
**install.mysql.utf8.sql** is executed.
1. If the component is already installed, the update
scenario comes into play, the folder **admin/sql/updates/mysql/**:  Only the files with higher version
numbers than the installed version of the component will be
executed in ascending order.<br>
Hint: The version of the installed component is stored in the
Joomla's table "#__schemas".

The "#__"-prefix is substituted automatically with the database prefix, (e.g. "jm_") which is defined in the configuration file (configuration.php) of the installed Joomla version as parameter $dbprefix.

**install.mysql.utf8.sql**
```sql
DROP TABLE IF EXISTS `#__depot`;
CREATE TABLE `#__depot`(
	`id` SERIAL,
	`component_name` VARCHAR(1024) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL
		COMMENT 'unique component name (ASCII characters only)',
	`alias` VARCHAR(1024) NOT NULL DEFAULT '',
	`description` VARCHAR(4000) NOT NULL DEFAULT '',
	`quantity` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`quantity_exp` INT(11) NOT NULL DEFAULT 0
		COMMENT 'Exponent of the quantity (10^x of the number, usually 0 i.e. 10⁰)',
	`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`checked_out` INT(11) NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`path` VARCHAR(400) NOT NULL DEFAULT '',
	`state` TINYINT(4) NOT NULL DEFAULT 0
		COMMENT 'Published=1,Unpublished=0,Archived=2,Trashed=-2',
	`access` TINYINT(4) NOT NULL DEFAULT 0,
	`params` VARCHAR(1024) NOT NULL DEFAULT '',
	`image` VARCHAR(1024) NOT NULL DEFAULT '',
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`version` int unsigned NOT NULL DEFAULT 1,
	-- references to other tables:
	`category_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_id` INT(11) NOT NULL DEFAULT 0,
	`datasheet_alt` VARCHAR(1024) NOT NULL DEFAULT '',
	`manufacturer_id` INT(11) NOT NULL DEFAULT 0,
	`stock_id` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	KEY `idx_state` (`state`),
	KEY `idx_stock_id` (`stock_id`),
	KEY `idx_manufacturer` (`manufacturer_id`),
	UNIQUE KEY `aliasindex` (`alias`,`manufacturer_id`,`stock_id`)
)	ENGINE=InnoDB
	AUTO_INCREMENT=0
	DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__depot` (`component_name`,`alias`,`description`,`quantity`,`created`,
 `ordering`,`state`,`manufacturer_id`) VALUES
 ('1N5404','1n5404','diode, rectifier 3A',9,'2023-09-25 15:00:00',1,1,1),
 ('1N4148','1n4148','diode, general purpose',1234,'2023-09-25 15:15:15',2,1,2);
```
The table is created in the database when the component is installed.
Also two lines of sample data are inserted into the table "#__depot".

#### Database table uninstallation
When the component is uninstalled the
**uninstall.mysql.utf8 sql** is executed.
We drop the used tables from the database.

**install.mysql.utf8.sql**
```sql
DROP TABLE IF EXISTS `#__depot`;
```
#### Database table update
When the component is updated the
**admin/sql/updates/mysql** folder with its files is executed.

Even if you do not need a database update, you can add an empty file in admin/sql/updates/mysql/0.0.2.sql to initialize the schema version.

In future versions, if you plan to use database tables, the update can be performed automatically.
We create an empty file, just with a comment "-- version 0.0.2"
**admin/sql/updates/mysql/0.0.2.sql**

```sql
-- version 0.0.2
```

#### Manifest file for extensions
The sql files are only executed if they exist in the \<component_name\>.xml manifest file. We add this after the namespace tag, just before the files information tag:
```php
<install> <!-- Runs on install -->
	<sql>
		<file driver="mysql" charset="utf8">sql/install.mysql.utf8.sql</file>
	</sql>
</install>
<uninstall> <!-- Runs on uninstall -->
	<sql>
		<file driver="mysql" charset="utf8">sql/uninstall.mysql.utf8.sql</file>
	</sql>
</uninstall>
<update>
	<schemas>
		<schemapath type="mysql">sql/updates/mysql</schemapath>
	</schemas>
</update>
```
The **\<version\> tag** of the manifest must be updated to 0.0.2 and
also add the sql folder in the administration files section.

#### Table class
**src/table/DepotTable.php**

For each database table, you have to define a table class. The model
asks the table to get information or perform database operations.

This table class has to be defined in admin/src/Table/DepotTable.php file.

```php
<?php
namespace KW4NZ\Component\Depot\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

\defined('_JEXEC') or die;

class DepotTable extends Table
{
	function __contruct(DatabaseDriver $db)
	{
		parent::__contruct('#__depot', 'id', $db);
	}
}
```
Joomla uses the **Table Object** to get item or record, insert records,
update or delete records for the database operations.

Methods in the table objects are:

1. **load()** to load the existing record from the database, passing
			the primary key of the record.
1. **bind()** to set the new values for the fields.
1. **check()** to perform any validation.
1. **store()** to save the new values to the database.
1. **delete()** to delete the record from the database.

---

## Get a Form in Joomla component (b3_form)

The **Form** class of Joomla is used to create complex forms with flexible
layouts and dynamic properties. First, the form fields are defined in the
XML file. Then the view file gets the form from the model and layout file
displays the form.

#### XML Form file
**admin/forms/part.xml**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<form>
	<field
		name="title" 
		type="text" 
		label="JGLOBAL_TITLE" 
		required="true"
	/>
</form>
```
Similarly you can add other fields to the XML file.

#### View file
**admin/src/View/Part/HtmlView.php**

This file is similar to the view file added earlier for the "Parts" view.
The view file gets the form from the model in the display() method.
```php
<?php
namespace KW4NZ\Component\Depot\Administrator\View\Part;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

\defined('_JEDEC') or die;

class HtmlView extends BaseHtmlView
{
	protected $form;
	protected $item;

	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		parent::display($tpl);
	}
}
```
The code will look for the method **getForm()**
and **getItem()** in the model file.
We need to get the item to get the ID
of the record in case of editing the existing record.

#### Model file
**admin/src/Model/PartModel.php**

We need to extend the model class with the **AdminModel**.
The AdminModel class extends FormModel class. The **getForm()**
method gets the Form object for the edit form.

```php
namespace KW4NZ\Component\Depot\Administrator\Model;
use Joomla\CMS\MVC\Model\AdminModel;

\defined('_JEXEC') or die;

class PartModel extends AdminModel
{
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_depot.part',
			'part', array('control' => 'jform',
			'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}
}
```
The **loadForm()** and **preprocessForm()** methods are defined in
the FromModel class and the **bind()** method is defined in the
Form class. The first argument (name) of loadForm() is set to
"com_depot.part". The second argument (form xml source) is "part",
and the third argument is the associative array for options.

The form is defined in the source file: **forms/part.xml**

After you have set the $form variable with the Form object,
you check to see if you are loading data to the form.
If you want to pre-load data for the form, you include an element
called "load_data" that is set to a boolean true.
Then, the method calls the loadFormData() method to get the data
for the form. This method gets any previously loaded data from
the session or database.

###### Modifying Form dynamically

Inside the getForm() method, before returning the $form, you can modify the form with many methods of the Form class. You can easily fine-tune your forms dynamically before they are rendered.

#### Layout file - rendering Form
**tmpl/part/edit.php**

After you get the form in the view file ($this->form), the form
is rendered in the layout file (edit.php).

```php
<?php
use Joomla\CMS\HTML\HTMLHelper;

$wa = $this->document->getWebAssetManager();

$wa->useScript('keepalive');
$wa->useScript('form.validate');
?>
<form action="<?= Route::_('index.php?option=com_depot&layout=edit&id=' .
	(int) $this->item->id); ?>"
	method="post" name="adminForm" id="item-form" class="form-validate">

	<?= $this->form->renderField('title'); ?>

	<input type="hidden" name="task" value="part.edit" />
	<?= HTMLHelper::_('form.token'); ?>
</form>
```
The form validate script is required to submit the for. The renderField() method
of the Form class displays the field - both label and input.

We can access the form using following URL:
```php
administrator/index.php?option=com_depot&view=part&layout=edit
```
It displays edit.php layout file of the Part View. When the form is submitted, the
data is sent to the controller depending upon the action buttons in the toolbar.

Please remember to add the additional folder `form` to the Manifest file "depot.xml", i.e.
just a line before `<folder>services</folder>` :
```xml
<folder>form</folder>
```

---

## Adding administrator's actions (Back-end) - Save and Cancel (b4_actions)
In the form View, you can add action buttons like "Save" and "Cancel" to submit the form
or cancel the editing respectively. These buttons are added to the toolbar.

These buttons require compound tasks (controller and method). For example,

- Save and Edit: part.apply
- Save and Close: part.save
- Cancel: part.cancel

#### View file
**admin/src/View/Part/HtmlView.php**
In the View file, we create a new method to add a toolbar: `addToolbar()`. The
toolbar hides the sidebar menu on forms, sets the title and adds action buttons.
```php
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

protected function addToolbar()
{
	Factory::getApplication()->getInput()->set('hidemainmenu', true);
	ToolbarHelper::title('Part: Add');

	ToolbarHelper::apply('part.apply');
	ToolbarHelper::save('part.save');
	ToolbarHelper::cancel('part.cancel', 'JTOOLBAR_CLOSE');
}
```
The display() calls this method to include the toolbar.
```php
public function display($tpl = null) 
{
	$this->form = $this->get('Form');

	$this->addToolbar();

	parent::display($tpl);
}
```
#### Controller file
**admin/src/Controller/PartController.php***
Now, when these action buttons are clicked, Joomla will look for apply(), save() or cancel()
methods in the Part controller. So, create a controller clss that will extend FormController.
These methods are already defined in the parent class.
```php
use Joomla\CMS\MVC\Controller\FormController;

class PartController extends FormController
{
}
```
#### Model file

**admin/src/model/PartModel.php**

If you click on Save button, it will save the data and redirect to the editing screen. However, the data will not be present in the form. Though, we have set `$loadData` to true, we also need to create a method `loadFormData()` to get the data for the form.

```php
protected function loadFormData()
{
	$app  = Factory::getApplication();
	$data = $app->getUserState('com_depot.edit.part.data', []);

	if (empty($data)) {
		$data = $this->getItem();
	}

	return $data;
}
```
First, this method tries to get the data from the session. If it fails to get data from the
session, then it gets data from the database. The `getItem()` method is defined in the
parent class.

Suppose the user has filled out the form to add a new item but has some invalid data in the
form. In this case, the save will not be successful, so the data is not saved in the
database. It would be very frustrating if the user hat to reenter all the fields to fix a
single error. To handle this scenario, you save the entered data in the user's session.
Only after the save is successful, you clear this out of the session.

So, you either have data from the database, which you can get with `getItem()`, or you have
data from the session, which you can get with `getUserSate()`.

---
## Automatic handling of fields (b5_field_manipulation)
Values of some fields in the form can be automatically handled. There is no need to fill in
the values while creating or editing a record.

For example, `alias` can be generated from the `title` (or here, from the component's name),
dates can be set to the current date or to `null`, user id can be obtained from current
logged in user. Further may you also need to check validity of some fields like "should not
be empty", `alias` should be unique, etc.

The data submitted by the form needs to be modified before saving. This can be done at
various places:

- in the **Model class** by oeverriding the `save()` method or `preparetable()` method.
- in the **Table class** by overriding the `bind()`, `check()`, or `store()` methods.

#### 1. Model file
**admin/src/Model/PartModel.php
```php
public function save($data)
{
	/* Add code to modify data before saving */

	return parent:save($data);
}
```
It is better to perform automatic handling of fields in the Table class as the data can be
saved not only from administration, but also from frontend, API or by any other means.

##### Example generating Alias
Alias is generated from the `component_name` (or any other field) using **OutputFilter** class
method.
```php
if (empty($data['alias']))
{
 	if (Factory::getConfig()->get('unicodeslugs') == 1) {
		$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['component_name']);
	} else {
		$data['alias'] = OutputFilter::stringURLSafe($data['component_name']);
	}
}
```
##### Ordering
The ordering value is calculated by finding the maximum value in the column and then
incrementing it.
```php
/* if it is 0 -> get the max + 1 value */
if (!$data['ordering']) {
	$db = Factory::getDbo();
	// $query = $db->getQuery(true)	// is deprecated,
	// using createQuery() instead:
	$query = $db->createQuery()
		->select('MAX(ordering)')
		->from('#__depot');

	$db->setQuery($query);
	$max = $db->loadResult();

	$data['ordering'] = $max + 1;
}

```
#### 2. bind()
The `bind()` splits the article text or description into intro text and full text based on read
more tag in the content. This method also converts fields data from arrays to JSON for
saving into the database.

```php
public function bind($array, $ignore = '')
{
	if (isset($array['attribs']) && \is_array($array['attribs'])) {
		$registry = new Registry($array['attribs']);
		$array['attribs'] = (string) $registry;
	}

	return parent::bind($array, $ignore);
}

```

#### 3. check()
The check() checks whether title of the article is not empty. This method also sets the alias,
hits, publishing dates.
```php
public function check()
{
	try {
		parent::check();
	}
	catch (\Exception $e) {
		$this->setError($e->getMessage());

		return false;
	}

	if (trim($this->title) == '') {
		$this->setError('Title (title) is not set.');

		return false;
	}

	if (trim($this->alias) == '') {
		$this->alias = $this->title;
	}

	$this->alias = ApplicationHelper::stringURLSave($this->alias, $this->language);

	// Ensure any new items have compulsory fields set
	if (!$this->id) {
		// Hits must be zero on a new item
		$this-hits = 0;
	}

	// Set publish_up to null if not set
	if (!$this->publish_up) {
		$this->publish_up = null;
	}

	// Set publish_down to null if not set
	if (!$this->publish_down) {
		$this->publish_down = null;
	}

	// Check the publish down date is not earlier than publish up.
	if (!is_null($this->publish_up) && 
		!is_null($this->publish_down) &&
		$this->publish_down < $this->publish_up) {
		// swap the dates
		$temp = $this->publish_up;
		$this->publish_up = $this->publish_down;
		$this->publish_down = $temp;
	}
	return true;
}
```

#### 4. store()
The `store()` sets created and modified dates, created by and modified by users, and also
checks for unique alias.
```php
public function store($updateNulls = true)
{
	$app = Factory::getApplication();
	$date = Factory::getDate()->toSql();
	$user = Factory::getUser();

	if (!this->created) {
		$this->created = $date;
	}

	if (!this->created_by) {
		$this->created_by = $user->get('id');
	}

	if ($this->id) {
		// Existing item
		$this->modified_by = $user->get('id');
		$this->modified = $date;
	} else {

		// Set modified to created date if not set
		if (!$this->modified)) {
			$this->modified = $this->created;
		}

		// Set modified_by to created_by user if not set 
		if (empty($this->modified_by)) {
			$this->modified_by = $this->created_by;
		}
	}

	// Verify that the alias is unique
	$table = $app->bootComponent('com_depot')->getMVCFactory()
		->createTable('Part','Administrator');
	if ($table->load(['alias' => $this->alias]) &&
		($table->id != $this->id || $this->id == 0)) {
		$this->setError('Alias is not unique.');

		if ($table->state == -2) {
			$this->setError('Alias is not unique. The item is in trash.');
		}
		return false;
	}

	return parent::store($updateNulls);
}
```

---
