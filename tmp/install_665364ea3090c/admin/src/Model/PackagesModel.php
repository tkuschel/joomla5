<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.7
 */

namespace KW4NZ\Component\Depot\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class PackagesModel extends ListModel
{
	public function __construct($config = [])
	{
		$config['filter_fields'] = [
			'id',
			'p.id',
			'name',
			'p.name',
			'alias',
			'p.alias',
			'state',
			'p.state',
			'published',
			'p.published',
			'mounting_style_id',
			'p.mounting_style_id',
			'mounting_style',
			'description',
			'p.description',
			'ordering',
			'p.ordering',
			'checked_out',
			'p.checked_out',
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
					$db->quoteName('p.id'),
					$db->quoteName('p.name'),
					$db->quoteName('p.alias'),
					$db->quoteName('p.description'),
					$db->quoteName('p.image'),
					$db->quoteName('p.state'),
					$db->quoteName('p.ordering'),
					$db->quoteName('p.checked_out'),
					$db->quoteName('p.checked_out_time'),
					$db->quoteName('p.modified'),
					$db->quoteName('p.modified_by'),
					$db->quoteName('p.mounting_style_id'),
				]
			)
		)
			->select(
				[
					$db->quoteName('u.name', 'creator'),
					$db->quoteName('ms.title', 'mounting_style'),
				]
			)
			->from($db->quoteName('#__depot_package', 'p'))
			->join('LEFT', $db->quoteName('#__users', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('p.checked_out'))
			->join('LEFT', $db->quoteName('#__depot_mounting_style', 'ms'), $db->quoteName('ms.id') . ' = ' . $db->quoteName('p.mounting_style_id'));

		// filter: like / search
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where($db->quoteName('p.name') . ' LIKE ' . $like);
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');
		if (is_numeric($published)) {
			$published = (int) $published;
			$query->where($db->quoteName('p.state') . ' = :published')
				->bind(':published', $published, ParameterType::INTEGER);
		} elseif ($published === '') {
			$query->where($db->quoteName('p.state') . ' IN (0, 1)');
		}

		// add list ordering clause
		$query->order(
			$db->quoteName($db->escape($this->getState('list.ordering', 'p.ordering'))) . ' ' .
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
	public function getTable($type = 'Package', $prefix = 'Administrator', $config = [])
	{
		return parent::getTable($type, $prefix, $config);
	}
}