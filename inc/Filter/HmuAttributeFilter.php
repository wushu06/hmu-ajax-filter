<?php

namespace Inc\Filter;

class HmuAttributeFilter
{

    /*
     * hmu filter output html
     */
    function hmu_filter_html()
    {
        global $product;

        // Get product attributes
        @$attributes = $product->get_attributes();

        if (!$attributes) {
            return;
        }


        foreach ($attributes as $attribute):
            $name = $attribute['name'];
            $clean_name = str_replace(['pa_', '-'], ' ', $name);
            echo '<input type="hidden" data-attr=' . $name . ' class="hidden-attributes">';
            $clean_attr = str_replace('pa_', '', $name);
            ?>
            <div class="block--shop_filter">
                <div class="block--shop_filter_attributes" id="attributesSilder<?php echo $clean_attr; ?>">

                    <?php
                    // Get product attributes
                    $terms = get_terms($name);
                    echo '<select class="hmu_filter_attributes">';
                    echo '<option>'.$clean_name.'</option>';
                    foreach ($terms as $term) { ?>

                            <option
                                    data-term-tax="<?php echo $name; ?>"
                                    data-term-slug="<?php echo $term->slug ?>"
                                    data-term="<?php echo $term->term_id ?>"
                                    class="<?php echo $clean_attr; ?>-term init-ajax"
                                    href="<?php echo esc_url(get_term_link($term)) ?>"
                            >
                               <?php echo $term->name ?>
                            </option>


                    <?php }
                    echo '</select>';

                    ?>
                </div>
            </div>
        <?php endforeach;

    }
}


