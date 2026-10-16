<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dick_den_Braber
 */

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post single-page content-page">
		<section class="top-hero  clearfix">

			<div class="hero-background-image background-cover"></div>

			<div class="hero-content">

				<main class="hero-title-container">
					<div class="hero-title-wrapper">

						<div class="hero-title-inside"> 
							<h1 class="hero-title type-me donthyphenate"><!--googleoff: index--><span class="typed-text"><?php the_title(); ?></span><!--googleon: index--><span class="hide"><?php the_title(); ?></span></h1>
							<?php if(strlen($str) > 0)
								echo '<p class="tag-title">' . $str . '</p>';
							?>

						</div><!-- .hero-title-inside -->
					</div><!-- .hero-title-wrapper -->
				</main>

			</div><!-- .hero-content -->

		</section>

		<section class="post__content">

			<div class="entry-content">
				<?php
					the_content( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'dickdenbraber' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dickdenbraber' ),
						'after'  => '</div>',
					) );
				?>

			</div><!-- .entry-content -->

			<!-- <footer class="entry-footer">
				<?php dickdenbraber_entry_footer(); ?>
			</footer> .entry-footer -->
		</section>
	</div>
</article><!-- #post-## -->