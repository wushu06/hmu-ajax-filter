<?php

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

class FieldsCallbacks extends BaseController
{

        public function sanitizeCallback($options)
    {

    }

    public function adminSectionManager()
    {
        echo 'Import Prices and Users';
    }

    public function dashboardSectionManager()
    {
        echo 'Dashboard Control';
    }

    public function cronSectionManager()
    {
    }

    public function hmuTemplate($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<input type="text" class="regular-text hmu-input" name="' . $option_name . '[' . $name . ']"  value="' . $isvalue . '"  placeholder="Default is woocommerce content-product">';
    }


    public function hmuWrapperID($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<input type="text" class="regular-text hmu-input" name="' . $option_name . '[' . $name . ']"  value="' . $isvalue . '"  placeholder="id attribute wrapping the products list">';
    }

    public function hmuCustomClass($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<input id="' . $name . '" type="text" class="' . $classes . ' regular-text hmu-input" name="' . $option_name . '[' . $name . ']"  value="' . $isvalue . '"  placeholder="Custom class">';
    }

    public function hmuCheckboxLabel($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                        <input id="hmuCheckbox" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                        <label for="hmuCheckbox" class="checkbox-label" data-off="off" data-on="on">
                        </label></div>';
    }

    public function hmuHideParents($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                <input id="hmuHideParent" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                <label for="hmuHideParent" class="checkbox-label" data-off="off" data-on="on">
                </label></div>';
    }

    public function sunset_custom_css_callback($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<div id="customCss">' . $isvalue . '</div><textarea id="hmu_css" name="' . $option_name . '[' . $name . ']" style="display: none;">' . $isvalue . '</textarea>';
    }
}
