<?php
 /*Template Name: New Template
 */
 
get_header(); ?>

    <?php
        $mypost = array( 'post_type' => 'top_bands', );
        // $query = new WP_Query( $mypost );
    ?>

    <?php while ( have_posts() ) : the_post();?>
        <?php get_template_part( 'loop-templates/content-single-api-post', 'page' ); ?> 
    <?php endwhile; ?>
    
<?php wp_reset_query(); ?>
<?php get_footer(); ?>