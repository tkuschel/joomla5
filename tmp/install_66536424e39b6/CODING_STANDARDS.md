# KW4NZ coding standards

This file lists standards that any programmer adding or changing code in
this project should follow.

## Code implementation

1.	Document your code in source files and the manual. (tm)

1.	This project is implemented in PHP Version 8.2. The base of the Joomla 
	core is version 5.0 as a minimum and is not maintained backward compatible.

1.	The return type of "is" or "has" style functions should be `bool`,
	which return a "yes"/"no" answer.  `zend_result` is an appropriate
	return value for functions that perform some operation that may
	succeed or fail.

## User functions/methods naming conventions

1. Function names should be in lowercase, with words underscore
 	delimited, with care taken to minimize the letter count.
	The exception to this is of course the Joomla core functions and
	Joomla naming conventions for classes.
	Abbreviations should not be used when they greatly decrease
	the readability of the function name itself:

	Good:

	```php
	str_word_count
	array_key_exists
	```

	Ok:

	```php
	date_interval_create_from_date_string
    // Could be 'date_intvl_create_from_date_str'?
	get_html_translation_table()
	// Could be 'html_get_trans_table'?
	```

	Bad:

	```php
	hw_GetObjectByQueryCollObj
	pg_setclientencoding
	jf_n_s_i
	```

1. If they are part of a "parent set" of functions, that parent should be
	included in the user function name, and should be clearly related to the
	parent program or function family. This should be in the form of `parent_*`:

	A family of `foo` functions, for example:

	Good:

	```php
	foo_select_bar
	foo_insert_baz
	foo_delete_baz
	```

	Bad:

	```php
	fooselect_bar
	fooinsertbaz
	delete_foo_baz
	```

1. Variable names must be meaningful. One letter variable names must be avoided,
	except for places where the variable has no real meaning or a trivial
	meaning (e.g. `for ($i=0; $i<100; $i++) ...`).

1. Variable names should be in lowercase. Use underscores to separate between
	words.

1. Method names follow the *studlyCaps* (also referred to as *bumpy case* or
	*camel caps*) naming convention, with care taken to minimize the letter
	count. The initial letter of the name is lowercase, and each letter that
	starts a new `word` is capitalized:

	Good:

	```php
	connect()
	getData()
	buildSomeWidget()
	```

	Bad:

	```php
	get_Data()
	buildsomewidget()
	getI()
	```

1. Class names should be descriptive nouns in *PascalCase* and as short as
	possible. Each word in the class name should start with a capital letter,
	without underscore delimiters. The class name should be prefixed with the
	name of the "parent set" (e.g. the name of the extension) if no namespaces
	are used. Abbreviations and acronyms as well as initialisms should be
	avoided wherever possible, unless they are much more widely used than the
	long form (e.g. HTTP or URL). Abbreviations start with a capital letter
	followed by lowercase letters, whereas acronyms and initialisms are written
	according to their standard notation. Usage of acronyms and initialisms is
	not allowed if they are not widely adopted and recognized as such.

	Good:

	```php
	Curl
	CurlResponse
	HTTPStatusCode
	URL
	BTreeMap // B-tree Map
	Id // Identifier
	ID // Identity Document
	Char // Character
	Intl // Internationalization
	Radar // Radio Detecting and Ranging
	```

	Bad:

	```php
	curl
	curl_response
	HttpStatusCode
	Url
	BtreeMap
	ID // Identifier
	CHAR
	INTL
	RADAR // Radio Detecting and Ranging
	```

## Syntax and indentation

