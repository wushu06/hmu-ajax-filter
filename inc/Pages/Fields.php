<?php

namespace Inc\Pages;

use \Inc\Base\BaseController;

use \Inc\Api\SettingsApi;

use \Inc\Api\Callbacks\FieldsCallbacks;

class Fields extends BaseController
{

    public $settings;
	public $fields_callbacks;
    public $pages = array();
	public $subpages = array();

   

    function register() {

               // add_action('admin_menu', array($this, 'register_my_custom_submenu_page' ));

	        	$this->settings = new SettingsApi();
				$this->fields_callbacks = new FieldsCallbacks();

		
				$this->setSettings();
				$this->setSections();
				$this->setFields();
		
				$this->settings->register();

				
    }


    /*
    * create fields
    */
	public function setSettings()
	{
        /*
         * # for each page create group of fields and give each group option name
         * #
         */


		$args = array(
			array(
				'option_group' => 'hmu_dashboard_options_group',
				'option_name' => 'hmu_dashboard',
				//'callback' => array( $this->fields_callbacks,'sanitizeCallback' )
			)
			
		);
	
		

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
            array(
                'id' => 'hmu_dashboard_index',
                'title' => 'Dashboard',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'hmu_woo_filter' //dahboard page
            )
		);

		$this->settings->setSections( $args );
	}

    public function dahboardFields()
    {
        return  array(
            // ID
            //0- title 1- callback 2-page 3- section 4- option name 5-input type

            'wrapper_id' =>
                array('',
                    'hmuWrapperID',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'custom_class' =>
                array('',
                    'hmuCustomCLass',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'use_checkbox' =>
                array('Remove checkboxes',
                    'hmuCheckboxLabel',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'hide_parent' =>
                array('Hide categories parents',
                    'hmuHideParents',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'custom-css' =>
                array('Insert your Custom CSS',
                    'sunset_custom_css_callback',
                    'hmu_woo_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),


        );


    }

    public function setFields()
	{
		$args = array ();


		foreach ($this->dahboardFields()   as $id_dash => $dashtitle_callback ) {
			
			$args[] = array (
				'id' => $id_dash,
				'title' => $dashtitle_callback[0],
				'callback' => array( $this->fields_callbacks, $dashtitle_callback[1] ),
				'page' => $dashtitle_callback[2],
				'section' => $dashtitle_callback[3],
					'args' => array(
						'option_name' => $dashtitle_callback[4],
						'label_for' => $id_dash,
						'class' => 'hmu-upload'
					)
				);
		}
		

		$this->settings->setFields( $args );
	}

}