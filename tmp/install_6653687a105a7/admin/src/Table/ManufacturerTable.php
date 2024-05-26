<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.1
 */

namespace KW4NZ\Component\Depot\Administrator\Table;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

\defined('_JEXEC') or die;

class ManufacturerTable extends Table
{
	function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__depot_manufacturer', 'id', $db);

		// add an alias for published:
		$this->setColumnAlias('published', 'state');
	}

	public function store($updateNulls = true)
	{
		$app = Factory::getApplication();
		$date = Factory::getDate()->toSql();
		// $user = Factory::getUser();
		// $user = $this->getCurrentUser();
		$user = $app->getIdentity();

		if (!$this->created) {
			$this->created = $date;
		}

		if (!$this->created_by) {
			$this->created_by = $user->get('id');
		}

		if ($this->id) {
			// existing item
			$this->modified_by = $user->get('id');
			$this->modified = $date;
		} else {
			// set modified to created date if not set
			if (!$this->modified) {
				$this->modified = $this->created;
			}
			if (empty($this->modified_by)) {
				$this->modified_by = $this->created_by;
			}
		}

		// Verify that the alias is unique
		$table = $app->bootComponent('com_depot')->getMVCFactory()->createTable('Manufacturer', 'Administrator');
		if ($table->load(['alias' => $this->alias]) && ($table->id != $this->id || $this->id == 0)) {
			$this->setError('Alias is not unique.');

			if ($table->state == -2) {
				$this->setError('Alias is not unique. The item is in Trash.');
			}

			return false;
		}

		return parent::store($updateNulls);
	}
}