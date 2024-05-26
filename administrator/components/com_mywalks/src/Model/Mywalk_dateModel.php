<?php
/**
 * @package     Mywalks.Administrator
 * @subpackage  com_mywalks
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Mywalks\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\Database\ParameterType;
use Joomla\CMS\Factory;
//use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
//use Joomla\CMS\Table\Table;
//use Joomla\CMS\Table\TableInterface;
use J4xdemos\Component\Mywalks\Administrator\Extension\MywalksComponent;

//use Joomla\Registry\Registry;

/**
 * Item Model for a single walk.
 *
 * @since  1.6
 */

class Mywalk_dateModel extends AdminModel
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_MYWALKS';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			return $this->getCurrentUser()->authorise('core.delete', 'com_mywalks.mywalks.' . (int) $record->id);
		}

		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		// Check for existing article.
		if (!empty($record->id)) {
			return $this->getCurrentUser()->authorise('core.edit.state', 'com_mywalks.mywalks.' . (int) $record->id);
		}

		// Default to component settings if neither article nor category known.
		return parent::canEditState($record);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   3.0
	 * @throws  \Exception
	 */
	public function getTable($name = '', $prefix = '', $options = array())
	{
		$name = 'mywalk_dates';
		$prefix = 'Table';

		if ($table = $this->_createTable($name, $prefix, $options)) {
			return $table;
		}

		throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   4.0.0
	 */
	public function publish(&$pks, $value = 1)
	{
		/* this is a very simple method to change the state of each item selected */
		$db = $this->getDatabase();

		$query = $db->createQuery();

		$query->update($db->quoteName('#__mywalk_dates'))
			->set($db->quoteName('state') . ' = :state')
			->bind(':state', $state, ParameterType::INTEGER)
			->whereIN($db->quoteName('id'), $pks);
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_mywalks.mywalk_date', 'mywalk_date', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = Factory::getApplication();
		$data = $app->getUserState('com_mywalks.edit.mywalk_date.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Article Manager: Articles
		}

		$this->preprocessData('com_mywalks.mywalk_date', $data);

		return $data;
	}
}
