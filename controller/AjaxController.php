<?php
/**
 * Upload Store profile pic through ajax.
 */
add_action( 'wp_ajax_upload_profile_pic', 'tusatelite_upload_profile_pic' );
function tusatelite_upload_profile_pic() 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }

    check_ajax_referer('ajax-upload-profile-pic', 'security');

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->uploadProfilePic( $_POST );

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);
    
    wp_die();
}

/**
 * Upload banner pic through ajax.
 */
add_action( 'wp_ajax_upload_banner_pic', 'tusatelite_upload_banner_pic' );
function tusatelite_upload_banner_pic() 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }

    check_ajax_referer('ajax-upload-banner-pic', 'security');

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->uploadProfileBannerPic( $_POST );

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);
    
    wp_die();
}

/**
 * Update info from my dashboard.
 */
add_action( 'wp_ajax_saving_store_information_details', 'tusatelite_saving_store_information_details' );
function tusatelite_saving_store_information_details( ) 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }

    check_ajax_referer('ajax-saving-store-information-details', 'security');

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->saveStoreInformationDetails( $_POST );

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);
    
    wp_die();
}

/**
 * Upload your photos through ajax.
 */
add_action( 'wp_ajax_upload_photo_ajax', 'tusatelite_upload_photo_ajax' );
function tusatelite_upload_photo_ajax() 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }
    
    check_ajax_referer('upload-photo-ajax-nonce', 'security');

    if(!(is_array($_POST) && is_array($_FILES) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->addPhoto($_POST, $_FILES);

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

    wp_die();
}

/**
 * Get your photos through ajax.
 */
add_action( 'wp_ajax_get_photo_ajax', 'tusatelite_get_photo_ajax' );
function tusatelite_get_photo_ajax() 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }
    
    //check_ajax_referer('get-photo-ajax-nonce', 'security');

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->getPhotos();

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

    wp_die();
}

/**
 * Remove photos through ajax.
 */
add_action( 'wp_ajax_remove_photo_ajax', 'tusatelite_remove_photo_ajax' );
function tusatelite_remove_photo_ajax() 
{
    $user_id = get_current_user_id();
    if(intval($user_id) == 0)
    {      
      $response['success'] = false;
      $response['title']   = "Ops! Some Error Occurred.";
      $response['message'] = "User can not be null";
      echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

      wp_die();
    }
    
    check_ajax_referer('remove-photo-ajax-nonce', 'security');

    if(!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)){
        return;
    }

    $user = new User( $user_id );
    $store = new Store( $user->store_id );

    $response = $store->removePhotos( $_POST['photo_id'] );

    echo json_encode($response, JSON_HEX_QUOT | JSON_HEX_TAG);

    wp_die();
}
