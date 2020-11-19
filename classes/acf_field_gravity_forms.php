<?php
/*** Gravity form field */
class acf_field_gravity_forms extends acf_field {
    /*
    *  __construct
    *  This function will setup the field type data
    */
    function __construct() {
        // vars
        $this->name = 'gravity_forms_field';
        $this->label = __('Gravity Forms');
        $this->category = __("Relational",'recycling'); // Basic, Content, Choice, etc
        $this->defaults = array(
        'allow_multiple' => 0,
        'allow_null' => 0
        );
        // do not delete!
        parent::__construct();
    }
  
    /*
    *  render_field_settings()
    *  Create extra settings for your field. These are visible when editing a field
    */
    function render_field_settings( $field ) {
        
        /*
        *  acf_render_field_setting
        */
        acf_render_field_setting( $field, 
            array(
                'label' => 'Allow Null?',
                'type'  =>  'radio',
                'name'  =>  'allow_null',
                'choices' =>  array(
                1 =>  __("Yes",'recycling'),
                0 =>  __("No",'recycling'),
                ),
                'layout'  =>  'horizontal'
            )
        );

        acf_render_field_setting( $field, 
            array(
                'label' => 'Allow Multiple?',
                'type'  =>  'radio',
                'name'  =>  'allow_multiple',
                'choices' =>  array(
                    1 =>  __("Yes",'recycling'),
                    0 =>  __("No",'recycling'),
                ),
                'layout'  =>  'horizontal'
            )
        );
    }
  
    /*
    *  render_field()
    *  Create the HTML interface for your field
    *  @param $field (array) the $field being rendered
    */
      
    function render_field( $field ) {

        /*
        *  Review the data of $field.
        *  This will show what data is available
        */
        $field = array_merge($this->defaults, $field);
        $choices = array();

        //Show notice if Gravity Forms is not activated
        if (class_exists('RGFormsModel')) 
        {
            $forms = RGFormsModel::get_forms(1);
        }   
        else 
        {
            echo "<font style='color:red;font-weight:bold;'>Warning: Gravity Forms is not installed or activated. This field does not function without Gravity Forms!</font>";
        }
        
        //Prevent undefined variable notice
        if(isset($forms)){
          foreach( $forms as $form ){
            $choices[ intval($form->id) ] = ucfirst($form->title);
          }
        }
        // override field settings and render
        $field['choices'] = $choices;
        $field['type']    = 'select';
            if ( $field['allow_multiple'] ) {
                $multiple = 'multiple="multiple" data-multiple="1"';
                echo "<input type=\"hidden\" name=\"{$field['name']}\">";
            }
            else $multiple = '';
        ?>
        <select id="<?php echo str_replace(array('[',']'), array('-',''), $field['name']);?>" name="<?php echo $field['name']; if( $field['allow_multiple'] ) echo "[]"; ?>"<?php echo $multiple; ?>>
            <?php
            if ( $field['allow_null'] ) 
                echo '<option value="">- Select -</option>';
                
            foreach ($field['choices'] as $key => $value){
                $selected = '';
                if ( (is_array($field['value']) && in_array($key, $field['value'])) || $field['value'] == $key )
                    $selected = ' selected="selected"';
            ?>
                <option value="<?php echo $key; ?>"<?php echo $selected;?>><?php echo $value; ?></option>
            <?php } ?>
        </select>
        <?php
    }

    /*
    *  format_value()
    *  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
    */
    function format_value( $value, $post_id, $field ) {
            
        //Return false if value is false, null or empty
        if( !$value || empty($value) ){
            return false;
        }
            
        //If there are multiple forms, construct and return an array of form objects
        if( is_array($value) && !empty($value) ) 
        {
            $form_objects = array();
            foreach($value as $k => $v)
            {
                $form = GFAPI::get_form( $v );
                //Add it if it's not an error object
                if( !is_wp_error($form) )
                {
                    $form_objects[$k] = $form;
                }
            }

            //Return false if the array is empty
            if( !empty($form_objects) )
            {
                return $form_objects;   
            }
            else
            {
                return false;
            }
        }
        else
        {
            $form = GFAPI::get_form(intval($value));
            //Return the form object if it's not an error object. Otherwise return false. 
            if( !is_wp_error($form) )
            {
                return $form;   
            }
            else
            {
                return false;
            }
        }
    }
}

