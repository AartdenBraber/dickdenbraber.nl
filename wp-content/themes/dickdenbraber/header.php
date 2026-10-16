<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dick_den_Braber
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
  	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

	<title>Dick den Braber - journalist en tekstschrijver</title>
	<meta name="description" content="Dick den Braber - journalist en tekstschrijver">
	<meta name="author" content="Dick den Braber">

	<link href="//fonts.googleapis.com/css?family=Roboto+Slab|Raleway:400" rel="stylesheet" async defer>

		
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
	<![endif]-->

    <?php wp_head(); ?>


</head>

<body ontouchstart="" <?php body_class(); ?>>
<div id="page" class="site hyphenate">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dickdenbraber' ); ?></a>

	<?php /* <header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description;  ?></p>
			<?php
			endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'dickdenbraber' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead --> */ ?>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-header-inner">
			<div class="site-branding-container">
				<a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<div class="logo-container">
						
						<?php 
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
						?>
						<img class="logo" src="<?php echo $image[0]; ?>" alt="Logo">
					</div>
					<!-- <div class="logo-container">
						<img class="logo" src="//aartdenbraber.nl/wp-old/wp-content/uploads/2017/02/logo-small.png" alt="Logo" />
					</div> -->
					<div class="site-title-container">
						<?php
						if ( is_front_page() || is_home() ) : ?>
							<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
						<?php else : ?>
							<p class="site-title"><?php bloginfo( 'name' ); ?></p>
						<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php
						endif; ?>
					</div> <!-- .site-title-container -->
				</a><!-- .site-branding -->
			</div>

			<nav class="site-header-menu-container">
				<ul class="site-header-menu">
					<?php $menu = wp_nav_menu( array( 
						'theme_location'  => 'primary-menu',
						'echo' => false,
				        'container' => false,
				        'fallback_cb' => function() { return ''; }, ) ); 

					    $menu = preg_replace(array(
					        '#^<ul[^>]*>#',
					        '#</ul>$#'
					    ), '', $menu);

					    echo $menu;

					?><li class="site-header-menu__item recensies-link optional-hide">
						<a class="recensies <?php echo ( is_front_page() ? 'smooth-scroll' : '' ); ?>" href="<?php echo (is_front_page() ? "" : esc_url( home_url( '/' ) ) ); ?>#recensies">						Recensies
						</a>
					</li><li class="site-header-menu__item contact-now-button">
						<a class="contact-now <?php echo ( is_front_page() ? 'smooth-scroll' : '' ); ?>" href="<?php echo (is_front_page() ? "" : esc_url( home_url( '/' ) ) ); ?>#contact">
							<svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#emailaddress"></use></svg>
						</a>
					</li><li class="site-header-menu__item menu-toggle-container">
						<a class="menu-toggle">
							<span class="nav-icon"><span></span><span></span><span></span><span></span></span>
							<span class="nav-text"><?php esc_html_e( 'Menu', 'aart-den-braber-portfolio' ); ?></span>
						</a>
					</li>
				</ul><!-- .site-header-menu -->
			</nav><!-- .site-header-menu-container -->
		</div>

	</header><!-- #masthead -->

	<div class="is-mobile"><!-- div to check if it's a mobile user. Works with CSS Media Queries --></div>

	<nav id="site-navigation" class="main-navigation mobile-menu" role="navigation">
		<div class="navigation-content">
			
			<ul>
				<?php echo $menu; ?><li class="site-header-menu__item recensies-link">
						<a class="recensies <?php echo ( is_front_page() ? 'smooth-scroll' : '' ); ?>" href="<?php echo (is_front_page() ? "" : esc_url( home_url( '/' ) ) ); ?>#recensies">						Recensies
						</a>
					</li><li class="site-header-menu__item contact-now-button">
					<a class="contact-now <?php echo ( is_front_page() ? 'smooth-scroll' : '' ); ?>" href="<?php echo (is_front_page() ? "" : esc_url( home_url( '/' ) ) ); ?>#contact">
						Contact
					</a>
				</li>
			</ul>
			
		</div><!-- .navigation-content -->

	</nav><!-- #site-navigation -->

	<div id="content" class="site-content">
