<?php 
/**
 * Theme setup
 */
add_action('after_setup_theme', function () {

    /**
     * Make theme available for translation.
     */
    load_theme_textdomain('tusatelit', get_template_directory() . '/languages');

    /**
     * remove junk from head
     */
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('use_default_gallery_style', '__return_false');
    add_filter('emoji_svg_url', '__return_false');
    add_filter('show_recent_comments_widget_style', '__return_false');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);

    /**
     * Enable automatic feed link
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
     */
    add_theme_support('automatic-feed-links');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'header-menu' => __('Header Menu', 'tusatelit'),
        'footer-menu' => __('Footer Menu', 'tusatelit'),
        'social-menu' => __('Social Links Menu', 'tusatelit'),
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1568, 9999);

    /**
     * Enable support for Post Thumbnails on posts and pages.
     */ 
    add_image_size( 'product-thumbnail', 225, 225, true );
    // add_image_size( 'blog-post-small', 82, 82 );
    // add_image_size( 'core-user', 737, 1142, true);
    // add_image_size( 'core-user-thumnail', 300, 465, true);
    // add_image_size( 'blog-post-featured', 737, 484 );

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');


    /**
     * post formats
     */
    add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', [
        'height' => 250,
        'width' => 250,
        'flex-width' => false,
        'flex-height' => false,
        'header-text' => ['site-title', 'site-description'],
    ]);

    /**
     * Add support for full and wide align images.
     * 
     */
    add_theme_support('align-wide');

    /**
     * Add support for responsive embedded content.
     */
    add_theme_support('responsive-embeds');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    //add_editor_style(get_template_directory_uri() . '/style.css');
}, 20);

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {

    global $wp_query;

    /*** Enqueue styles. ***/
    wp_enqueue_style('google-fonts-montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,600,700', false, false, 'all');
    wp_enqueue_style('google-fonts-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i', false, false, 'all');
    wp_enqueue_style('plugins', get_theme_file_uri('/css/plugins.css'), false, wp_get_theme()->get('Version'), 'all');
    wp_enqueue_style('style', get_stylesheet_uri(), false, wp_get_theme()->get('Version'));

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.5.1.min.js', null, '3.5.1', true);

    wp_enqueue_script('plugins', get_theme_file_uri('/js/plugins.js'), ['jquery'], wp_get_theme()->get('Version'), true);
    wp_enqueue_script('scripts', get_theme_file_uri('/js/scripts.js'), ['jquery'], wp_get_theme()->get('Version'), true);

    $data = array(
        'site_url' => get_theme_file_uri(),
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('scripts', 'ajax', $data);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

}, 100);


/**
 * Add ACF Options page
 */
if(function_exists('acf_add_options_page')) {
    // $option_page = acf_add_options_page(array(
    //   'page_title'  => 'General Settings',
    //   'menu_title'  => 'Theme Settings',
    //   'menu_slug'   => 'theme-general-settings',
    //   'capability'  => 'edit_posts',
    //   'redirect'    => true,
    //   'position' => 61,
    //   'icon_url'    => 'dashicons-hammer'
    // ));

    // $option_page = acf_add_options_sub_page(array(
    //   'page_title'  => 'Header Setup',
    //   'menu_title'  => 'Header Setup',
    //   'parent_slug' => 'theme-general-settings',
    // ));

    // $option_page = acf_add_options_sub_page(array(
    //   'page_title'  => 'Footer Setup',
    //   'menu_title'  => 'Footer Setup',
    //   'parent_slug' => 'theme-general-settings',
    // ));
    acf_add_options_page();
}

/**
 * Autoloader Classes 
 */
spl_autoload_register( function ( $class_name ) {

  /**
   * If the class being requested does not start with our prefix,
   * we know it's not one in our project
   */
  if ( empty( $class_name ) ) {
    return;
  }

  // Compile our path from the current location
  $classes = dirname( __FILE__ ) . '/classes/'. $class_name .'.php';  

  // If a file is found
  if ( file_exists( $classes ) ) {
    // Then load it up!
    require( $classes );
  }

});

require_once('controller/AjaxController.php');

/**
 * Remove Admin Toolbar
 */
show_admin_bar( false );
add_filter('show_admin_bar' , '__return_false');
add_theme_support('admin-bar', ['callback' => '__return_false']);

/**
 * Add some scripts and style to the backend login
 */
add_action('login_head', function() { 
    echo '<link rel="stylesheet" type="text/css" href="' . get_theme_file_uri( '/admin/login.css' ) . '" />'; 
    echo '<script type="text/javascript" src="' . get_theme_file_uri( '/admin/jquery-3.2.1.min.js' ) . '"></script>';
    echo '<script type="text/javascript" src="' . get_theme_file_uri( '/admin/login.js' ) . '"></script>';
     
});

/**
 * Function to run every time wordpress finish to load
 * Load User Class global variable.
 * @return void 
 */
add_action("init",function() {
    
    if(!is_admin()) 
    {
        $current_user = wp_get_current_user();

        global $user, $post;
        if(session_id() == NULL)
        {
            session_start();        
        }
        
        $user = new User(get_current_user_id());

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
        {
            if(session_id())
            {
                session_destroy();
                $_SESSION = array();
            }
            wp_logout();
            wp_redirect(get_site_url());        
            exit();        
        }
    }
    $App = new App();
});

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */

add_filter('wp_nav_menu_args', function ($args = '') {
  $nav_menu_args = [];
  $nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s </ul>';
  }

  if (!$args['walker']) {
    $nav_menu_args['walker'] = new bs4Navwalker();
  }

  return array_merge($args, $nav_menu_args);
});

// Remove ID from menu li items
add_filter('nav_menu_item_id', '__return_null');

/*** get permalink by template name */
function get_template_link($temp){
    $link = null;
    $pages = get_pages(
        array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $temp
        )
    );
    if(isset($pages[0])){
        $link = get_page_link($pages[0]->ID);
    }
    return $link;
}

if ( ! function_exists( 'tusatelit_starter_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function tusatelit_starter_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    }

    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );

    $posted_on = sprintf(
        esc_html_x( 'Posted on %s', 'post date', 'tusatelit' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    $byline = sprintf(
        esc_html_x( 'by %s', 'post author', 'tusatelit' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span> | <span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo ' | <span class="comments-link"><i class="fa fa-comments" aria-hidden="true"></i> ';
        /* translators: %s: post title */
        comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'tusatelit' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
        echo '</span>';
    }

}
endif;


if ( ! function_exists( 'tusatelit_starter_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function tusatelit_starter_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'tusatelit' ) );
        if ( $categories_list && tusatelit_starter_categorized_blog() ) {
            printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'tusatelit' ) . '</span>', $categories_list ); // WPCS: XSS OK.
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html__( ', ', 'tusatelit' ) );
        if ( $tags_list ) {
            printf( ' | <span class="tags-links">' . esc_html__( 'Tagged %1$s', 'tusatelit' ) . '</span>', $tags_list ); // WPCS: XSS OK.
        }
    }


    edit_post_link(
        sprintf(
            /* translators: %s: Name of current post */
            esc_html__( 'Edit %s', 'tusatelit' ),
            the_title( '<span class="screen-reader-text">"', '"</span>', false )
        ),
        ' | <span class="edit-link">',
        '</span>'
    );
}
endif;


// create field
new acf_field_gravity_forms();

add_filter( 'loop_shop_columns', 'tm_product_columns', 5);
function tm_product_columns($columns) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        $columns = 4;
        return $columns;
    }
}



?>