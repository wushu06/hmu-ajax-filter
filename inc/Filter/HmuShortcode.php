<?php

/**
 * HmuShortcode class generate the shortcode for the plugin
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */


namespace Inc\Filter;

use Inc\Filter\HmuAllTaxonomies;
use Inc\Filter\HmuAjax;

class HmuShortcode
{
    public function __construct()
    {
        new HmuAjax();
        add_shortcode('HmuTaxonomies', array($this, 'hmuTaxonomyOptionShortcode'));
    }


    public function hmuTaxonomyOptionShortcode($atts)
    {

        $category = new HmuAllTaxonomies();
        $category->register();
    }
}
