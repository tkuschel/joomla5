<?php
/**
 * @package		Depot.Site
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.14
 */

namespace KW4NZ\Component\Depot\Site\View\Part;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
	protected $item;

	public function display($tpl = null)
	{
		$this->item = $this->get('Item');

		parent::display($tpl);
	}
}