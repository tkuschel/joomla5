<?php
/**
 * @package		Depot.Site
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.14
 */

namespace KW4NZ\Component\Depot\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
	protected $default_view = 'part';

	public function display($cacheable = false, $urlparams = [])
	{
		return parent::display($cacheable, $urlparams);
	}
}