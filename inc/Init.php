<?php
/**
 * Init class global initialize to all the classes
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace Inc;

use Inc\Pages\Fields;
use Inc\Pages\Menu;
use Inc\Base\Enqueue;
use Inc\Base\SettingsLinks;
use Inc\Filter\HmuShortcode;

final class Init
{

    public static function getServices()
    {
        return [
            new Fields(),
            new Menu(),
            new Enqueue(),
            new SettingsLinks(),
            new HmuShortcode()

        ];
    }


    public static function registerServices()
    {
        foreach (self::getServices() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    protected static function instantiate($class)
    {
        $service = new $class();
        return $service;
    }
}
