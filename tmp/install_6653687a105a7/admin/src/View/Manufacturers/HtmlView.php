<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.3
 */

namespace KW4NZ\Component\Depot\Administrator\View\Manufacturers;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
// use Joomla\CMS\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

// use Joomla\CMS\Language\Text;


class HtmlView extends BaseHtmlView
{
	public function display($tpl = null)
	{
		// Get application
		$app = Factory::getApplication();

		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		// list order
		$this->listOrder = $this->escape($this->state->get('list.ordering'));
		$this->listDirn = $this->escape($this->state->get('list.direction'));
		// add pagination
		$this->pagination = $this->get('Pagination');
		// adding filters
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// set the toolbar
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_DEPOT_MANAGER_MANUFACTURERS'));
		ToolbarHelper::addNew('manufacturer.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'manufacturers.delete');
		ToolbarHelper::publish('manufacturers.publish', 'JTOOLBAR_PUBLISH', true);
		ToolbarHelper::unpublish('manufacturers.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}