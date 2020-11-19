<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

class Utils
{
    /**
     * Array to hold the response
     * @var null
     */
    private $response = array();


    /**
     * Class constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Allow upload an image to wordpress media gallery
     */
    public function uploadImage($post_id, $data = array(), $acf_name='', $size = '')
    {

        if(!function_exists('wp_handle_upload')){
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        $upload_dir       = wp_upload_dir();

        $upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        // remove the part that we don't need from the provided image and decode it
        $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image_base64']));

        $filename         = 'profile_pic_' . $post_id . '.png';

        $hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;

        error_log($upload_path . $hashed_filename, $decoded);

        // @new
        $image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

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
            if ($acf_name) 
            {
            	update_post_meta( $post_id, $acf_name , $attach_id );    
            }
            else
            {
            	set_post_thumbnail( $post_id, $attach_id );
            }
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
     * Convert time like time ago format
     * @param  datetime $ts [description]
     * @return string     formated string time ago
     */
    public static function time2str($ts) 
    {
        if(!ctype_digit($ts)) {
            $ts = strtotime($ts);
        }
        $diff = time() - $ts;
        if($diff == 0) {
            return 'now';
        } elseif($diff > 0) {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) { return 'Yesterday'; }
            if($day_diff < 7) { return $day_diff . ' days ago'; }
            if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
            if($day_diff < 60) { return 'last month'; }
            return date('F Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 120) { return 'in a minute'; }
                if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
                if($diff < 7200) { return 'in an hour'; }
                if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
            }
            if($day_diff == 1) { return 'Tomorrow'; }
            if($day_diff < 4) { return date('l', $ts); }
            if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
            if(ceil($day_diff / 7) < 4) { return 'in ' . ceil($day_diff / 7) . ' weeks'; }
            if(date('n', $ts) == date('n') + 1) { return 'next month'; }
            return date('F Y', $ts);
        }
    }

    /**
     * Currency format
     * @param  string $value number to put in float
     * @return string        String formated
     */
    public static function moneyFormat( $value = null ) {
        if ( $value == null ) return;
        $value = (float) $value;
        if ($value<0) return "-".$this->moneyFormat(-$value);
        return '$' . number_format($value, 2);
    }
    
    /**
     * Format date April 10, 2020
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function dateFormat( $value ) {
        if (  $value )
        {
            return date( "F j, Y",strtotime( $value ) );
        }
        return false;
    }


    public static function acfButton($field, $style = null) {
        if ($field['button']['text']) 
        {
            if ($field['button']['type'] == 'internal') 
            {
                printf('<a href="%s" class="btn %s">%s</a>', esc_url($field['button']['internal_url']), $style, $field['button']['text']);
            }
            elseif ($field['button']['type'] == 'external')
            {
                printf('<a href="%s" class="btn %s" target="_blank">%s</a>', esc_url($field['button']['external_url']), $style, $field['button']['text']);
            }
        }
    }

}