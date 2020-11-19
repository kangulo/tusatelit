<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
/**
 * Class Name: bs4Navwalker
 * GitHub URI: https://github.com/dupkey/bs4navwalker
 * Description: A custom WordPress nav walker class for Bootstrap 4 nav menus in a custom theme using the WordPress built in menu manager.
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class bs4Navwalker extends Walker_Nav_Menu
{
    
    public function __construct() {
        add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 2);
        add_filter('nav_menu_item_id', '__return_null');
        $cpt           = get_post_type();
        $this->cpt     = in_array($cpt, get_post_types(array('_builtin' => false)));
        $this->archive = get_post_type_archive_link($cpt);
    }

    public function checkCurrent($classes) {
        return preg_match('/(current[-_])|active/', $classes);
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"dropdown-menu\">\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

        // New
        $class_names .= ' nav-item';
        
        if (in_array('menu-item-has-children', $classes)) {
            $class_names .= ' dropdown';
        }

        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }
        //

        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        // print_r($class_names);

        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        // New
        if ($depth === 0) {
            $output .= $indent . '<li' . $id . $class_names .'>';
        }
        //

        // $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        // New
        if ($depth === 0) {
            $atts['class'] = 'nav-link';
        }

        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class']       .= ' dropdown-toggle';
            $atts['data-toggle']  = 'dropdown';
        }

        if ($depth > 0) {
            $manual_class = array_values($classes)[0] .' '. 'dropdown-item';
            $atts ['class']= $manual_class;
        }

        if (in_array('current-menu-item', $item->classes)) {
            $atts['class'] .= ' active';
        }
        // print_r($item);
        //

        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        // New
        /*
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $item_output .= '<a class="nav-link dropdown-toggle"' . $attributes .'data-toggle="dropdown">';
        } elseif ($depth === 0) {
            $item_output .= '<a class="nav-link"' . $attributes .'>';
        } else {
            $item_output .= '<a class="dropdown-item"' . $attributes .'>';
        }
        */
        //
        $item_output .= '<a'. $attributes .'>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if ($depth === 0) {
            $output .= "</li>\n";
        }
    }


    public function cssClasses($classes, $item) {
        $slug = sanitize_title($item->title);

        // Fix core `active` behavior for custom post types
        if ($this->cpt) {
          $classes = str_replace('current_page_parent', '', $classes);

          if ($this->archive) {
            if ($this->url_compare($this->archive, $item->url)) {
              $classes[] = 'active';
            }
          }
        }

        // Remove most core classes
        $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
        $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

        // Re-add core `menu-item` class
        $classes[] = 'menu-item';

        // Re-add core `menu-item-has-children` class on parent elements
        if ($item->is_subitem) {
          $classes[] = 'menu-item-has-children';
        }

        // Add `menu-<slug>` class
        $classes[] = 'menu-' . $slug;

        $classes = array_unique($classes);
        $classes = array_map('trim', $classes);

        return array_filter($classes);
    }

    /**
     * Compare URL against relative URL
     */
    function url_compare($url, $rel) {
      $url = trailingslashit($url);
      $rel = trailingslashit($rel);
      return ((strcasecmp($url, $rel) === 0) || root_relative_url($url) == $rel);
    }

}

/**
 * Make a URL relative
 */
function root_relative_url($input) {
  if (is_feed()) {
    return $input;
  }
  $url = parse_url($input);
  if (!isset($url['host']) || !isset($url['path'])) {
    return $input;
  }
  $site_url = parse_url(network_home_url());  // falls back to home_url
  if (!isset($url['scheme'])) {
    $url['scheme'] = $site_url['scheme'];
  }
  $hosts_match = $site_url['host'] === $url['host'];
  $schemes_match = $site_url['scheme'] === $url['scheme'];
  $ports_exist = isset($site_url['port']) && isset($url['port']);
  $ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;
  if ($hosts_match && $schemes_match && $ports_match) {
    return wp_make_link_relative($input);
  }
  return $input;
}

