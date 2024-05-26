<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.7
 */

namespace KW4NZ\Component\Depot\Administrator\View\Packages;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects


class HtmlView extends BaseHtmlView
{
	public function display($tpl = null)
	{
		/** @var PackagesModel $model */
		$model = $this->getModel();
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->state = $model->getState();
		$this->filterForm = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();

		if (!\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState')) {
			$this->setLayout('emptystate');
		}

		// Set the toolbar
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_DEPOT_MANAGER_PACKAGES'));
		ToolbarHelper::addNew('package.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'packages.delete');
		ToolbarHelper::publish('packages.publish', 'JTOOLBAR_PUBLISH', true);
		ToolbarHelper::unpublish('packages.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}