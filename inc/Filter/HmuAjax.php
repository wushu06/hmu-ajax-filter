<?php

namespace Inc\Filter;

class HmuAjax
{
    public function __construct()
    {

        add_action('wp_ajax_customfilter', array($this, 'hmu_ajax_call'));
        add_action('wp_ajax_nopriv_customfilter', array($this, 'hmu_ajax_call'));
    }

    public function hmu_ajax_call()
    {
        ?>
        <div class="col-md-12">
            <?php

            $nonce = $_POST['nonce'];

            if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
                die('Busted!');
            }
            $cpt = 'product';
            $template = 'woocommerce/content-product';
            if ($dashboard = get_option('hmu_dashboard')) {
                $template = array_key_exists('hmu_template', $dashboard)  ? $dashboard['hmu_template'] : 'woocommerce/content-product';

            }

            $args = array(
                'post_type' => $cpt,
                'posts_per_page' => -1,
                'orderby' => array(
                    'ID' => 'DESC',
                ),

            );

            if ($_POST['args'] == 'true') {
                $result = array();
                foreach ($_POST['attributes'] as $tax => $term) {
                    $result [] = array(
                        'taxonomy' => $tax,
                        'field' => 'slug',
                        'terms' => array($term),

                    );
                }
                //var_dump($result);
                $args['tax_query'] = array(
                    'relation' => 'AND',

                    $result
                );

            }


            /* when using namespace dont forget to add \ to WP_Query */
            $query = new \WP_Query($args);
            if ($query->have_posts()) :
                while ($query->have_posts()) {
                    $query->the_post();

                    get_template_part($template);

                }
                wp_reset_postdata();
            else :
                echo '<h2> No Result found</h2>';
            endif;

            die();

            ?>
        </div>

        <?php
    }
}