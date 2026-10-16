<?php
include('archive.php');
die(); // just stop rendering after this line. We don't need it. However, it doesn't hurt if the rest is still here for reviewing purposes.



/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dick_den_Braber
 */
?>
<div id="archive-page">
<?php get_header(); ?>
	<section class="top-hero  clearfix">

			<div class="hero-background-image background-cover"></div>

			<div class="hero-content">

				<main class="hero-title-container">
					<div class="hero-title-wrapper">

						<div class="hero-title-inside"> 
							<?php $str = str_replace('"', "'", get_field('slagzin') ); ?>
							<h1 class="hero-title type-me donthyphenate"><!--googleoff: index--><span class="typed-text">Publicaties</span><!--googleon: index--><span class="hide">Publicaties</span></h1>

						</div><!-- .hero-title-inside -->
					</div><!-- .hero-title-wrapper -->
				</main>

			</div><!-- .hero-content -->

		</section>

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<section class="page-content set-into-top-hero clearfix">
					<div class="entry-content content">
						<h2 class="set-into-top-hero__title">Wat wilt u graag zien?</h2>
						<ul class="publicaties-opties">
							<?php
								$selectedClass = 'button--selected';
							?>
							<li><a href="#" class="button button--dark button--selected">Alles</a></li>
							<li><a href="#" class="button button--dark <?php echo is_category('journalistiek') ? 'button--selected' : "" ?>">Alleen journalistiek</a></li>
							<li><a href="#" class="button button--dark <?php echo is_category('tekstschrijven') ? 'button--selected' : "" ?>">Alleen tekstschrijven</a></li>
						</ul><!-- .published-by-container -->
					</div><!-- .content -->
				</section><!-- section.set-into-top-hero -->

				<?php echo is_category('journalistiek') ? 'button--selected' : "not cat journalistiek" ?>

				<section class="page-content homepage-recent-posts">
					<div class="entry-content">

			<?php
			if ( have_posts() ) :

				if ( is_home() && !is_front_page() ) : ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>

				<?php
				endif;


				?>

				<ul id="posts-container" class="posts-container clearfix">

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content-preview' );

					endwhile;

					?>
				</ul>
			<?php

				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>
				
	<!-- 
				<select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'>
	<option value=""><?php echo attribute_escape(__('Select Month')); ?></option>
	<?php wp_get_archives('type=monthly&format=option&show_post_count=1'); ?> </select> -->


	<div class="blog-list-archive">

	<!-- <?php
	/**/
	$years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date)
	FROM $wpdb->posts WHERE post_status = 'publish'
	AND post_type = 'post' ORDER BY post_date DESC");
	foreach($years as $year) :
	?>
	<li><a href="<?php echo get_month_link($year); ?>"><?php echo $year; ?></a>

	    <ul class="archive-sub-menu">
	        <?    $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date)
	        FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'
	        AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
	        foreach($months as $month) :
	        ?>
	            <li><a href="<?php echo get_month_link($year, $month); ?>">

	                <?php echo date( 'F', mktime(0, 0, 0, $month) );?></a>

	            </li>

	        <?php endforeach;?>

	    </ul>

	</li>

	<?php endforeach; ?> -->

	</div>
					</div>
				</section>
			</main><!-- #main -->
		</div><!-- #primary -->
<?php
get_footer(); ?>
</div><!-- #archive-page -->
