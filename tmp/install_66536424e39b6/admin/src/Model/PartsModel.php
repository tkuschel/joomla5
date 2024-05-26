<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.1
 */

namespace KW4NZ\Component\Depot\Administrator\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class PartsModel extends ListModel
{
	public function __construct($config = [])
	{
		$config['filter_fields'] = [
			'id',
			'd.id',
			'state',
			'd.state',
			'component_name',
			'd.component_name',
			'alias',
			'd.alias',
			'quantity',
			'd.quantity',
			'ordering',
			'd.ordering',
			'description',
			'd.description',
			'published',
			'd.published',
			'package',
			'manufacturer',
			'stock_name',
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
					$db->quoteName('d.id'),
					$db->quoteName('d.state'),
					$db->quoteName('d.component_name'),
					$db->quoteName('d.alias'),
					$db->quoteName('d.description'),
					$db->quoteName('d.quantity'),
					$db->quoteName('d.quantity_exp'),
					$db->quoteName('d.ordering'),
					$db->quoteName('d.package_id'),
					$db->quoteName('d.checked_out'),
					$db->quoteName('d.checked_out_time'),
				]
			)
		)
			->select(
				[
					$db->quoteName('u.name', 'creator'),
					$db->quoteName('m.name_short', 'manufacturer'),
					$db->quoteName('m.name_long', 'manufacturer_long'),
					$db->quoteName('s.name', 'stock_name'),
					$db->quoteName('p.name', 'package_name'),
					$db->quoteName('p.description', 'package_description'),
					$db->quoteName('ms.title', 'mounting_style'),
					$db->quoteName('v.name', 'owner'),
				]
			)
			->from($db->quoteName('#__depot', 'd'))
			->join('LEFT', $db->quoteName('#__depot_manufacturer', 'm'), $db->quoteName('m.id') . ' = ' . $db->quoteName('d.manufacturer_id'))
			->join('LEFT', $db->quoteName('#__depot_stock', 's'), $db->quoteName('s.id') . ' = ' . $db->quoteName('d.stock_id'))
			->join('LEFT', $db->quoteName('#__depot_package', 'p'), $db->quoteName('p.id') . ' = ' . $db->quoteName('d.package_id'))
			->join('LEFT', $db->quoteName('#__users', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('d.checked_out'))
			->join('LEFT', $db->quoteName('#__depot_mounting_style', 'ms'), $db->quoteName('ms.id') . ' = ' . $db->quoteName('p.mounting_style_id'))
			->join('LEFT', $db->quoteName('#__users', 'v'), $db->quoteName('v.id') . ' = ' . $db->quoteName('s.owner_id'));
		// filter: like / search
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where($db->quoteName('d.component_name') . ' LIKE ' . $like);
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');
		if (is_numeric($published)) {
			$published = (int) $published;
			$query->where($db->quoteName('d.state') . ' = :published')
				->bind(':published', $published, ParameterType::INTEGER);
		} elseif ($published === '') {
			$query->whereIn($db->quoteName('d.state'), [0, 1]);
		}

		// add list ordering clause
		$query->order(
			$db->quoteName($db->escape($this->getState('list.ordering', 'd.ordering'))) . ' ' .
			$db->escape($this->getState('list.direction', 'ASC'))
		);

		return $query;
	}
}