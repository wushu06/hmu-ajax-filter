<?php

namespace Inc\Filter;

class HmuCategoryFilter
{

    /*
     * get terms
     */
    public static function hmu_terms(  )
    {

        $taxonomies = get_taxonomies();
        $child_array = array();
        $all = array();
        foreach ( $taxonomies as $tax ) {
          //  echo '<p>' . $taxonomy . '</p>';


            $terms = get_terms( $tax, 'orderby=count&hide_empty=1' );
            if ( !empty( $terms ) && !is_wp_error( $terms ) ){
                    foreach ($terms as $term) {

                        $chilterms = get_terms( $tax, array( 'parent' => $term->term_id, 'orderby' => 'slug', 'hide_empty' => false ) );
                        if ( !empty( $chilterms ) && !is_wp_error( $chilterms) ) {
                            foreach ($chilterms as $childterm) {
                                $child_array[] = array(
                                    'childName' => $childterm->name
                                );
                            }

                        }
                        $all['parent'][] = array(
                            'ID' => $term->term_id,
                            'name' => $term->name,
                            'permalink' => get_term_link($term),
                            'slug' => $term->slug,
                            'count' => $term->count,
                            'child' =>  $child_array

                        );



                    }



                }
            }


        return $all;

    }



    /*
     * hmu filter output html
     */
    function hmu_category_html()
    {
        $taxonomies = $this->hmu_terms( 'product_cat' );

        ?>
            <div class="block--shop_filter">
                <div class="block--shop_filter_attributes" id="">

                    <?php
                    // Get product attributes
                    echo '<select class="hmu_filter_attributes">';
                    echo '<option>Porduct category</option>';
                   foreach ($taxonomies as $taxonomy):
                         ?>

                        <option
                            data-term-tax="<?php echo 'product_cat'; ?>"
                            data-term-slug="<?php echo $taxonomy['slug']; ?>"
                            data-term="<?php echo $taxonomy['ID']; ?>"
                            class="<?php echo 'product_cat'; ?>-term init-ajax"
                            href="<?php echo esc_url($taxonomy['permalink']) ;?>"
                        >
                            <?php echo $taxonomy['name']; ?>
                        </option>

                    <?php endforeach;

                    echo '</select>';

                    ?>
                </div>
            </div>
            <?php


    }
}


