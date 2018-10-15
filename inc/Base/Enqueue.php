<?php

namespace Inc\Base;

use Inc\Base\BaseController;

class Enqueue extends BaseController
{

    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'hmu_filter_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'hmu_admin_filter_scripts'));
    }

    public function hmu_filter_scripts()
    {
        wp_enqueue_style('bootstrapCss', plugins_url() . '/hmu-woo-filter/assets/bootstrap.min.css', array(), '1.0.1');
        wp_enqueue_style('hmuCss', plugins_url() . '/hmu-woo-filter/assets/filter.css', array(), '1.0.1');
        wp_enqueue_script('hmuJs', plugins_url() . '/hmu-woo-filter/assets/filter.js', array(), null, true);

        $id = '';
        if ($dashboard_option = get_option('hmu_dashboard')) {
            $id = $dashboard_option["wrapper_id"];
        }

            // $translation_array = array('adminAjax' => admin_url('admin-ajax.php'));
        wp_localize_script('hmuJs', 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'wrapper_id' => $id
        ));
    }

    public function hmu_admin_filter_scripts($hook)
    {
        if ($hook != 'toplevel_page_hmu_woo_filter') {
            return;
        }

        wp_enqueue_style('hmuAdminCss', plugins_url() . '/hmu-woo-filter/assets/hmu-admin.css', array(), '1.0.1');
    }
}