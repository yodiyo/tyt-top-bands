<?php
   
   /**
   * Plugin Name: TYT Top Bands
   * Plugin URI: https://github.com/yodiyo/tyt-top-bands
   * description: Top Trump Bands
   * Version: 1.0
   * Author: Yorick Brown
   * Author URI: http://theyoricktouch.com
   * License: GPL2 or later
   */


    // deny direct access to this file 
    defined( 'ABSPATH' ) or die( 'No intruders please!' );

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
                'supports' => array( 'thumbnail'),
                'taxonomies' => array( '' ),
                'menu_icon' => 'dashicons-format-audio',
                'has_archive' => false,
                'capability_type' => array('top_band', 'top_bands'),
                'map_meta_cap' => true
            )
        );
    }


    // post templates
    add_filter( 'template_include', 'include_template_function', 1 );

    function include_template_function( $template_path ) {
        if ( get_post_type() == 'top_bands' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'page_templates/single-top-bands.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-top-bands.php';
                }
            } 
        }
        return $template_path;
    }

    // add artist name as post name
    function update_post($post_id) {
        $post_type = get_post_type($post_id);
        if ($post_type != 'top_bands') {
            return;
        }
        $post_title = get_field('artist_name', $post_id);
        $post_name = sanitize_title($post_title);
        $post = array(
            'ID' => $post_id,
            'post_name' => $post_name,
            'post_title' => $post_title
        );
        wp_update_post($post);
    }
    add_action('acf/save_post', 'update_post', 1); 

    // create top band creator role

    // Register activation hood to add role
    register_activation_hook( __FILE__, 'tyt_add_band_role' );

    function tyt_add_band_role() {
        add_role('top_band_creator',
            'Top Band creator',
            array(
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
            )
        );
    }

    // Run the function on admin_init
    add_action('admin_init', 'remove_profile_menu');

    // Removal function
    function remove_profile_menu() {
        global $wp_roles;

        // Remove the menu. Syntax is `remove_submenu_page($menu_slug, $submenu_slug)`
        remove_submenu_page('users.php', 'profile.php');
        remove_menu_page('profile.php');
        
        /* Remove the capability altogether*/
        $wp_roles->remove_cap('top_band_creator', 'read');
    }
    
    // Register deactivation hood to remove role
    register_deactivation_hook( __FILE__, 'tyt_remove_band_role' );
    
    function tyt_remove_band_role() {
        remove_role( 'top_band_creator' );
    }
    
    
    add_action('admin_init','tyt_add_role_caps',999);


    function tyt_add_role_caps() {
    
        // Add the roles you'd like to administer the custom post types
        $roles = array('top_band_creator', 'editor','administrator');
        
        // Loop through each role and assign capabilities
        foreach($roles as $the_role) { 
    
            $role = get_role($the_role);
        
            $role->add_cap( 'read' );
            $role->add_cap( 'read_top_band');
            $role->add_cap( 'read_private_top_band' );
            $role->add_cap( 'edit_top_band' );
            $role->add_cap( 'edit_top_bands' );
            $role->remove_cap( 'edit_others_top_bands' );
            $role->add_cap( 'edit_published_top_bands' );
            $role->add_cap( 'publish_top_bands' );
            //$role->add_cap( 'delete_others_top_bands' );
            // $role->add_cap( 'delete_private_top_bands' );
            // $role->add_cap( 'delete_published_top_bands' );
        }
    } 

?>