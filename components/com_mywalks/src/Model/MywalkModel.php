<?php
/**
 * @package     Mywalks.Site
 * @subpackage  com_mywalks
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Mywalks\Site\Model;

defined('_JEXEC') or die;

use Joomla\Database\ParameterType;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;

/**
 * Mywalk Component Mywalk Model
 *
 * @since  1.5
 */
class MywalkModel extends ItemModel
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_mywalks.mywalk';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('mywalk.id', $pk);
	}

	/**
	 * Method to get walk data.
	 *
	 * @param   integer  $pk  The id of the walk.
	 *
	 * @return  object|boolean  Menu item data object on success, boolean false
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('mywalk.id');

		try {
			$db = $this->getDatabase();
			$query = $db->createQuery()
				->select(
					$this->getState(
						'item.select',
						'a.*'
					)
				);
			$query->from($db->quoteName('#__mywalks') . ' AS a')
				->where($db->quoteName('a.id') . ' = :id')
				->bind(':id', $pk, ParameterType::INTEGER);

			$db->setQuery($query);

			$data = $db->loadObject();

			if (empty($data)) {
				throw new \Exception(Text::_('COM_MYWALKS_ERROR_WALK_NOT_FOUND'), 404);
			}
		} catch (\Exception $e) {
			if ($e->getCode() == 404) {
				// Need to go through the error handler to allow Redirect to work.
				throw new \Exception($e->getMessage(), 404);
			} else {
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}

		return $data;
	}
	/**
	 * Method to get walk visit data.
	 *
	 * @param   integer  $pk  The id of the walk.
	 *
	 * @return  object|boolean  Menu item data object on success, boolean false
	 */
	public function getReports($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('mywalk.id');

		try {
			$db = $this->getDatabase();
			$query = $db->createQuery()
				->select('b.*')
				->from($db->quoteName('#__mywalk_dates') . ' AS b')
				->where($db->quoteName('b.walk_id') . ' = :id')
				->bind(':id', $pk, ParameterType::INTEGER)
				->order($db->quoteName('date') . ' DESC');

			$db->setQuery($query);

			$data = $db->loadObjectList();

			// It is OK to have a walk without visit data - handle it the view.
		} catch (\Exception $e) {
			if ($e->getCode() == 404) {
				// Need to go through the error handler to allow Redirect to work.
				throw new \Exception($e->getMessage(), 404);
			} else {
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}

		return $data;
	}
}
