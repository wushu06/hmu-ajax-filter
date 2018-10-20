<?php 

namespace Inc;

use Inc\Pages\Fields;
use Inc\Pages\Menu;
use Inc\Base\Enqueue;
use Inc\Filter\HmuShortcode;



final class Init {

    public static function get_services () {


        return [
            new Fields(),
            new Menu(),
            new Enqueue(),
            new HmuShortcode()

        ];

    }

    

    public static function register_services () {
        foreach (self::get_services() as $class ) {
            $service = self::instantiate($class);
            if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
        }

    }

    protected static function instantiate ($class) {
        $service = new $class();
        return $service;

    }
}