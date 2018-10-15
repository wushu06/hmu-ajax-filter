<?php

namespace Inc\Filter;

class HmuAllTaxonomies
{

    /*
     * get terms
     */
    public static function hmu_get_term_option()
    {
        $option_terms = array();
        if(  get_option('hmu_woo_filter') ) {

            $option_terms = get_option('hmu_woo_filter');
           // var_dump(get_option('hmu_woo_filter'));
        }

        return $option_terms;


    }



    /*
     * hmu filter output html
     */
    function hmu_taxonomy_option_to_html()
    {
        $taxonomies = $this->hmu_get_term_option();
        if(!empty($taxonomies)):
        ?>
        <div class="block--shop_filter container">
            <div class="block--shop_filter_attributes row" id="">

                <?php
                // Get product attributes

                foreach ($taxonomies as $key=>$taxonomy):
                    echo '<div class="hmu-container"><div class="hmu-row"><div class="col-md-12"><h2 class="text-left">'.$key.'</h2></div></div></div>';
                foreach ($taxonomy as $child=>$tax):

                   // var_dump(get_term_by( 'id', $tax, $key )->count);
                    ?>
                    <div class="hmu-container">
                    <div class="hmu-row">
                        <div class="hmucol-md-10 hmucol-sm-10 text-left" >
                        <label class="text-left" for=""><?php echo $child; ?></label>
                        <span>(<?php echo get_term_by( 'id', $tax, $key )->count ; ?>)</span>
                        </div>
                        <div class="hmucol-md-2 hmucol-sm-2">
                            <input
                                    type="checkbox"
                                    value="<?php ?>"
                                    data-term-tax="<?php echo $key  ?>"
                                    data-term-slug="<?php echo $child; ?>"
                                    data-term="<?php echo $child; ?>"
                                    class=" hmu_filter_attributes"
                                    <?php echo $taxonomy == '1' ? 'checked' : '' ?>
                            />
                        </div>

                    </div>
                    </div>


                <?php
                endforeach;

                endforeach;



                ?>
            </div>
        </div>
        <?php
        else:
            echo '<p>No category has been selected, please refer to settings page <a href="'.esc_attr(admin_url("admin.php?page=hmu_woo_filter")).'">here</a></p>';
            endif;

    }
}


