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
// use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

\defined('_JEXEC') or die;

class ManufacturersModel extends ListModel
{
	public function __construct($config = [])
	{
		$config['filter_fields'] = [
			'id',
			'm.id',
			'name_short',
			'm.name_short',
			'name_long',
			'm.name_long',
			'alias',
			'm.alias',
			'state',
			'm.state',
			'published',
			'm.published',
			'description',
			'm.descrition',
			'image',
			'm.image',
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
					$db->quoteName('m.id'),
					$db->quoteName('m.name_short'),
					$db->quoteName('m.name_long'),
					$db->quoteName('m.alias'),
					$db->quoteName('m.description'),
				]
			)
		)
			->select(
				[
					$db->quoteName('u.name', 'creator'),
				]
			)
			->from($db->quoteName('#__depot_manufacturer', 'm'))
			->join('LEFT', $db->quoteName('#__users', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('m.checked_out'));

		// filter: like / search
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where('(' . $db->quoteName('m.name_long') . ' LIKE ' . $like . ' OR ' .
				$db->quoteName('m.name_short') . ' LIKE ' . $like . ')');
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');
		if (is_numeric($published)) {
			$published = (int) $published;
			$query->where($db->quoteName('m.state') . ' = :published')
				->bind(':published', $published, ParameterType::INTEGER);
		} elseif ($published === '') {
			$query->where($db->quoteName('m.state') . ' IN (0, 1)');
		}

		// add list ordering clause
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}
}