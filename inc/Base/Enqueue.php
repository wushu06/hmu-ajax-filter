<?php

/**
 * Enqueue class handles the scripts and styles of the plugin
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace Inc\Base;

use Inc\Base\BaseController;

class Enqueue extends BaseController
{

    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'hmuFilterScripts'));
        add_action('admin_enqueue_scripts', array($this, 'hmuFilterAdminScripts'));
    }

    public function hmuFilterScripts()
    {
        wp_enqueue_style('bootstrapCss', plugins_url() . '/hmu-ajax-filter/assets/bootstrap.min.css', array(), '1.0.1');
        wp_enqueue_style('hmuCss', plugins_url() . '/hmu-ajax-filter/assets/filter.css', array(), '1.0.1');
        wp_enqueue_script('hmuJs', plugins_url() . '/hmu-ajax-filter/assets/filter.js', array(), null, true);

        $id = '';
        $select = '';
        if ($dashboard_option = get_option('hmu_dashboard')) {
            $id = array_key_exists('wrapper_id', $dashboard_option)
                ?  $dashboard_option["wrapper_id"] : 'container';
            $select = array_key_exists('use_select', $dashboard_option)
                ?  $dashboard_option["use_select"] : '';
        }

            // $translation_array = array('adminAjax' => admin_url('admin-ajax.php'));
        wp_localize_script('hmuJs', 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'wrapper_id' => $id,
            'select' => $select
        ));
    }

    public function hmuFilterAdminScripts($hook)
    {
        if ($hook != 'toplevel_page_hmu_ajax_filter') {
            return;
        }

        wp_enqueue_style('hmuAdminCss', plugins_url() . '/hmu-ajax-filter/assets/hmu-admin.css', array(), '1.0.1');
        wp_enqueue_style('hmuAdminStyleCss', plugins_url() . '/hmu-ajax-filter/assets/hmu.custom_css.css', array(), '1.0.1');
        wp_enqueue_script('aceJs', plugins_url() . '/hmu-ajax-filter/assets/ace/ace.js', array(), null, true);
        wp_enqueue_script('hmuCssJs', plugins_url() . '/hmu-ajax-filter/assets/hmu_css.js', array(), null, true);
    }
}
