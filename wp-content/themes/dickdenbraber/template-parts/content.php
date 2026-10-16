<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dick_den_Braber
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php 

		$postID = $post->ID;
		$image_small = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'dickdenbraber_medium_small' );
		$image_medium = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'medium_large' );
		$image_big = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'full' );

		if( $image_big[1] > 300 && $image_big[2] > 300 ) {
			$too_small = false;
		} else {
			$too_small = true;
		}

		// like this: url(...jpg) min-width in px, -
		$str_responsive_bg = 'url(\'' . $image_small[0] . '\') 0, url(\'' . $image_medium[0] . '\') 400, url(\'' . $image_big[0] . '\') 700';

	?>
	<div class="post single-page <?php echo $too_small ? "image-too-small" : "" ?>">
		<section class="top-hero">
			<div class="hero-background-image lazyload background-cover responsive-bg" data-bg="<?php echo $str_responsive_bg; ?>">
		</section>

		<section class="post__content">
			<form method="post" action="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">
				<button title="Sluiten - Ga naar de vorige pagina" type="submit" class="mfp-close">×</button>
			</form>

			<header class="entry-header">

				<?php
				$str = str_replace('"', "'", get_the_title() );
				if ( is_single() ) : ?>

						<h1 class="entry-title type-me donthyphenate" style="margin-right: 48px;" data-type-speed=".7"><!--googleoff: index--><span class="typed-text"><?php echo $str; ?></span><!--googleon: index--><span class="hide"><?php echo preg_replace("/\[del\][^)]+\\[del\]/","", $str); ?></span></h1>

				<?php else : 
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;

				if ( 'post' === get_post_type() ) : ?>
				<div class="post__meta">
					<?php dickdenbraber_posted_on() ?>
					<!-- <span class="extra-text">Geplaatst op</span>
					<span class="post__meta__date"><?php the_time('j F Y'); ?></span> <span class="extra-text">onder</span>
					<span class="post__meta__category"><?php the_category('<span class="post__meta__category__separator extra-text"></span>') ?></span><span class="extra-text">.</span> -->
				</div><!-- .post-meta -->
				<?php
				endif; ?>
			</header><!-- .entry-header -->

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

				<?php if($too_small) : ?>
					<p style="margin-top: 20px; font-size: .8em;">Mijn excuses, voor deze publicatie heb ik helaas geen betere kwaliteit foto.</p>
				<?php endif; ?> 

				<div class="labels">
					<?php the_tags('<ul class="the_tags"><li class="tag">', '</li><li class="tag">', '</li></ul>'); ?>
				</div>

			</div><!-- .entry-content -->

			<!-- <footer class="entry-footer">
				<?php dickdenbraber_entry_footer(); ?>
			</footer> .entry-footer -->
		</section>

		<?php if( has_category('tekstschrijven') ): ?>
			<section class="page-content postsPage-call-to-action parallax" data-speed=".8">
				<div class="parallax-bg background-cover lazyload" data-bg="linear-gradient( rgba(181,143,0, .9), rgba(181,143,0, .9) ), url('<?php echo get_field('testimonials_achtergrondafbeelding', get_option( 'page_on_front' ))['url']; ?>')"></div>

				<div class="parallax-content entry-content content full-width call-to-action">
					<div class="call-to-action__col">
						<h3><?php the_field('call-to-action__titel', get_option( 'page_for_posts' )); ?></h3>
						<p><?php the_field('call-to-action__aanvullende_tekst', (int)get_option( 'page_for_posts' )); ?></p>
					</div>
					<div class="call-to-action__col call-to-action__col--small">
						<a href="<?php echo get_home_url(); ?>/#contact" class="button button--responsive-big button-big smooth-scroll"><?php the_field('call-to-action__button-tekst', get_option( 'page_for_posts' )); ?></a>
					</div>
				</div>

			</section><!-- .call-to-action -->
		<?php endif; ?>
	</div>
</article><!-- #post-## -->