1. Use K&R-style and use 1TBS ()"One True Bace Style").
	Of course, we can't and don't
	want to force anybody to use a style he or she is not used to, but, at the very
	least, when you write code that goes into the core of PHP or one of its standard
	modules, please	maintain the K&R style.
	This applies to just about everything, starting with indentation and comment
	styles and up to function declaration syntax. Also
	see [Indentstyle](http://www.catb.org/~esr/jargon/html/I/indent-style.html).
	When following K&R, each function has its opening brace at the next line on the
	same indentation level as its header, the statements within the braces are
	indented, and the closing brace at the end is on the same indentation level as
	the header of the function at a line of its own.

```php
protected function batch_client($value, $pks, $contexts)
{
	// Set the variables
	$user	= $this->getCurrentUser();
	$table	= $this->getTable();

	foreach ($pks as $pk) {
		if (!$user->authorise('core.edit', $contexts[$pk])) {
			$this->setError(Text::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

			return false;
		}

		$table->reset();
		$table->load($pk);
		$table->cid = (int) $value;

		if (!$table->store()) {
			$this->setError($table->getError());

			return false;
		}
	}

	// Clean the cache
	$this->cleanCache();

	return true;
}
```

1. Be generous with whitespace and braces. Keep one empty line between the
	variable declaration section and the statements in a block, as well as
	between logical statement groups in a block. Maintain at least one empty
	line between two functions, preferably two. Always prefer:

	```php
	if (foo) {
		bar;
	}
	```

	to:

	```php
	if(foo)bar;
	```

1. When indenting, use the tab character. A tab is expected to represent four
	spaces (1 tab == 4 spaces). It is important to maintain consistency in indentation so that
	definitions, comments, and control structures line up correctly.

## Trailing whitespace, EOF

1. Trailing whitespace must not be present after statements or serial comma break
or on blank lines. Remove trailing white spaces.

	Good:

	```php
	$quotes_exist = false;
	$my_movies = [
	\t'Slumdog Millionaire',
		'The Lives of Ohters',
		'The Shawshank Redemption'
	];

	print_welcome_message();
	//EOF
	```

	Bad, because there is a space after `','` , also two spaces on the blank line below `print_welcome_message()`. No whitespaces in the last line.:

	```php
	$quotes_exist = false;  
	$my_movies = [
		'Slumdog Millionaire', 
		'The Lives of Ohters', 
		'The Shawshank Redemption'
	];
	  
	print_welcome_message();
		 
	  //EOF
	```

1. Use newline at the end of a file (EOF). 

## Doctype

Always use the minimal doctype

```php
<doctype html>
```


## Capitalisation
All HTML should be lowercase; element names, attributes, attribute values
(unless text/CDATA), CSS selectors, properties, and property values (except of strings).
Additionally, there is no need to use CDATA to escape inline JavaScript, formerly a
requirement to meet XML strictness in XHTML.

## Documentation headers
Documentation headers for PHP code in: files, classes, class properties, methods and functions, called the **docblocks**.
The file header DocBlock consists of the following required and optional elements in
the following order:

- @version (optional and must be first)
- @category (optional and rarely used)
- @package (generally optional but required when files contain only procedural code.
	Always optional in namespaced code)
- @subpackage (optional)
- @author (optional but only permitted in non-Joomla sources files)
- @copyright (required)
- @license (required and must be compatible with the Joomla license)
- @link(optional)
- @see (optional)
- @since (generally optional but required when files contain only procedural code)
- @deprecated (optional)

```php
/**
* @package		Depot.Administrator
* @subpackage	com_depot
* @author		Thomas Kuschel <thomas@kuschel.at>
* @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @since		0.1
```

## PHP code
Use the full `<?php  ... >` to delimit PHP code. Since PHP8.0, the short tag `<? ...?>` is obsolete and removed.

For files that contain only PHP code, the closing tag (`?>`) should not be included.
It is not required by PHP. Leaving this out prevents trailing white space from being
accidentally injected into the output that can introduce errors.

PHP includes a short tag `<?=` which is a short-hand to the more verbose `<?php echo`.
Since PHP5.4 this is always available and should be used in templates for more
readabilty.

```php
	<?php $u = "chris"; ?><p>Welcome <?= $u ?></p>
```
is equivalent and more readable than:

```php
	<?php $u = "chris"; ?><p>Welcome <?php echo "$u"; ?></p>
```

### Including code
Anywhere you are unconditionally including a file, use **require_once**.
Anywhere you are conditionally including a file (for example, factory mathods),
use **include_once**. Either of these will ensure that files are included only
once. You should not enclose the filename in parentheses.

```php
require_once JPATH_COMPONENT_ADMINISTRATOR . '/Helper/InstallerHelper.php';
```

### Global variables
Global variables should not be used. Use static class properties or constants
instead of globals, following OOP and factory patterns.

### Control structures
For all control structures there is a space between the keyword and an opening
paranthesis, then no space either after the opening parenthesis or before the
closing bracking. This is done to distinguish control keywords from function names.
All control structures must contain their logic within braces.

### Concatenation spacing
There should always be a space before and after the concatenation operator `('.')` because
of readablity.

### Constants
Constants should always be all-uppercase, with underscores to separate words.

### Namespaces

Namespaces are formatted according to this flow: First there is the file docblock followed by the namespace the file lives in. When required, the namespace is followed by the defined check. Lastly, the imported classes using the use keyword. All namespace imports should be alphabetically ordered.

### Function Calls

Functions should be called with no spaces between the function name and the opening parenthesis, and no space between this and the first parameter; a space after the comma between each parameter (if they are present), and no space between the last parameter and the closing parenthesis. There should be space before and exactly one space after the equals sign.
