<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.1
 */

namespace KW4NZ\Component\Depot\Administrator\View\Parts;

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
		// Get model
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

	protected function addToolbar(): void
	{
		ToolbarHelper::title(Text::_('COM_DEPOT_MANAGER_PARTS'));
		ToolbarHelper::addNew('part.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'parts.delete');
		ToolbarHelper::publish('parts.publish', 'JTOOLBAR_PUBLISH', true);
		ToolbarHelper::unpublish('parts.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}