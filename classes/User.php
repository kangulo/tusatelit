<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

class User extends WP_User
{  

    /**
     * User ID
     * @var integer
     */
    public $ID = 0;

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
     * User Constructor
     * @param integer $user_id User id
     */
    public function __construct( $user_id = NULL )
    {   
        global $wpdb;
        add_action( 'wp_login', array($this, 'increaseUserLoginCounter'), 10, 1 );
        add_filter( 'gform_after_submission_1', array($this, 'registerUser') , 10, 2 );
        //add_filter( 'gform_validation_1', array($this, 'registerValidation')  );


        if( $user_id == NULL )
        {
            $this->ID = get_current_user_id();
        }
        else
        {
            $this->ID = intval( $user_id );
        }
        
        if( $this->ID == 0 )
        {
            wp_set_current_user(0);    
            return;
        }

        // Get all post meta for the post
        $fields = get_user_meta( $this->ID );

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


        parent::__construct($this->ID);

    }

    /**
     * Login
     *
     * @param string $username email or username
     * @param string $password password
     * @return Array 
     */
    public function login( $email = null, $password = null )
    {

        global $wpdb;
        /********************************************************************/       
        if ($this->isLoggedIn()) return;

        if(is_email($email))
        {
            $username = $wpdb->get_var("SELECT user_login FROM wp_users WHERE user_email = '".$email."'");
            $user = get_user_by( 'email', $email );
        }
        else
        {
            $username = $email;                  
            $user = get_user_by( 'login', $username );
        }

        if ( !empty($user) )
        {

            // Get all user meta data for $id & Filter out empty meta data
            $data = array_filter(array_map( function( $a ){ return $a[0]; }, get_user_meta( $user->ID ) ) );

            if( !isset($data['status']) || $data['status'] == "active" )
            {
                if ( isset($data['email_valid']) && $data['email_valid'] == 0 )
                {
                    // Send Notification to that user
                    $notificationManager = new Notification();
                    $msg = ['sender_id' => '0',
                        'recipient_id' => $user->ID,
                        'reference' => "USER_LOGIN",                    
                        'type' => "NOTIFICATION",                    
                        'title' => "Friendly Reminder",                    
                        'message' => "Your email is not confirmed yet."];
                    $notificationManager->addMessage($msg);
                }

                $creds = array(
                    'user_login'    => $username,
                    'user_password' => $password,
                    'remember'      => true
                );

                $user_wp = wp_signon( $creds, true );

                if ( !is_wp_error($user_wp) )
                {
                    $this->ID = $user_wp->ID;
                    parent::__construct($this->ID);

                    wp_set_current_user( $this->ID, $username );
                    wp_set_auth_cookie( $this->ID, true, false );
                    do_action( 'wp_login', $username );

                    $this->response['success'] = true;
                    $this->response['title']   = "Welcome back,";
                    $this->response['message'] = "<h1>". $this->first_name . ' ' . $this->last_name . "</h1><br>successful, redirecting...";
                    $this->response['redirect_url'] = get_permalink(2);
                    $this->response['autoclose'] = 1;               
                }
                else
                {
                    $this->response['success'] = false;
                    $this->response['title']   = "Ops! Some Error Ocurred";
                    $this->response['message'] = $user_wp->get_error_message();
                    $this->response['autoclose'] = 0; 
                    
                }
            }
            elseif( $data['status'] == "inactive" )
            {
                $this->response['success'] = false;
                $this->response['title']   = "Ops! Some Error Ocurred";
                $this->response['message'] = "Your account is inactive temporarily.<br><br> Please contact Responder Corp Support ";
                $this->response['autoclose'] = 0; 
            }            
        }
        else
        {
            $this->response['success'] = false;
            $this->response['title']   = "Ops! Some Error Ocurred";
            $this->response['message'] = "Username or Password are invalid";          
        }
        return $this->response;
    }

