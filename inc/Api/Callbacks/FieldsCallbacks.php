<?php 

namespace Inc\Api\Callbacks; 

use \Inc\Base\BaseController;


class FieldsCallbacks extends BaseController {

    public $cron_name;

    public function sanitizeCallback2( $input )
    {
        $output = array();
      /*  $r = array();
        if(!empty($_FILES["hmu_cron"]["tmp_name"]))
        {


                $newFilename =  time().'_'.$_FILES["hmu_cron"]["name"];
                $location = $this->plugin_path.'Upload/'. $newFilename;
                move_uploaded_file($_FILES["hmu_cron"]["tmp_name"], $location);
                $r ['cron_upload'] =  $location;



        }*/

        if(isset($_POST['btnSubmit'])):
            $output = get_option('hmu_cron');
            if(!empty($_FILES["hmu_cron"]["tmp_name"]))
            {
                $newFilename =  time().'_'.$_FILES["hmu_cron"]["name"];
                $location = $this->plugin_url.'Upload/'. $newFilename;
              //  move_uploaded_file($_FILES["hmu_cron"]["tmp_name"], $location);
                $movefile = wp_handle_upload($_FILES["hmu_cron"], array('test_form' => FALSE));



            }



                if (empty($output)) {
                    $output['1'] = $input;
                    $output['1']['cron_upload'] = $movefile['url'];

                } else {

                    foreach ($output as $key => $value) {
                        $count = count($output);
                        if ($key < $count) {
                            $output[$key] = $value;
                            $output[$key]['cron_upload'] =$movefile['url'];

                        } else {
                            $output[$key + 1] = $input;
                            $output[$key + 1]['cron_upload'] =$movefile['url'];

                        }


                    }
                }

        endif;


        return $output;

    }

    public function sanitizeCallback( $options )
	{

    }

	public function adminSectionManager()
	{
		echo 'Import Prices and Users';
    }
    public function dashboardSectionManager ()
    {
        echo 'Dashboard Control';
    }
    public function cronSectionManager()
    {
    }

    public function hmuCheckboxLabel($args)
    {
        
                $name = $args['label_for'];
                $classes = $args['class'];
                $option_name = $args['option_name'];
                $checkbox = get_option( $option_name );
                $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

                
                echo '<div id="toggles">
                        <input id="hmuCheckbox" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked": "") . '>
                        <label for="hmuCheckbox" class="checkbox-label" data-off="off" data-on="on">
                        </label>';
    }
    public function hmuHideParents($args)
    {

                $name = $args['label_for'];
                $classes = $args['class'];
                $option_name = $args['option_name'];
                $checkbox = get_option( $option_name );
                $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;


                echo '<div id="toggles">
                        <input id="hmuHideParent" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked": "") . '>
                        <label for="hmuHideParent" class="checkbox-label" data-off="off" data-on="on">
                        </label>';
    }


    public function hmuWrapperID($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="id attribute wrapping the products list">';
    }

    public function hmuCustomClass($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Custom class">';
    }
}
