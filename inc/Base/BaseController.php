<?php

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $subpagesOutput = array();

    public $dahboardFields = array();
    public $fieldsOutput = array();


    public function __construct()
    {
        /*$this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));*/
        $this->plugin_path = SITE_ROOT.'/';
        $this->plugin_url = plugins_url().'/hook-me-up/';

        $this->subpagesOutput = array(

        );

        /*
        * FIELDS
        */

        $this->dahboardFields = array(
            // ID
            //0- title 1- callback 2-page 3- section 4- option name 5-input type


            'wrapper_id' =>
                array('',
                    'hmuWrapperID',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                    'boolean'
                ),
            'custom_class' =>
                array('',
                    'hmuCustomCLass',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                    'boolean'
                ),
            'use_checkbox' =>
                array('Remove checkboxes',
                    'hmuCheckboxLabel',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                    'boolean'
                ),
            'hide_parent' =>
                array('Hide categories parents',
                    'hmuHideParents',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                    'boolean'
                ),


        );


    }

    function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }


}