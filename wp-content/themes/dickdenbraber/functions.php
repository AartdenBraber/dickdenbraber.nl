<?php
/**
 * Dick den Braber functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dick_den_Braber
 */

if ( ! function_exists( 'dickdenbraber_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dickdenbraber_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Dick den Braber, use a find and replace
	 * to change 'dickdenbraber' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'dickdenbraber', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'custom-logo', array(
		'height'      => 200,
		'width'       => 200,
		'flex-height' => false,
		'flex-width'  => false,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary-menu' => esc_html__( 'Primary', 'dickdenbraber' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'dickdenbraber_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

}
endif;
add_action( 'after_setup_theme', 'dickdenbraber_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dickdenbraber_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dickdenbraber_content_width', 640 );
}
add_action( 'after_setup_theme', 'dickdenbraber_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dickdenbraber_widgets_init() {
	/*register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dickdenbraber' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dickdenbraber' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );*/

	register_sidebar(array(
		'name' => 'Landingspagina recente posts',
		'id'   => 'homepage-recent-posts',
		'description'   => 'Dit verschijnt op de homepagina',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));

}
add_action( 'widgets_init', 'dickdenbraber_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dickdenbraber_scripts() {
	wp_enqueue_style( 'dickdenbraber-style', get_stylesheet_uri() );

	wp_enqueue_script( 'dickdenbraber-javascript', get_template_directory_uri() . '/js/scripts.js', array(), '20151215', true );

	wp_enqueue_script( 'dickdenbraber-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'dickdenbraber-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dickdenbraber_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';







/* =Clean up the WordPress head
------------------------------------------------- */

// remove header links
add_action('init', 'tjnz_head_cleanup');
function tjnz_head_cleanup() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );                      // Category Feeds
    remove_action( 'wp_head', 'feed_links', 2 );                            // Post and Comment Feeds
    remove_action( 'wp_head', 'rsd_link' );                                 // EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' );                         // Windows Live Writer
    remove_action( 'wp_head', 'index_rel_link' );                           // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );              // previous link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );               // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );   // Links for Adjacent Posts
    remove_action( 'wp_head', 'wp_generator' );                             // WP version
    if (!is_admin()) {
        wp_deregister_script('jquery');                                     // De-Register jQuery
        wp_register_script('jquery', '', '', '', true);                     // Register as 'empty', because we manually insert our script in header.php
  		wp_deregister_script('mediaelement');
    } 
}

// remove WP version from RSS
add_filter('the_generator', 'tjnz_rss_version');
function tjnz_rss_version() { return ''; }


/**
 * Filter the except length to less words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

/*// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
       global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '">[...]</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');*/




/*function set_width_cookie() {
	session_start();

	if( isset($_POST['width']) ) :
		$number_of_days = 30;
	    //setcookie('screen_width', $_POST['width'], time() + 60 * 60 * 24 * $number_of_days);
	    $_SESSION['screen_width'] = $_POST['width'];
	    die; // this is the only thing that needs to be done. Don't waste resources
	elseif( !isset($_SESSION['screen_width']) ) : 
		function execute() { ?>
			<script>
				$.ajax({
					type: "POST",
				    data: { width: window.innerWidth } 
			    });
			</script>
		<?php }
		add_action('wp_footer','execute');
	endif; 

	function executeAlways() { ?>
		<script>
			var resizeId;
			$(window).resize(function() {
				clearTimeout(resizeId);
    			resizeId = setTimeout(doneResizing, 500);
			});
			function doneResizing(){
				$.ajax({
					type: "POST",
				    data: { width: window.innerWidth } 
			    });
			}
		</script>
	<?php } 
	add_action('wp_footer','executeAlways');

}
add_action('init','set_width_cookie');*/

add_image_size('dickdenbraber_medium_small', 500, 999999);