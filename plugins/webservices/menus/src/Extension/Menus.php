<?php

/**
 * @package     Joomla.Menus
 * @subpackage  Webservices.menus
 *
 * @copyright   (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\WebServices\Menus\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_menus.
 *
 * @since  4.0.0
 */
final class Menus extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeApiRoute' => 'onBeforeApiRoute',
        ];
    }

    /**
     * Registers com_menus's API's routes in the application
     *
     * @param   BeforeApiRouteEvent  $event  The event object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
    {
        $router = $event->getRouter();

        $router->createCRUDRoutes(
            'v1/menus/site',
            'menus',
            ['component' => 'com_menus', 'client_id' => 0]
        );

        $router->createCRUDRoutes(
            'v1/menus/administrator',
            'menus',
            ['component' => 'com_menus', 'client_id' => 1]
        );

        $router->createCRUDRoutes(
            'v1/menus/site/items',
            'items',
            ['component' => 'com_menus', 'client_id' => 0]
        );

        $router->createCRUDRoutes(
            'v1/menus/administrator/items',
            'items',
            ['component' => 'com_menus', 'client_id' => 1]
        );

        $routes = [
            new Route(
                ['GET'],
                'v1/menus/site/items/types',
                'items.getTypes',
                [],
                ['public' => false, 'component' => 'com_menus', 'client_id' => 0]
            ),
            new Route(
                ['GET'],
                'v1/menus/administrator/items/types',
                'items.getTypes',
                [],
                ['public' => false, 'component' => 'com_menus', 'client_id' => 1]
            ),
        ];

        $router->addRoutes($routes);
    }
}
