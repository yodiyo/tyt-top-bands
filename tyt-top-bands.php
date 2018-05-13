<?php
   /*
   Plugin Name: TYT Top Bands
   Plugin URI: http://top-bands.com
   description: Top Trump Bands
   Version: 1.0
   Author: Yorick Brown
   Author URI: http://theyoricktouch.com
   License: GPL2 or later
   */


    // deny direct access to this file 
    // defined( 'ABSPATH' ) or die( 'No intruders please!' );

    add_action( 'init', 'create_top_band' );

    function create_top_band () {
        register_post_type( 'top_bands',
            array(
                'labels' => array(
                    'name' => 'Top Bands',
                    'singular_name' => 'Top Band',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Top Band',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Top Band',
                    'new_item' => 'New Top Band',
                    'view' => 'View',
                    'view_item' => 'View Top Band',
                    'search_items' => 'Search Top Bands',
                    'not_found' => 'No Top Bands found',
                    'not_found_in_trash' => 'No Top Bands found in Trash',
                    'parent' => 'Parent Top Band'
                ),
                'rewrite' => array( 
                    'slug' => 'top-bands',
                    'with_front' => false
                ),
                'public' => true,
                'menu_position' => 15,
                'supports' => array( 'title', 'thumbnail'),
                'taxonomies' => array( '' ),
                'menu_icon' => 'dashicons-format-audio',
                'has_archive' => false
            )
        );
    }

    // add_action( 'admin_init', 'my_admin' );
    // add_action( 'save_post', 'add_top_band_fields', 10, 2 );

    // function my_admin() {
    //     add_meta_box( 
    //         'top_band_meta_box',
    //         'Top Band Details',
    //         'display_top_band_meta_box',
    //         'top_bands', 'normal', 'high'
    //     );
    // }

    
    function display_top_band_meta_box( $top_band ) {
        // get number of entries on Songkick
        $number_entries = esc_html( get_post_meta( $top_band->ID, 'number_entries', true ) );
        $first_gig = esc_html( get_post_meta( $top_band->ID, 'first_gig', true ) );
        $first_venue = esc_html( get_post_meta( $top_band->ID, 'first_venue', true ) );
        
        ?>
            <table>
                <tr>
                    <td style="width: 100%">Number of gigs</td>
                    <td><input type="text" size="80" name="top_band_number_entries" value="<?php echo $number_entries; ?>" /></td>
                </tr>
                <tr>
                    <td style="width: 100%">First gig</td>
                    <td><input type="text" size="80" name="top_band_first_gig" value="<?php echo $first_gig; ?>" /></td>
                </tr>
                <tr>
                    <td style="width: 100%">First venue</td>
                    <td><input type="text" size="80" name="top_band_first_venue" value="<?php echo $first_venue; ?>" /></td>
                </tr>
            </table>
        <?php
    }

    function add_top_band_fields( $top_band_id, $top_band ) {
        // Check post type for top bands
        if ( $top_band->post_type == 'top_bands' ) {
            // Store data in post meta table if present in post data
            if ( isset( $_POST['top_band_number_entries'] ) && $_POST['top_band_number_entries'] != '' ) {
                update_post_meta( $top_band_id, 'number_entries', $_POST['top_band_number_entries'] );
            }
            if ( isset( $_POST['top_band_first_gig'] ) && $_POST['top_band_first_gig'] != '' ) {
                update_post_meta( $top_band_id, 'first_gig', $_POST['top_band_first_gig'] );
            }
            if ( isset( $_POST['top_band_first_venue'] ) && $_POST['top_band_first_venue'] != '' ) {
                update_post_meta( $top_band_id, 'first_venue', $_POST['top_band_first_venue'] );
            }
        }
    }

    add_filter( 'template_include', 'include_template_function', 1 );

    function include_template_function( $template_path ) {
        if ( get_post_type() == 'top_bands' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-top-bands.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-top-bands.php';
                }
            } 
        }
        return $template_path;
    }

?>