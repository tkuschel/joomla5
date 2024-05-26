<?php
/**
 * @package		Depot.Site
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.14
 */

namespace KW4NZ\Component\Depot\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;

class PartModel extends ItemModel
{
	public function getItem($pk = null)
	{
		if ($pk == null) {
			$pk = Factory::getApplication()->input->getInt('id');
		}

		$db = $this->getDatabase();
		$query = $db->createQuery()
			->select('*')
			->from($db->quoteName('#__depot', 'd'))
			->where([
				$db->quoteName('d.id') . ' = ' . $db->quote($pk),
				$db->quoteName('d.state') . ' = 1',
			]);

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item;
	}
}