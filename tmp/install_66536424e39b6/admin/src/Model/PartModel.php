<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.3
 */

namespace KW4NZ\Component\Depot\Administrator\Model;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

\defined('_JEXEC') or die;

class PartModel extends AdminModel
{
	public function getForm($data = [], $loadData = true)
	{
		$form = $this->loadForm('com_depot.part', 'part', ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$app = Factory::getApplication();
		$data = $app->getUserState('com_depot.edit.part.data', []);

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function save($data)
	{
		/* Add code to modify data before saving */

		// if (Factory::getConfig()->get('unicodeslugs') == 1) {
		//	$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['component_name']);
		// } else {
		//	$data['alias'] = OutputFilter::stringURLSafe($data['component_name']);
		// }

		/* replaced by: */
		if (empty($data['alias'])) {
			$data['alias'] = ApplicationHelper::stringURLSafe($data['component_name']);
		}
		$result = parent::save($data);
		// if ($result) {
		//	$this->getTable('', 'Administrator')->rebuild(1);
		// }
		return $result;
	}
}