    /**
     * Sign Up User
     */
    public function registerUser( $entry, $form )
    {
        error_log("Paso Por Aqui ". current_time('mysql'));
        $userdata = array();
        
        // Generate a random confirmation code
        $email_confirmation_code = chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90));

        /* User Info */
        $userdata['first_name']             = sanitize_text_field( rgar( $entry, '2' )  );
        $userdata['last_name']              = sanitize_text_field( rgar( $entry, '3' ) );
        $userdata['email']                  = sanitize_email( rgar( $entry, '4' ) );    
        $userdata['user_pass']              = rgar( $entry, '6' );      

        /* Woocommerce Details */
        $userdata['billing_first_name'] = sanitize_text_field( rgar( $entry, '2' ) );
        $userdata['billing_last_name']  = sanitize_text_field( rgar( $entry, '3' ) );
        $userdata['billing_email']      = sanitize_text_field( rgar( $entry, '4' ) ); 

        /* Additionals fields */
        $userdata['signup_date']        = date("Y-m-d h:i:s a");
        $userdata['email_valid']        = 0;
        $userdata['email_conf_code']    = $email_confirmation_code;
        $userdata['status']             = 'active';
        $userdata['user_type']          = sanitize_text_field( rgar( $entry, '26' ) );

        // Check if the user exists
        if( null == username_exists( $userdata['email'] ) ) 
        {
            // Create WP User
            $user_id = wp_create_user( $userdata['email'], $userdata['user_pass'], $userdata['email'] );

            if ( ! is_wp_error( $user_id ) && is_int( $user_id ) ) 
            {
                $email = $userdata['email'];
                $password = $userdata['user_pass'];
                unset($userdata['email']);
                unset($userdata['user_pass']);
                $roles = array();

                //Insert/Update all user metadata;
                foreach ($userdata as $key => $value) {                
                    update_user_meta($user_id, $key, $value);
                }
                
                wp_update_user(array('ID'=>$user_id, 'role'=> $userdata['user_type'] ));

                // If the user has satelite as a role
                if ($userdata['user_type'] == "satelite")
                {
                    // Create a Store Custom Post Type
                    $post = array
                    (
                        "post_author"       => $user_id,
                        "post_title"        => $userdata['first_name'] . " " . $userdata['last_name'],
                        "post_status"       => "publish",
                        "post_type"         => 'stores'
                    );

                    //Create post
                    $post_id = wp_insert_post( $post, $wp_error );
                    if(is_wp_error($post_id)){
                      //there was an error in the post insertion, 
                      echo $post_id->get_error_message();
                      exit();
                    }

                    // Double check saving post_id in user meta data
                    update_user_meta( $user_id, 'store_id', $post_id);

                    // Save new post listing metadata
                    $userdata['user_id'] =  $user_id;

                }


                                 
                // ready to sign in the user into the system
                $creds = array(
                    'user_login'    => $email,
                    'user_password' => $password,
                    'remember'      => true
                );

                $user_wp = wp_signon( $creds, true );
                
                if ( !is_wp_error($user_wp) )
                {
                    $this->ID = $user_wp->ID;
                    parent::__construct($this->ID);     

                    $roles[] = 'approved';

                    if (!empty($roles) && is_array($roles)){
                        foreach ($roles as $role) {
                            $this->add_role($role);
                        }
                    }
                    //error_log(var_export($user_wp->roles,true));
                    
                    wp_set_current_user($this->ID, $email );
                    wp_set_auth_cookie($this->ID, true, false );
                    do_action( 'wp_login', $email, $this );

                    if ( $userdata['email_valid'] == 1) 
                    {
                        // Send confirmation email to the user                    
                        $mail = new Mail($email);

                        $mail->setMessageFromTemplate("CONFIRMATION_EMAIL",
                            ["FIRST_NAME" =>$userdata['first_name'],
                             "LAST_NAME" =>$userdata['last_name'], 
                             "SITE_URL" => site_url()]
                        );                
                        $mail->send();  


                        // Send Notification to that user
                        $notificationManager = new Notification();
                        $msg = ['sender_id' => '0',
                            'recipient_id' => $this->ID,
                            'reference' => "USER_SIGNUP",                    
                            'type' => "INFO", // INFO, WARNING, ERROR, SUCCESS                    
                            'title' => "Welcome",                    
                            'message' => "Thank You for Signing Up!"];
                        $notificationManager->addMessage($msg);

                        $msg = ['sender_id' => '0',
                            'recipient_id' => $this->ID,
                            'reference' => "USER_SIGNUP",                    
                            'type' => "NOTIFICATION",                    
                            'title' => "Complete Your Porfile",                    
                            'message' => "Please complete your company Profile."];
                        $notificationManager->addMessage($msg);
                    }
                    else
                    {
                        // Send confirmation email to the user
                        $url_confirmation_code = site_url()."/email-confirmation/".$email_confirmation_code."/".$email;
                        $mail = new Mail($email);

                        $mail->setMessageFromTemplate("CONFIRMATION_EMAIL",
                            ["FIRST_NAME" =>$userdata['first_name'],
                             "LAST_NAME" =>$userdata['last_name'], 
                             "URL_EMAIL_CONFIRMATION" => $url_confirmation_code,
                             "SITE_URL" => site_url()]
                        );                
                        $mail->send();  


                        // Send Notification to that user
                        $notificationManager = new Notification();
                        $msg = ['sender_id' => '0',
                            'recipient_id' => $this->ID,
                            'reference' => "USER_SIGNUP",                    
                            'type' => "NOTIFICATION",                    
                            'title' => "Verify Email",                    
                            'message' => "Please verify your email to ensure you get the full benefits of the Responder Portal."];
                        $notificationManager->addMessage($msg);

                        $msg = ['sender_id' => '0',
                            'recipient_id' => $this->ID,
                            'reference' => "USER_SIGNUP",                    
                            'type' => "NOTIFICATION",                    
                            'title' => "Complete Your Porfile",                    
                            'message' => "Please complete your company Profile."];
                        $notificationManager->addMessage($msg);
                    }

                    // Send email to ADministrator of new User register and 
                    $admin_email = get_option( 'admin_email' );
                    $mail = new Mail($admin_email);

                    $subject = "NEW USER REGISTERED";
                    $message = "A new user has been registered in Responder Portal Site.<br><ul><li>First Name: " .$userdata['first_name'] . "</li><li>Last Name: " . $userdata['last_name'] . "</li><li>Email: " . $email ."</li><li>User Type: " . $userdata['user_type'] . "</li><li>Browser: " . $_SERVER['HTTP_USER_AGENT'] . "</li><li>Ip: " . $_SERVER['REMOTE_ADDR']. "</li></ul>";

                    $mail->setMessageFromTemplate("REGULAR_EMAIL",
                        ["FIRST_NAME" => "Dear",
                         "LAST_NAME" => "Admin", 
                         "MESSAGE" => $message,
                         "EMAIL_TITLE" => $subject,
                         "SITE_URL" => site_url()]
                    );        
                    $mail->setSubject($subject);        
                    $mail->send(); 

                    $this->response['success'] = true;
                    $this->response['title']   = "Successfully Registered";
                    $this->response['message'] = "Welcome";
                    $this->response['autoclose'] = 0; 
                }
                else
                {
                    $this->response['success'] = false;
                    $this->response['title']   = "Ops! Some Error Ocurred";
                    $this->response['message'] = $user_wp->get_error_message();
                    $this->response['autoclose'] = 0; 
                }                
            }
            else
            {
                $this->response['success'] = false;
                $this->response['title']   = "Ops! Some Error Ocurred";
                $this->response['message'] = $user_id->get_error_message();
                $this->response['autoclose'] = 0; 
                
            }
        }
        else 
        {
            $this->response['success'] = false;
            $this->response['title']   = "Ops! This user is already registered";
            $this->response['message'] = $userdata['email'];
            $this->response['autoclose'] = 0; 
        }
        return $this->response;
    }

    public function increaseUserLoginCounter()
    {
        $new_value = intval(get_user_meta( $this->ID, 'login_count', true )) + 1;
        update_user_meta( $this->ID, 'login_count', $new_value  );
        update_user_meta( $this->ID, 'last_login_date', date('Y-m-d H:i:s'));
        update_user_meta( $this->ID, 'last_login_time', time());
    }

   
    public function registerValidation( $validation_result )
    {
        $form = $validation_result['form'];

        // 3 - Get the current page being validated
        $current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;

        if ( $current_page == 1)
        {
            // Check if Email already exists.
            if ( email_exists( rgpost( 'input_4' ) ) ) 
            {
                // set the form validation to false
                $validation_result['is_valid'] = false;
         
                //finding Field with ID of 1 and marking it as failed validation
                foreach( $form['fields'] as &$field ) {
         
                    //NOTE: replace 1 with the field you would like to validate
                    if ( $field->id == '4' ) {
                        $field->failed_validation = true;
                        $field->validation_message = 'Oops, this email address is already registered at Responder Portal, <a href="'. wp_lostpassword_url().'">Forget Password</a>';
                        break;
                    }
                }
            }

        }
        //error_log("Before Entro a validadr rgpost". rgpost( 'input_16_1' ));
        if ( $current_page == 3 )
        {
            // Check if Company Not in List.
            if ( rgpost( 'input_16_1' ) == "Create company" )
            {
                // Check if Email already exists.
                if (  rgpost( 'input_5' ) == "" ) 
                {
                    // set the form validation to false
                    $validation_result['is_valid'] = false;
             
                    //finding Field with ID of 1 and marking it as failed validation
                    foreach( $form['fields'] as &$field ) {
             
                        //NOTE: replace 1 with the field you would like to validate
                        if ( $field->id == '5' ) {
                            $field->failed_validation = true;
                            $field->validation_message = 'Please provide a Company Name';
                            break;
                        }
                    }
                }
            }
            else
            {
                // Check if Email already exists.
                if (  rgpost( 'input_10' ) == "" ) 
                {
                    // set the form validation to false
                    $validation_result['is_valid'] = false;
             
                    //finding Field with ID of 1 and marking it as failed validation
                    foreach( $form['fields'] as &$field ) {
             
                        //NOTE: replace 1 with the field you would like to validate
                        if ( $field->id == '10' ) {
                            $field->failed_validation = true;
                            $field->validation_message = 'Please Select a Company from the list';
                            break;
                        }
                    }
                }
            }

        }
        //Assign modified $form object back to the validation result
        $validation_result['form'] = $form;
        return $validation_result;
    }

    /**
     * Get an instance of the user profile depending of the user role
     * @return Object Class Profile
     */
    public function getUserRolesNames()
    {
        global $wp_roles;
        $roles = array();
        if ( !empty( $this->roles ) && is_array( $this->roles ) ) {
            foreach ( $this->roles as $key => $role ):
                if ( in_array( $role, array("operario","satelite","comerciante","proveedor","visitante") ) )
            { 
                $roles[] .= translate_user_role($wp_roles->roles[ $role ]['name']);
            }
            endforeach;
        }
        return implode(', ',$roles);
    }

    /**
     * Save user and profile information
     * @param  array $_POST
     * @return array $response
     */
    public function updateAccountInfo($data = array()) 
    {
        $userdata = [];
        $profiledata = [];
        $picture_url ='';

        // Update user data
        if (!empty($_POST['userdata']))
        {
            foreach($_POST['userdata'] as $index => $value)
            {
                $userdata[$index] = filter_var($value,FILTER_SANITIZE_STRING);
            }
            $userdata['ID'] = intval($this->ID);
            wp_update_user( $userdata );
        }

        // Update user data
        if (!empty($_POST['set_as_team_leader']))
        {
            if ( $_POST['set_as_team_leader'] == "is_leader" )
            {
                if ( !in_array( "company_team_leader", $this->roles ) ) {
                    //here our magic is done, we're setting the new role
                    $this->add_role( 'company_team_leader' );
                }
            }
            else
            {
                if ( in_array( "company_team_leader", $this->roles ) ) {
                    //here our magic is done, we're setting the new role
                    $this->remove_role( 'company_team_leader' );
                }
            }
        }

        // Update profile pic if has one
        if(!empty($_FILES["profile_pic"]))
        {            
            $attachment_id = media_handle_upload("profile_pic", $this->ID);
            if (is_numeric($attachment_id)) 
            {      
                update_user_meta( $this->ID, 'mm_sua_attachment_id', $attachment_id );
                $picture_url  = wp_get_attachment_url( $attachment_id );
            }          
        }
        
        // Check Password1 and Password2            
        if ($_POST['password'] != NULL)
        {
            if ($_POST['password']['current_password'] != NULL)
            {
                if ( wp_check_password( $_POST['password']['current_password'], $this->user_pass, $this->ID ) ) 
                {
                    if ($_POST['password']['new_password'] == $_POST['password']['new_password_2'])
                    {
                        $result = wp_update_user(array("ID" => $this->ID,"user_pass" => $_POST['password']['new_password']));
                        if( is_wp_error($result) )
                        {
                            $this->response['success'] = false;
                            $this->response['title']   = "Ops! Some Error Ocurred";
                            $this->response['message'] = $result->get_error_message();
                            $this->response['autoclose'] = 0; 
                            return $this->response;
                        } 
                    }
                    else
                    {
                        $this->response['success'] = false;
                        $this->response['title']   = "Ops! Some Error Ocurred";
                        $this->response['message'] = "The passwords don't match";
                        $this->response['autoclose'] = 0;
                        return $this->response;
                    }
                } else {
                    $this->response['success'] = false;
                    $this->response['title']   = "Ops! Some Error Ocurred";
                    $this->response['message'] = "Your current password doesn't match";
                    $this->response['autoclose'] = 1;
                    return $this->response;
                }
            }   
        }

        // Custom Fields
        if (!empty($_POST['fields']))
        {
            foreach($_POST['fields'] as $key => $value)
            {
                update_field($this->getFieldKey($key), $value, 'user_'.$this->ID);
            }
        }


        $this->response['success'] = true;
        $this->response['title']   = "Data saved successfully";
        $this->response['message'] = "Your updates have been saved";
        $this->response['profile_pic_url'] = $picture_url;
        $this->response['full_name'] = $userdata['first_name'] . " " . $userdata['last_name'];
        return $this->response;
    }


    /**
     * Get user profile image URL
     * @return string photo source
     */
    public function getProfilePicURL()
    {
        $profile_pic_url = get_template_directory_uri().'/images/user_placerholder.jpg';
        if( $this->mm_sua_attachment_id )        
        {

            $profile_pic_url  = wp_get_attachment_url( (int) $this->mm_sua_attachment_id );            
        }      
        return $profile_pic_url;
    }


    /**
     * Check if the user is logged in
     * @return boolean 
     */
    public function isLoggedIn()
    {        
        if( $this->ID > 0 && is_user_logged_in())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Check if user is Team Leader
     */
    public function isTeamLeader(){        
        if ( in_array( 'company_team_leader', (array) $this->roles ) ) {
            //The user has the "company_team_leader" role
            return true;
        }
        if ( in_array( 'portal_admin', (array) $this->roles ) ) {
            //The user has the "company_team_leader" role
            return true;
        }
        return false;
    }


    /**
     * Retrieves all the notifications of that user
     * @return boolean 
     */
    public function getNotifications()
    {   
        if( $this->ID == 0 ) return;

        $notification = new Notification();
        $messages = $notification->getMessages($this->ID);
        if ($messages) {
            return $messages;   
        }
        return "You don't have any notifications";
    }

    /**
     * Retrieves all the notifications of that user
     * @return boolean 
     */
    public function getMessageFormated()
    {   
        if( $this->ID == 0 ) return;

        $notification = new Notification();
        $messages = $notification->getMessageFormated($this->ID);
        if ($messages) {
            return $messages;   
        }
        return "You don't have any notifications";
    }

    /**
     * Adding to user favorite's list 
     * @param int $post_id listing id
     * @return void
     */
    public function addFavorite($post_id)
    {   
        $meta_key = "my_favorites";

        if( $this->has_prop($meta_key) )
        {
            $data = json_decode( $this->$meta_key );
            if (in_array( $post_id,  $data ) ) 
            {            
                return;
            }
            array_push( $data, $post_id );
            // Use array_values to reorder indexes into array to avoid json_encode problem
            $this->$meta_key = json_encode( array_values($data) );
        }
        else
        {
            $data = array( $post_id );
            $this->$meta_key = json_encode($data);
        }

        Utils::addProperty('my_favorites');

        //$listing = new Listing($post_id);
        //$listing->addUserToFavorites($this->ID);

        $response['success'] = true;
        $response['action']  = 'addFavorite';        
        $response['favorite_id']  = $post_id;

        return $this->response;
    }
    
    /**
     * Remove this listing from user favorite's list
     * @param  int $post_id listing id
     * @return void
     */
    public function removeFavorite($post_id)
    {
        $meta_key = "my_favorites";
        if( $this->has_prop($meta_key) )
        {
            $data = json_decode( $this->$meta_key );
            $key  = array_search( $post_id,  $data );
            if ( $key !== false )
            {
                unset( $data[$key] );
            }
            // Use array_values to reorder indexes into array to avoid json_encode problem
            $this->$meta_key = json_encode( array_values($data) );
        }
        $listing = new Listing($post_id);
        $listing->removeUserToFavorites($this->ID);

        $response['success'] = true;
        $response['action']  = 'removeFavorite';
        $response['favorite_id']  = $post_id;
        $response['title']   = "Nice!";
        $response['message'] = "Listing Unfavorited";

        return $this->response;
    }


     /**
     * Total of user favorites listings
     * @return int Total favorites listings
     */
    public function getCountFavorites()
    {
        global $wpdb;
        if( $this->ID )
        {
            try
            {
                
                $meta_key = "my_favorites";
                if( $this->has_prop($meta_key) )
                {
                    $data = json_decode( $this->$meta_key );
                    return count($data);
                }
                return 0;

            }
            catch (Exception $exc)
            {
                return 0;
            } 
        }
        else
        {
            return 0;
        }      
    }

    /**
     * Get user favorites listings
     * @return array favorites listings
     */
    public function getFavoriteListings()
    {
        $meta_key = "my_favorites";
        if( $this->has_prop($meta_key) )
        {
            $data = json_decode( $this->$meta_key );
        }
        else
        {
            $data = 0;
        }
        $response['success'] = true;
        $response['data'] = $data;

        return $this->response;  
    }

    /**
     * Check if the listing is in the user favorite's list
     * @param  int  $post_id listing id
     * @return void          
     */
    public function isFavorite( $post_id )
    {
        $meta_key = "my_favorites";
        if( $this->has_prop($meta_key) )
        {
            $data = json_decode( $this->$meta_key );
            if (in_array( $post_id,  $data ) ) 
            {                
                return true;
            }     
        }
        return false;
    }

    /**
     * Get reviews of the listing
     * @param  int $post_id 
     * @return int Rating value
     */
    public function getReviewsCount()
    {
        if( $this->ID )
        {
            try
            {
                $args = array(
                    'user_id' => $this->ID,
                );
                $comments_query = new WP_Comment_Query;
                $comments = $comments_query->query( $args );
                return count($comments);
                
            }
            catch (Exception $exc)
            {
                return false;
            } 
        }
        else
        {
            return false;
        }
    }

    /**
     * Get my review by application ID
     * @param  [type] $application_id [description]
     * @return [type]                 [description]
     */
    public function getMyReview( $application_id )
    {
        $args = array(
            'user_id' => $this->ID,
            'post_id' => (int) $application_id ,
        );
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );

        if ( !empty( $comments ) ) 
            return $comments;
        return false;
    }


    /**
     * Remove review comment from the list
     * @param  int $review_id listing id
     * @return void
     */
    public function removeReview($review_id)
    {
        $response['success']  = false;
        if( !empty($review_id) )
        {
            $result  = wp_delete_comment( $review_id, true );
        }

        if ($result)
        {
            $response['success'] = true;
            $response['review_id']  = $review_id;
            $response['title']   = "Review Deleted";
            $response['message'] = "Your review was successfully deleted";
        }
        else 
        {
            $response['success'] = false;
            $response['title']   = "Delete Review";
            $response['message'] = "Error trying to delete this comment.";
        }

        return $this->response;
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
        
        if($name == 'full_name')
        {
            return $this->first_name . " " . $this->last_name;
        }        
        return parent::__get($name);
    }

    /**
     * Magic Method to set values
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function __set($key,$value)
    {
        parent::__set($key,$value);
        update_user_meta($this->ID,$key,$value); 
    }


    public function __recursive_array_search($needle,$haystack) 
    {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && $this->__recursive_array_search($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }
    
}