<?php
/**
 * @package		Depot.Administrator
 * @subpackage	com_depot
 * @author		Thomas Kuschel <thomas@kuschel.at>
 * @copyright	(C) 2023 KW4NZ, <https://www.kuschel.at>
 * @license		GNU General Public License version 2 or later; see LICENSE.md
 * @since		0.0.1
 */

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use KW4NZ\Component\Depot\Administrator\Extension\DepotComponent;

return new class implements ServiceProviderInterface {
	public function register(Container $container)
	{
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\KW4NZ\\Component\\Depot'));
		$container->registerServiceProvider(new MVCFactory('\\KW4NZ\\Component\\Depot'));

		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new DepotComponent($container->get(ComponentDispatcherFactoryInterface::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));

				return $component;
			}
		);
	}
};
