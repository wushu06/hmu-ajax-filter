<?php

namespace Inc\Filter;

use Inc\Base\BaseController;

class HmuAllTaxonomies extends BaseController
{

    /*
     * get terms
     */
    public static function hmu_get_term_option()
    {
        $option_terms = array();
        if (get_option('hmu_ajax_filter')) {

            $option_terms = get_option('hmu_ajax_filter');
            // var_dump(get_option('hmu_ajax_filter'));
        }

        return $option_terms;

    }

    function hmu_post_terms_loop()
    {
        $term_list = array();
        @$obj = get_queried_object();
        @$obj_slug = $obj->slug;

        @$obj_tax = get_terms($obj->taxonomy, 'orderby=count');

        // $obj_term_id = $obj_term->term_id;
        @ $taxonomy = $obj->taxonomy; // this is the name of the taxonomy

        if (is_shop()) {
            $resultrgs = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'orderby' => array(
                    'ID' => 'DESC',
                ),

            );

        } else {
            $resultrgs = array(
                'posts_per_page' => -1,
                'post_type' => 'product',
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $obj->term_id,
                    )
                )
            );
        }

        $query = new \WP_Query($resultrgs);
        $childount_posts = $query->found_posts;
        $unique_terms = array();
        $unique_tax = array();
        while ($query->have_posts()) {
            $query->the_post();

            $post_taxonomies = get_post_taxonomies(get_the_ID());
            foreach ($post_taxonomies as $post_taxonomy) {
                // if( ! in_array( $post_taxonomy, $unique_tax ) ):
                // array_push($unique_tax, $post_taxonomy);
                $term_list = wp_get_post_terms(get_the_ID(), $post_taxonomy, array("fields" => "all"));

                foreach ($term_list as $term) :
                    $unique_terms[$post_taxonomy][] = $term;
                endforeach; //term_list
                // endif;

            }

        }

        $result = array();
        foreach ($unique_terms as $key => $unique_term) {
            foreach ($unique_term as $term) {
                $result [$term->taxonomy][] = $term->name;
            }

        }


        return $result;

    }


    /*
     * hmu filter output html
     */
    function hmu_taxonomy_option_to_html()
    {

        @$obj = get_queried_object();
        @$obj_slug = $obj->slug;
        // $obj_term_id = $obj_term->term_id;
        @$taxonomy = $obj->taxonomy;

        $taxonomies = $this->hmu_post_terms_loop();

        $option_terms = get_option('hmu_ajax_filter') ? get_option('hmu_ajax_filter') : array();
        //for tax
        $option_keys =  array_keys($option_terms);
        $option_values = array_values($option_terms);
        $new_option_values = array();
        foreach( $option_values as $subArray){
            foreach($subArray as $key=>$val){
                $new_option_values [] = $key;
            }
        }

        $checkbox = '0';
        $hide_parent = '0';
        $custom_css = '';

        if ($dashboard = get_option('hmu_dashboard')) {
            $checkbox = array_key_exists('use_checkbox', $dashboard)  ? $dashboard['use_checkbox'] : '0';
            $hide_parent = array_key_exists('hide_parent', $dashboard) ? $dashboard['hide_parent'] : '0' ;
            $custom_css = array_key_exists('custom-css', $dashboard) ? $dashboard['custom-css'] : '' ;
            //  var_dump($dashboard);
        }

        if($custom_css !==''){
            echo '<style>';
            echo $custom_css;
            echo '</style>';
        }


        if (!empty($taxonomies)): ?>
            <div class="block--shop_filter">
                <div class="block--shop_filter_attributes" id="">


                    <?php
                    foreach ($taxonomies as $key => $b) {
                        ?>
                        <?php
                        if($hide_parent == '0'):
                        if (in_array($key, $option_keys) ) :
                             echo '<div class="hmu-container"><div class="hmu-row"><div class="hmucol-md-10 hmucol-sm-10"><h2 class="text-left hmu-parent-title">' . $key . '</h2></div></div>';
                        endif; // check key in option
                        endif; // hide parent
                        foreach (array_count_values($b) as $child => $count) {
                            if (in_array(self::seoUrl($child), $new_option_values) ) :
                             ?>
                            <div class="hmu-container hmu-term-container">
                                <div class="hmu-row">

                                    <div class="hmucol-md-10 hmucol-sm-10 text-left">
                                        <label class="text-left hmu-label"
                                               for="<?php echo $child; ?>"><?php echo $child; ?></label>
                                        <span>(<?php echo $count; ?>)</span>

                                    </div>

                                    <div class=" <?php echo $checkbox == '1' ? 'hmu-hide-input' : 'hmucol-md-2 hmucol-sm-2' ?>">
                                        <input
                                                id="<?php echo $child; ?>"
                                                type="checkbox"
                                                value="<?php ?>"
                                                data-term-tax="<?php echo $key ?>"
                                                data-term-slug="<?php echo self::seoUrl($child); ?>"
                                                data-term="<?php echo $child; ?>"
                                                class="hmu-<?php echo self::seoUrl($key) ?> hmu_filter_attributes"
                                                data-archive-tax="<?php echo is_shop() ? '' : $taxonomy ?>"
                                                data-archive-term="<?php echo is_shop() ? '' : $obj_slug ?>"
                                        />
                                    </div>

                                </div>
                            </div>

                            <?php
                            endif; // check for child in option
                        }
                        echo '</div>';

                    }

                    ?>
                </div>
            </div>

        <?php
        else:
            echo 'No categories found';

        endif;

    }
}


