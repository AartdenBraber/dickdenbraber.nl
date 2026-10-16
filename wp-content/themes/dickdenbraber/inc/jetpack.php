<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Dick_den_Braber
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function dickdenbraber_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'type' => 'click',
		'container' => 'posts-container',
		'render'    => 'dickdenbraber_infinite_scroll_render',
		'footer'    => false,
		'posts_per_page'    => 8,
		'wrapper'    => false,

	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'dickdenbraber_jetpack_setup' );


function filter_jetpack_infinite_scroll_js_settings( $settings ) {
	$settings['text'] = __( 'Meer laden...', 'l18n' );
	return $settings;
}
add_filter( 'infinite_scroll_js_settings', 'filter_jetpack_infinite_scroll_js_settings' );


/**
 * Custom render function for Infinite Scroll.
 */
function dickdenbraber_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content-preview' );
		endif;
	}
}




/**
 * Jetpack related posts
 */


// Remove standard Jetpack functionality and replace it with something much better //
function jp_remove_related() {
	$jprp = Jetpack_RelatedPosts::init();
	$callback = array( $jprp, 'filter_add_target_to_dom' );
	remove_filter( 'the_content', $callback, 40 );
}
add_filter('wp', 'jp_remove_related', 20);




/* Create shortcode for displaying related posts anywhere in the post */
function dickdenbraber_related_shortcode( $atts ) {

	$related;
	$amountOfPosts = 4; // How many related posts?

    if ( class_exists( 'Jetpack_RelatedPosts' ) && method_exists( 'Jetpack_RelatedPosts', 'init_raw' ) ) {

        $related = Jetpack_RelatedPosts::init_raw()
            ->set_query_name( 'jetpackme-shortcode' )
            ->get_for_post_id(
                get_the_ID(),
                array( 'size' => $amountOfPosts -1 ) // at least have one picked at random
            );
    }
    
    if(count($related) < $amountOfPosts) {
    	$relatedIDs = array();
    	array_walk_recursive($related, function($v, $k) use (&$relatedIDs){ $relatedIDs[] = $v; });

    	$exclude = array_merge($relatedIDs, array(get_the_ID())); //also exclude the current post
	    $args = array(
		    'post_type' => 'post',
		    'posts_per_page' => $amountOfPosts - count($related), // just fill up the missing ones
	    	'post__not_in' => $exclude,
		    'orderby'   => 'rand',
		    );
		 
		$filler_posts = new WP_Query( $args );

	    while ( $filler_posts->have_posts() ) : $filler_posts->the_post();
	        array_push( $relatedIDs, get_the_ID() );
	    endwhile;
	    wp_reset_postdata();

	} 

	$related_posts_string = "";
	$related_posts = new WP_Query(array('post__in' => $relatedIDs)); 

	while ( $related_posts->have_posts() ) : $related_posts->the_post(); 
		ob_start();
		get_template_part('template-parts/content-preview');
        $related_posts_string .= ob_get_clean();
	endwhile;
	wp_reset_query();
/* 
	$related_posts = "";

    foreach ( $posts as $post ) : setup_postdata( $post ); 

        get_template_part( 'template-parts/content-preview' );

        
        $post = get_post( $result );

        $url = get_permalink($post->ID);
        $title = $post->post_title;

		$screen_width = $_SESSION['screen_width'];

		if($screen_width < 400)
			$size = 'dickdenbraber_medium_small';
		else
			$size = 'medium_large';

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );

		if( $image[1] > 250 && $image[2] > 250 ) {
			$too_small = false;
		} else {
			$too_small = true;
		}
		if($screen_width < 500)
			$too_small = false;

        $related_posts .= '<li>
        	<div class="post post--related">
        		<a href="' . $url . '" class="post__image lazyload background-cover ajax-popup-link" data-bg="url(' . $image[0] . ')"><h3 class="post--related__title">' . $title . '</h3></a>
        	</div>
        </li>';
    endforeach;
	wp_reset_postdata();
        */

    $related_posts_string = '
    	<section class="page-content related-posts-container">
    		<div class="entry-content content">
    			<h5>Misschien vindt u dit ook interessant</h5>
    			<ul class="posts-container clearfix">' . $related_posts_string . '</ul>
    		</div>
    	</div>';

    

    return $related_posts_string;
}

// Create a new shortcode for Jetpack related posts //
add_shortcode( 'dickdenbraber_related', 'dickdenbraber_related_shortcode' );

// Do not load the one-big Jetpack concatenated CSS file //
add_filter('jetpack_implode_frontend_css', '__return_false');

// First, make sure Jetpack doesn't concatenate all its CSS
add_filter( 'jetpack_implode_frontend_css', '__return_false' );

// Then, remove each CSS file, one at a time
function jeherve_remove_all_jp_css() {
  
  wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
  
}
add_action('wp_print_styles', 'jeherve_remove_all_jp_css' );

// Dequeue the default styles and jQuery for Jetpack module //
function add_dickdenbraber_scripts() {
    if (!is_admin()) {
        wp_dequeue_script('jetpack-related_posts');
        wp_dequeue_style('jetpack-related_posts');
    }
}

add_action("wp_enqueue_scripts", "add_dickdenbraber_scripts", 20);