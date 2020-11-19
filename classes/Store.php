<?php
/**
 * Store Class
 */
class Store
{
    /**
     * The Post ID
     * @var integer
     */
    public $ID = 0;

    /**
     * Post data container
     * @var object
     */
    public $data ;

    /**
     * The Post Meta Fields
     * @var object
     */
    public $fields ;

    /**
     * Array to hold the response
     * @var null
     */
    private $response = array();

    /**
     * Construct a new Instance
     * @param int $post_id The program post ID
     */
    public function __construct( $post_id )
    {   
        global $wpdb;
        try
        {
            $post_id = (int) $post_id;            
            if(is_int($post_id) && $post_id > 0)
            {
                if ( null === ( $this->data = get_post( $post_id ) ) )
                {
                    $this->ID = 0;
                    $this->data = new stdClass();
                }            
                else
                {
                    $this->ID = $post_id;
                }
                if($this->data->post_type != 'store')
                {
                    throw new Exception('Error getting profile');
                }  
            } 
        }
        catch (Exception $exc)
        {
            return 0;
        }

        // if we don't have an ID at this point, do nothing further
        if( ! $this->ID ) return;

        // if we don't have a post at this point, do nothing further
        if( ! $this->data ) return;

        // Get all post meta for the post
        $fields = get_post_meta( $this->ID );

        // if we found nothing
        if( ! $fields ) {
            $this->fields = new stdClass();
        }
        else
        {
            $aux_fields = [];
            // loop through and clean up singleton arrays
            foreach( $fields as $k => $v ) {
                // need to grab the first item if it's a single value
                if( count( $v ) == 1 )
                    $aux_fields[ $k ] = maybe_unserialize( $v[0] );
                
                // or store them all if there are multiple
                else $aux_fields[ $k ] = $v;

                // Check if this meta is an ACF field
                $acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_excerpt,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $k , 'acf-field' ) );

                // If there is a ACF Fied it will replace the previous value of that $key.
                if ( $acf_fields ) {
                    $aux_fields[ $k ] = get_field($acf_fields[0]->post_excerpt,$this->ID);
                }

            }
            $this->fields = (object) $aux_fields;
        }

    }


    /**
     * upload Store Profile Picture
     * @return string photo source
     */
    public function uploadProfilePic( $data = array() )
    {
        $response = array();

        if(!function_exists('wp_handle_upload')){
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        $upload_dir       = wp_upload_dir();
        $post_id = $this->ID;

        $upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        // remove the part that we don't need from the provided image and decode it
        $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image_base64']));

        $filename         = 'profile_pic_' . $post_id . '.png';

        $hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;

        error_log($upload_path . $hashed_filename, $decoded);

        // @new
        $image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

        $response = array();

        // @new
        $file             = array();
        $file['error']    = '';
        $file['tmp_name'] = $upload_path . $hashed_filename;
        $file['name']     = $hashed_filename;
        $file['type']     = 'image/jpeg';
        $file['size']     = filesize( $upload_path . $hashed_filename );

        // upload file to server
        // @new use $file instead of $image_upload
        $file_return    = wp_handle_sideload( $file, array( 'test_form' => false ) );

        if ( !empty( $file_return['error'] ) ) {
            // Insert any error handling here
            // do something with the file info...
            $this->response['success'] = false;
            $this->response['title']   = "Ops! An error occurred";
            $this->response['message'] = $file_return['error'];
        } else {

            $filename = $file_return['file'];
            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $upload_dir['url'] . '/' . basename($filename)
            );



            $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $post_id, $attach_id );
            $image_url = wp_get_attachment_image_src($attach_id,'thumbnail');
            // Perform any actions here based in the above results
            if($file_return && $attach_id && $attach_data )
            {
                // do something with the file info...
                $this->response['success'] = true;
                $this->response['title']   = "Great!";
                $this->response['message'] = "Thanks for uploading your profile pic!";
                $this->response['imageUrl'] = $image_url[0];
            }
            else
            {
                // do something with the file info...
                $this->response['success'] = false;
                $this->response['title']   = "Ops! An error occurred";
                $this->response['message'] = "Something wrong";

            }
        }

        return $this->response;
       
    }

    /**
     * upload Store Profile Banner Picture
     * @return string photo source
     */
    public function uploadProfileBannerPic( $data = array() )
    {
        $response = array();

        if(!function_exists('wp_handle_upload')){
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        $upload_dir       = wp_upload_dir();
        $post_id = $this->ID;

        $upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        // remove the part that we don't need from the provided image and decode it
        $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image_base64']));

        $filename         = 'profile_pic_' . $post_id . '.png';

        $hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;

        error_log($upload_path . $hashed_filename, $decoded);

        // @new
        $image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

        $response = array();

        // @new
        $file             = array();
        $file['error']    = '';
        $file['tmp_name'] = $upload_path . $hashed_filename;
        $file['name']     = $hashed_filename;
        $file['type']     = 'image/jpeg';
        $file['size']     = filesize( $upload_path . $hashed_filename );

        // upload file to server
        // @new use $file instead of $image_upload
        $file_return    = wp_handle_sideload( $file, array( 'test_form' => false ) );

        if ( !empty( $file_return['error'] ) ) {
            // Insert any error handling here
            // do something with the file info...
            $this->response['success'] = false;
            $this->response['title']   = "Ops! An error occurred";
            $this->response['message'] = $file_return['error'];
        } else {

            $filename = $file_return['file'];
            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $upload_dir['url'] . '/' . basename($filename)
            );



            $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            update_post_meta( $post_id, 'banner_photo', $attach_id );    
            $image_url = wp_get_attachment_image_src($attach_id,'full');
            // Perform any actions here based in the above results
            if($file_return && $attach_id && $attach_data )
            {
                // do something with the file info...
                $this->response['success'] = true;
                $this->response['title']   = "Great!";
                $this->response['message'] = "Thanks for uploading your banner pic!";
                $this->response['imageUrl'] = $image_url[0];
            }
            else
            {
                // do something with the file info...
                $this->response['success'] = false;
                $this->response['title']   = "Ops! An error occurred";
                $this->response['message'] = "Something wrong";
            }
        }

        return $this->response;
       
    }


    /**
     * Get Store profile image URL
     * @return string photo source
     */
    public function getProfilePicURL()
    {
        if( $this->ID )        
        {
            $profile_pic_url = !empty(get_the_post_thumbnail_url($this->ID ,'thumbnail')) ? get_the_post_thumbnail_url($this->ID ,'thumbnail') : get_template_directory_uri().'/images/default-user-image.png';
        }      
        return $profile_pic_url;
    }

     /**
     * Get Store profile banner image URL
     * @return string photo source
     */
    public function getProfileBannerPicURL()
    {

        if( $this->ID )       
        {
            $profile_banner_url = !empty(wp_get_attachment_image_src(get_field('banner_photo',$this->ID),'full')[0]) ? wp_get_attachment_image_src(get_field('banner_photo',$this->ID),'full')[0] : esc_url(get_template_directory_uri().'/images/default_banner.jpg');
        }      
        return $profile_banner_url;
    }

    /**
     * Save Store information details
     * @param  int $store_id 
     * @return void
     */
    public function saveStoreInformationDetails($data = array()) 
    {
        $post_title = '';
        $post_content = '';
        // save basic info
        if ( !empty($data['userdata']) )
        {
            wp_update_user( $data['userdata'] );
        }

        // save storedata info
        if ( !empty($data['storedata']) )
        {   
            // Adding Display checkboxes to the record in case is not set
            $data['storedata']['store_display_status'] = !isset($data['storedata']['store_display_status']) ? 0 : $data['storedata']['store_display_status'];
            $data['storedata']['special_offer_display_status'] = !isset($data['storedata']['special_offer_display_status']) ? 0 : $data['storedata']['special_offer_display_status'];

            foreach($data['storedata'] as $index => $value)
            {
                if ( $index == 'title' )
                {
                    $post_title = filter_var( $value,FILTER_SANITIZE_STRING ); continue;
                }
                if ( $index == 'description' )
                {
                    $post_content = filter_var( $value,FILTER_SANITIZE_STRING ); continue;
                }
                update_field($this->getFieldKey($index), filter_var( $value,FILTER_SANITIZE_STRING ), $this->ID);
                //update_post_meta($this->id, $index, filter_var( $value,FILTER_SANITIZE_STRING ));
            }
            // Save Google Map attributes
            $address = $this->address;
            if ( trim($address) != false )
            {
                // error_log("NOT EMPTY ADDRESS" . $this->address);
                $field_name = $this->getFieldKey('google_map',$this->id);
                $full_address = sprintf('%s', $this->address );
                $this->getGoogleMapsACFbyAddress($this->id,$full_address,$field_name);
            }
            else
            {
                // error_log("EMPTY ADDRESS " . $this->address . " | " . $this->google_map);
                $this->google_map = "";
            }
        }

        // // Save Open Close Days and Hours for Gym Profile
        // if ( !empty($data['listinghours']) )
        // {
        //     foreach($data['listinghours'] as $index => $value)
        //     {
        //         $listing_hours[$index] = filter_var( $value,FILTER_SANITIZE_STRING );
        //     }

        //     for ($i = 0; $i < 7 ; $i++) 
        //     {                   
        //         $value = ( isset($listing_hours['open_close_hours_'.$i.'_is_open']) == "on" ) ? 1 : 0;
        //         update_post_meta($this->id, 'open_close_hours_'.$i.'_day', filter_var( $i,FILTER_SANITIZE_STRING ));
        //         update_post_meta($this->id, 'open_close_hours_'.$i.'_is_open', filter_var( $value,FILTER_SANITIZE_STRING ));
        //         update_post_meta($this->id, 'open_close_hours_'.$i.'_open_hours', filter_var( $listing_hours['open_close_hours_'.$i.'_open_hours'],FILTER_SANITIZE_STRING ));
        //         update_post_meta($this->id, 'open_close_hours_'.$i.'_close_hours', filter_var( $listing_hours['open_close_hours_'.$i.'_close_hours'],FILTER_SANITIZE_STRING ));
        //     }
        // }

        // // Save List Key Feature Tags
        // if ( !empty($data['listingkeyfeature']) )
        // {
        //     $taxonomy       = "key_feature";
        //     $terms          = array_keys($data['listingkeyfeature']);

        //     // Delete all taxonomie asociate to that post
        //     $term_taxonomy_ids = wp_set_object_terms( $this->id, null, $taxonomy );
        //     // Asign the new ones
        //     $term_taxonomy_ids = wp_set_object_terms( $this->id, $terms, $taxonomy );
    
        //     if ( is_wp_error($term_taxonomy_ids) ){
        //         // There was an error somewhere and the terms couldn't be set.
        //         $this->response['success'] = false;
        //         $this->response['title']   = "Ops! An error occurred";
        //         $this->response['message'] = $term_taxonomy_ids->get_error_message();
        //         return $this->response;
                
        //     } else {
        //         // Success! The post's categories were set.
        //         error_log("<h1>Thanks</h1>");
        //     }
        // }
        $post_data = array(
            'ID' => intval($this->ID),
            'post_title' => $post_title,                
            'post_content' => $post_content,                
        );

        // Update the post into the database
        wp_update_post( $post_data );

        $this->response['success'] = true;
        $this->response['title']   = "Great!";
        $this->response['message'] = "Your updates have been saved";
        return $this->response;
    }

    /**
     * Function to retrieve google map ACF parameters value
     */
    public function getGoogleMapsACFbyAddress($post_id, $full_address ,$google_acf_id) {   

        $address_one_line = preg_replace('/ *(\r\n|\r|\n)+ */', " ", $full_address);

        $coords = $this->_getGoogleMapsACFlatlng( $address_one_line );

        if ( $coords ) {
            $location = array(
                'address' => '',
                'lat' => '',
                'lng' => ''
            );

            $location['address'] = $address_one_line;
            $location['lat'] = $coords['lat'];
            $location['lng'] = $coords['lng'];

            $result = update_field( $google_acf_id, $location, $post_id );


            if ( $result ) {
                // Save lat/long as separate meta keys too.
                update_post_meta( $post_id, 'latitude', $location['lat'] );
                update_post_meta( $post_id, 'longitude', $location['lng'] );
                update_post_meta( $post_id, 'city', $coords['city'] );
                update_post_meta( $post_id, 'state', $coords['state'] );
                update_post_meta( $post_id, 'state_short', $coords['state_short'] );
                update_post_meta( $post_id, 'country', $coords['country'] );
                update_post_meta( $post_id, 'country_short', $coords['country_short'] );
                update_post_meta( $post_id, 'postcode', $coords['postcode'] );
                update_post_meta( $post_id, 'address', $address_one_line );
            }
            return true;

        }

        return false;
    }

    /**
     * Function to retrieve lat lng using google geocode library
     */
    public function _getGoogleMapsACFlatlng($address){
        // http://stackoverflow.com/a/8633623/470480
        $GOOGLE_API_KEY = get_field('google_map_api_key','options');
        $address = urlencode($address); // Spaces as + signs
        $request = wp_remote_get("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=".  $GOOGLE_API_KEY);
        $json = wp_remote_retrieve_body( $request );
        
        if ( !$json ) {
            error_log('Google Maps returned an empty response');
            return false;
        }
        $data = json_decode($json);
        if ( !$data ) {
            error_log('<h2>ERROR! Google Maps returned an invalid response, expected JSON data:</h2>');
            echo esc_html(print_r($json, true));
            exit;
        }
        if ( isset($data->{'error_message'}) ) {
            error_log('<h2>ERROR! Google Maps API returned an error:</h2>');
            error_log('<strong>'. esc_html($data->{'status'}) .'</strong> ' . esc_html($data->{'error_message'}) .'<br>');
            exit;
        }
        if ( empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}) || empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}) ) {
            error_log('<h2>ERROR! Latitude/Longitude could not be found:</h2>');
            echo esc_html(print_r($data, true));
            exit;
        }

        if(!empty($data->{'results'}[0]->{'address_components'})){
            foreach($data->{'results'}[0]->{'address_components'} as $add_comp){
                if(in_array('locality', $add_comp->types)){
                    $city = $add_comp->long_name;
                }
                elseif (in_array('administrative_area_level_1', $add_comp->types)) {
                    $state = $add_comp->long_name;
                    $state_short = $add_comp->short_name;
                }
                elseif (in_array('country', $add_comp->types)) {
                    $country = $add_comp->long_name;
                    $country_short = $add_comp->short_name;
                }elseif (in_array('postal_code', $add_comp->types)) {
                    $postcode = $add_comp->long_name;
                }
            }
        }

        $lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $lng = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        // Value can be negative, so check for specifically 0.
        if ( floatval( $lat ) === 0 || floatval( $lng ) === 0 ) {
            error_log('<h2>ERROR! Latitude/Longitude is invalid (exactly zero):</h2>');
            error_log(var_export('Latitude:', $lat,true));
            error_log(var_export('Longitude:', $lng,true));
            error_log(var_export('Result:', $data->{'results'}[0],true));
            exit;
        }
        return array( 'lat' => $lat, 'lng' => $lng, 'city' => $city, 'state' => $state, 'state_short' => $state_short, 'country' => $country, 'country_short' => $country_short, 'postcode' => $postcode );

    }


    /**
     * Simulate getField ACF Function
     * @param  string $name Name of the ACF Field
     * @return (mixed)      Value of the ACF Field
     */
    public function getField( $name )
    {
        return (isset($this->fields->$name) ? $this->fields->$name : false);
    }

    /**
     * Get all options fron ACF Select field
     * @param  string $name         Name of the Select ACF Field
     * @param  int  $repeater_id    ID of the parent field 
     * @return string               The Key of the field
     */
    public function getSelectOptions( $select_name, $repeater_id = null)
    {
        if ( intval($repeater_id) > 0 )
        {
            $field = get_field_object( $this->getSubFieldKey($select_name, $repeater_id) ); 
        }
        else
        {
            $field = get_field_object( $this->getFieldKey($select_name) );
        }
        return $choices = $field['choices'];
    }

    /**
     * Get the ACF Field Key
     * @param  string $name Name of the ACF Field
     * @return string       Key of the ACF Field
     */
    public function getFieldKey( $name )
    {
        global $wpdb;

        // Check if this meta is an ACF field
        $acf_aux_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_excerpt,post_name,post_content FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $name , 'acf-field' ) );


        $acf_fields = [];
        // Exclude Tab Type ACF Field form the key
        if ( $acf_aux_fields) {
            foreach ($acf_aux_fields as $key => $field) {
                $field_object = maybe_unserialize( $field->post_content ) ;
                if ( $field_object['type'] == 'tab' ) continue;
                $acf_fields[] = $field;
            }
        }

        // If there is a ACF Fied it will replace the previous value of that $key.
        // get all fields with that name.
        switch ( count( $acf_fields ) ) {
            case 0: // no such field
                return false;
            case 1: // just one result.
                return $acf_fields[0]->post_name;
        }
    
        // result is ambiguous
        // get IDs of all field groups for this post
        $field_groups_ids = array();
        $field_groups = acf_get_field_groups( array(
            'post_id' => $this->ID,
        ) );

        foreach ( $field_groups as $field_group )
            $field_groups_ids[] = $field_group['ID'];

        // Check if field is part of one of the field groups
        // Return the first one.
        foreach ( $acf_fields as $acf_field ) {
            if ( in_array($acf_field->post_parent,$field_groups_ids) )
                return $acf_field->post_name;
        }

        return false;
    }

    /**
     * Get the ACF Field Key inside a repeater mostly for the select
     * @param  string $name         Name of the ACF Field
     * @param  int  $repeater_id    ID of the parent field 
     * @return string               The Key of the field
     */
    public function getSubFieldKey( $name, $repeater_id )
    {
        global $wpdb;

        // Check if this meta is an ACF field
        $acf_aux_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_excerpt,post_name,post_content FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s AND post_parent=%d" , $name , 'acf-field', $repeater_id ) );
        
        $acf_fields = [];
        // Exclude Tab Type ACF Field form the key
        if ( $acf_aux_fields) {
            foreach ($acf_aux_fields as $key => $field) {
                $field_object = maybe_unserialize( $field->post_content ) ;
                if ( $field_object['type'] == 'tab' ) continue;
                $acf_fields[] = $field;
            }
        }

        // If there is a ACF Fied it will replace the previous value of that $key.
        // get all fields with that name.
        switch ( count( $acf_fields ) ) {
            case 0: // no such field
                return false;
            case 1: // just one result.
                return $acf_fields[0]->post_name;
        }

        return false;
    }


    /**
     * Magic Methods to get
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function __get($name)
    {
        if ( $name == "title" ) 
        {
            return isset($this->data->post_title) ? $this->data->post_title :'';
        } 
        if ( $name == "content" ) 
        {
            return isset($this->data->post_content) ? $this->data->post_content :'';
        }   
        if ( !isset($this->data->$name) ) 
        {
            return "";
        }      
        else return $this->data->$name;
    }

    /**
     * Magic Method to set values
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function __set($name,$value)
    {
        update_post_meta($this->ID,$name,$value);
    }

    /**
     * Destroy the instance of the class
     */
    public function __destruct() {
        $this->ID = 0;
        $this->data = new stdClass();  
    }
    
}