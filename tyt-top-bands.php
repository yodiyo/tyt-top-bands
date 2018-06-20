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
                'has_archive' => false
            )
        );
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
?>