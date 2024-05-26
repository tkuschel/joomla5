<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.3
 */

namespace KW4NZ\Component\Depot\Administrator\View\Part;

use Joomla\CMS\Factory;
// use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

\defined('_JEXEC') or die;

/**
 * View to edit an article.
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws	\Exception
	 * @since	1.6
	 */
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		//	if (count($errors = $this->get('Errors'))) {
		//		throw new GenericDataException(implode("\n", $errors), 500);
		//	}
		$this->addToolbar();

		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->getInput()->set('hidemainmenu', true);

		ToolbarHelper::title('Part: Add');

		ToolbarHelper::apply('part.apply');
		ToolbarHelper::save('part.save');
		ToolbarHelper::cancel('part.cancel', 'JTOOLBAR_CLOSE');
	}
}