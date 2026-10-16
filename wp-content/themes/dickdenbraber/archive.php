<?php
/*
Template Name: Archive
*/
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
<?php get_header();?>

<div id="archive-page" class="archive-page">
	<section class="top-hero  clearfix">

			<div class="hero-background-image background-cover"></div>

			<div class="hero-content">

				<main class="hero-title-container">
					<div class="hero-title-wrapper">

						<div class="hero-title-inside"> 
							<?php

								if( is_tag() ) {
									$str = 'Met als label <br/><em>' . single_tag_title('', false) . '</em>';
								}
								if( is_date() ) {
									$str = 'Uit het archief <br/><em>' . get_the_archive_title() . '</em>';
								}

							?>
							<h1 class="hero-title type-me donthyphenate"><!--googleoff: index--><span class="typed-text">Publicaties</span><!--googleon: index--><span class="hide">Publicaties</span></h1>
							<?php if(strlen($str) > 0)
								echo '<p class="tag-title">' . $str . '</p>';
							?>

						</div><!-- .hero-title-inside -->
					</div><!-- .hero-title-wrapper -->
				</main>

			</div><!-- .hero-content -->

		</section>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<section id="content" class="page-content set-into-top-hero clearfix">
					<div class="entry-content content">
						<h2 class="set-into-top-hero__title">Wat wilt u graag zien?</h2>
						<?php function show_archive_options() { ?>
						<ul class="publicaties-opties">
							<li><a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="button button--dark <?php echo !is_category() ? 'button--selected' : "" ?>">Alles</a></li>
							<li><a href="<?php echo get_category_link( get_cat_ID('journalistiek') ); ?>" class="button button--dark <?php echo is_category('journalistiek') ? 'button--selected' : "" ?>">Alleen journalistiek</a></li>
							<li><a href="<?php echo get_category_link( get_cat_ID('tekstschrijven') ); ?>" class="button button--dark <?php echo is_category('tekstschrijven') ? 'button--selected' : "niet journalistiek" ?>">Alleen tekstschrijven</a></li>
						</ul><!-- .published-by-container -->

						<?php get_search_form(); ?>
						<?php }
							show_archive_options();
						?>

						<?php /*wp_get_archives(array(
							'type'=>'yearly', 
							'show_post_count'=>true
						));*/ ?>

					</div><!-- .content -->
				</section><!-- section.set-into-top-hero -->

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

				//the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>
						<h3 style="text-align: center; padding-top: 20px;">Verfijn de resultaten</h3>
						<?php show_archive_options(); ?>
						<a class="show-archive-button button" tabindex="0">Archief weergeven op jaar en maand</a>
						<div class="archive-list">

							<?php
							setlocale(LC_TIME, 'nl_NL');
							$year_prev = null;

							$months = $wpdb->get_results(	"SELECT DISTINCT MONTH( post_date ) AS month ,
															YEAR( post_date ) AS year,
															COUNT( id ) as post_count FROM $wpdb->posts
															WHERE post_status = 'publish' and post_date <= now( )
															and post_type = 'post'
															GROUP BY month , year
															ORDER BY post_date DESC");

							foreach($months as $month) :
								$year_current = $month->year;
								if ($year_current != $year_prev) {
									if ($year_prev != null) { ?>
										</ul>
									<?php } ?>
									<h3 class="archive-list__title"><a href="<?php bloginfo('url') ?>/<?php echo $month->year; ?>"><?php echo $month->year; ?></a></h3>
									<ul class="archive-list__months">
								<?php } ?><li>
									<a href="<?php bloginfo('url') ?>/<?php echo $month->year; ?>/<?php echo date("m", mktime(0, 0, 0, $month->month, 1, $month->year)) ?>">
										<span class="archive-month"><?php echo strftime("%B", mktime(0, 0, 0, $month->month, 1, $month->year)) ?></span>
										<span class="archive-count">(<?php echo $month->post_count; ?> publicatie<?php echo $month->post_count > 1 ? "s" : ""; ?>)</span>
									</a>
								</li><?php 
								$year_prev = $year_current;
							endforeach; ?>
							</ul>

							<h3 style="text-align: center;">Labels</h3>
							<?php 
							wp_tag_cloud(array(
								'smallest' => 12
							)); 
							?>

						</div>
					</div>
				</section>

			</main><!-- #main -->
		</div><!-- #primary -->



<?php
get_footer(); ?>
</div><!-- #archive-page -->
