<?php
/**
 * The template for displaying the homepage
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dick_den_Braber
 */
date_default_timezone_set('Europe/Amsterdam');
get_header(); ?>

	<section class="top-hero clearfix">

		<div class="hero-background-image background-cover"></div>

		<div class="hero-content">

			<main class="hero-title-container">
				<div class="hero-title-wrapper">
					<div class="portrait-container">
						<?php
							$portretfoto = get_field('portretfoto')['id'];
							$size = 'medium';
							$image = wp_get_attachment_image_src( $portretfoto, $size );
						?>
						<img class="portrait" src="<?php echo $image[0]; ?>" alt="Portret van Dick den Braber: blond haar, blauwe ogen en een bril." />
					</div>

					<div class="hero-title-inside">
						<p id="hero-greeting" class="hero-greeting"><?php the_field('welkomsttekst_middag'); ?></p>  
						
						<script>	
							(function setHeroGreeting() {
								var d = new Date(),
									tijd = d.getHours(),
									msg = '<?php the_field('welkomsttekst_middag'); ?>';

								if(tijd < 12)
									msg = '<?php the_field('welkomsttekst_ochtend'); ?>';
								else if (tijd > 18)
									msg = '<?php the_field('welkomsttekst_avond'); ?>';

								document.getElementById('hero-greeting').innerHTML = msg;

								// call this every minute, just for fun
								setTimeout(setHeroGreeting, 60 * 1000);
							})();
						</script>

						<?php $str = str_replace('"', "'", get_field('slagzin') ); ?>
						<h1 class="hero-title type-me donthyphenate"><!--googleoff: index--><span class="typed-text"><?php echo $str; ?></span><!--googleon: index--><span class="hide"><?php echo preg_replace("/\[del\][^)]+\\[del\]/","", $str); ?></span></h1>

					</div><!-- .hero-title-inside -->
				</div><!-- .hero-title-wrapper -->
			</main>

		</div><!-- .hero-content -->

	</section>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="page-content set-into-top-hero clearfix">
				<div class="entry-content content">
					<h2 class="set-into-top-hero__title"><?php the_field('published_by_text'); ?></h2>
					<ul class="published-by">
					<?php //add me in
					//Fields
					//published_by = Gallery Field
					$images = get_field('published_by');
					if( $images ): ?>
					 	<?php foreach( $images as $image ): 
							$link = get_field('verwijzing', $image['ID']); 
						 	?><li class="published-by__item">
						 		<a class="published-by__item__link grayscale grayscale-undo-on-hover " href="<?php echo $link; ?>">
							 		<img class="published-by__item__link__image lazyload" data-src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							 	</a>
						 	</li><?php endforeach; ?>
					<?php else : ?>
						<p>Voeg hier logo's toe via pagina Home » Gepubliceerd door...</p>
					<?php endif; ?>
					</ul><!-- .published-by-container -->
				</div><!-- .content -->
			</section><!-- section.set-into-top-hero -->

			<section class="page-content homepage-main-content">
				<ul id="category-picker-container">
					<li class="category-picker left active" id="category-1-button">
						<a class="category-picker__button">
							<h3><?php the_field('onderdeel_1__titel'); ?></h3>
							<small><?php the_field('onderdeel_1__omschrijving'); ?></small>
						</a>
					</li><li class="category-picker right" id="category-2-button">
						<a class="category-picker__button">
							<h3><?php the_field('onderdeel_2__titel'); ?></h3>
							<small><?php the_field('onderdeel_2__omschrijving'); ?></small>
						</a>
					</li>
				</ul>
				
				<div class="entry-content content">	
					<div class="category-display-carousel owl-carousel">
						<div class="category-display" id="category-2">
							<h3 class="screen-reader-text"><?php the_field('onderdeel_1__titel'); ?></h3>
							<?php the_field('onderdeel_1'); ?>
							<p class="button-holder">
								<a href="<?php echo get_category_link( get_cat_ID('tekstschrijven') ); ?>" class="button button--responsive-big button--dark"><?php the_field('onderdeel_1__tekst_voor_knop'); ?></a>
							</p>


						</div>
						<div class="category-display" id="category-1">
							<h3 class="screen-reader-text"><?php the_field('onderdeel_2__titel'); ?></h3>
							<?php the_field('onderdeel_2'); ?>
							<p class="button-holder">
								<a href="<?php echo get_category_link( get_cat_ID('journalistiek') ); ?>" class="button button--responsive-big button--dark"><?php the_field('onderdeel_2__tekst_voor_knop'); ?></a>
							</p>
						</div>
					</div><!-- .category-display-container -->
				</div><!-- .content -->
			</section><!-- section.home-intro -->

			<section class="page-content homepage-call-to-action parallax" data-speed=".8">
				<div class="parallax-bg background-cover lazyload" data-bg="linear-gradient( rgba(181,143,0, .9), rgba(181,143,0, .9) ), url('<?php echo get_field('testimonials_achtergrondafbeelding')['url']; ?>')"></div>

				<div class="parallax-content entry-content content full-width call-to-action">
					<div class="call-to-action__col">
						<h3><?php the_field('call-to-action__titel'); ?></h3>
						<p><?php the_field('call-to-action__aanvullende_tekst'); ?></p>
					</div>
					<div class="call-to-action__col call-to-action__col--small">
						<a href="#contact" class="button button--responsive-big button-big smooth-scroll"><?php the_field('call-to-action__button-tekst'); ?></a>
					</div>
				</div>

			</section><!-- .homepage-call-to-action -->

			<section class="page-content homepage-recent-posts">
				<div class="entry-content">
					<h2>Recente publicaties</h2>
					<ul class="posts-container clearfix full-width-posts">
					<?php 


						$firstPosts = array();
						$wpb_all_query = new WP_Query(
							array(
								'post_type'=>'post', 
								'post_status'=>'publish', 
								'posts_per_page'=>1, 
								'category_name' => 'tekstschrijven'
							)
						); 

						while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
							get_template_part( 'template-parts/content-preview' );
							$firstPosts[] = $post->ID;
						endwhile;
						wp_reset_query();

						?>
					</ul>
					<ul class="posts-container clearfix">
						<?php
						$wpb_all_query2 = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>4, 'post__not_in' => $firstPosts)); 

						while ( $wpb_all_query2->have_posts() ) : $wpb_all_query2->the_post(); 
							get_template_part( 'template-parts/content-preview' );
						endwhile;
						wp_reset_query();
					?>

					</ul>
					<p class="button-holder">
						<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="button button--responsive-big button--dark">Bekijk alle publicaties</a>
					</p>
				</div><!-- .entry-content -->
			</section><!-- homepage-recent-posts -->

			<section id="recensies" class="page-content homepage-testimonials parallax" data-speed=".4">
				<div class="parallax-bg background-cover lazyload" data-bg="linear-gradient( rgba(5,55,98, .9), rgba(5,55,98, .9) ), url('<?php echo get_field('testimonials_achtergrondafbeelding')['url']; ?>')"></div>

				<div class="parallax-content entry-content content full-width">
					<h2><?php the_field('testimonials_title'); ?></h2>

					<div class="owl-carousel-container">
						<ul class="owl-carousel testimonials-carousel center-carousel">	
						<?php

					// check if the repeater field has rows of data
					if( have_rows('recensies') ):

					 	// loop through the rows of data
					    while ( have_rows('recensies') ) : the_row(); ?>

							<li>
								<div class="testimonial-item">
								
									<blockquote class="testimonial-citate"><?php the_sub_field('citaat'); ?></blockquote>
									<footer class="testimonial-author">
										<?php
											$foto = get_sub_field('foto')['id'];
											$size = 'medium';
											$image = wp_get_attachment_image_src( $foto, $size );

											if($foto){
												echo '<div class="testimonial-author-photo background-cover lazyload" data-bg="url(' . $image[0] . ')"></div>';
											}
										?>

										<p class="testimonial-author-name"><?php the_sub_field('naam') ?></p>
									</footer>
							    </div>
					        </li>

					   <?php endwhile;

					else : ?>

					    <p>Nog geen recensies toegevoegd. Voeg ze toe via pagina Home » Recensies</p>

					<?php endif; ?>

						</ul>
					</div><!-- .owl-carousel-container -->

				</div><!-- .content -->
			</section><!-- section.intro -->


		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
