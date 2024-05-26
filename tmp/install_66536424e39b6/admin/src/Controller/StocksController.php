<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.9.9
 */

namespace KW4NZ\Component\Depot\Administrator\Controller;

use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class StocksController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var		string
	 * @since	0.9.9
	 */
	protected $text_prefix = 'COM_DEPOT_STOCKS';

	public function getModel($name = 'Stock', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}
}