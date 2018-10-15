<?php

namespace Inc\Filter;
use Inc\Filter\HmuAttributeFilter;
use Inc\Filter\HmuCategoryFilter;
use Inc\Filter\HmuAllTaxonomies;
use Inc\Filter\HmuAjax;

class HmuShortcode
{
    function __construct()
    {
        new HmuAjax();
        add_shortcode('HmuAttributes', array($this, 'hmu_attribute_shortcode'));
        add_shortcode('HmuCategories', array($this, 'hmu_category_shortcode'));
        add_shortcode('HmuTaxonomies', array($this, 'hmu_taxonomy_option_shortcode'));
    }

    /**
     * Load theme's JavaScript and CSS sources.
     */


    function hmu_attribute_shortcode($atts)
    {
        /* $a = shortcode_atts( array(
             'foo' => 'something',
             'bar' => 'something else',
         ), $atts );

         return "foo = {$a['foo']}";*/


        $filter = new HmuAttributeFilter();
        $filter->hmu_filter_html();

    }

    function hmu_category_shortcode($atts)
    {

          $category = new HmuCategoryFilter();
          $category->hmu_category_html();


    }

    function hmu_taxonomy_option_shortcode($atts)
    {

        $category = new HmuAllTaxonomies();
        $category->hmu_taxonomy_option_to_html();


    }


}