<?php
namespace Inc\Pages;
use Inc\Base\BaseController;

class Menu extends BaseController
{
    public function register()
    {
        add_action('admin_menu', array($this, 'hmu_woo_filter_pages'));


    }
    public  function hmu_woo_filter_pages() {
        add_menu_page(
            'Hmu woo filter',// the page title
            'Filter',//menu title
            'manage_options',//capability
            'hmu_woo_filter',//menu slug/handle this is what you need!!!
            array($this, 'my_custom_menu_page_callback'), // callback
            'dashicons-networking',//icon_url,
            '50'//position
        );
        add_submenu_page(
            'hmu_woo_filter',
            'Hmu woo filter',
            'Hmu filter',
            'manage_options',
            'hmu_woo_filter',
            array($this, 'my_custom_submenu_page_callback')
        );
    }
    public function my_custom_menu_page_callback() {
        require_once( $this->plugin_path."/template/dashboard.php" );

    }

    public function my_custom_submenu_page_callback() {
        require_once( $this->plugin_path."/template/dashboard.php" );

    }
}