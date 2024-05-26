<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.3
 */

namespace KW4NZ\Component\Depot\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class StocksModel extends ListModel
{
	public function __construct($config = [])
	{
		$config['filter_fields'] = [
			'id',
			's.id',
			'name',
			's.name',
			'alias',
			's.alias',
			'state',
			's.state',
			'published',
			's.published',
			'description',
			's.description',
			'ordering',
			's.ordering',
			'checked_out',
			's.checked_out',
			'owner',
		];
		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \Joomla\Database\DatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true);


		//		$query->select('*')
//			->from($db->quoteName('#__depot', 'd'));

		// order by
//		$query->order('d.id ASC');
//		if (true) {
//			return $query;
//		}

		// select the required fields from the table
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('s.id'),
					$db->quoteName('s.name'),
					$db->quoteName('s.alias'),
					$db->quoteName('s.description'),
					$db->quoteName('s.state'),
					$db->quoteName('s.ordering'),
					$db->quoteName('s.checked_out'),
					$db->quoteName('s.checked_out_time'),
				]
			)
		)
			->select(
				[
					$db->quoteName('u.name', 'creator'),
					$db->quoteName('o.name', 'owner'),
					$db->quoteName('o.username', 'owner_username'),
				]
			)
			->from($db->quoteName('#__depot_stock', 's'))
			->join('LEFT', $db->quoteName('#__users', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('s.checked_out'))
			->join('LEFT', $db->quoteName('#__users', 'o'), $db->quoteName('o.id') . ' = ' . $db->quoteName('s.owner_id'));
		// filter: like / search
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where('(' . $db->quoteName('s.name') . ' LIKE ' . $like . ' OR ' .
				$db->quoteName('s.description') . ' LIKE ' . $like . ')');
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');
		if (is_numeric($published)) {
			$published = (int) $published;
			$query->where($db->quoteName('s.state') . ' = :published')
				->bind(':published', $published, ParameterType::INTEGER);
		} elseif ($published === '') {
			$query->where($db->quoteName('s.state') . ' IN (0, 1)');
		}

		// Add the list ordering clause.
		$query->order(
			$db->quoteName($db->escape($this->getState('list.ordering', 's.ordering'))) . ' ' .
			$db->escape($this->getState('list.direction', 'ASC'))
		);

		return $query;
	}

	/**
	 * Returns a reference to the Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Stock', $prefix = 'Administrator', $config = [])
	{
		return parent::getTable($type, $prefix, $config);
	}

}