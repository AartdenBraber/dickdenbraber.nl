<?php
/**
 * Template part for displaying previews of posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dick_den_Braber
 */
		$postID = $post->ID;
		$image_medium = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'dickdenbraber_medium_small' );
		$image_big = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'medium_large' );

		if( $image_big[1] > 250 && $image_big[2] > 250 ) {
			$too_small = false;
		} else {
			$too_small = true;
		}

		$str_responsive_bg = 'url(\'' . $image_medium[0] . '\') 0, url(\'' . $image_big[0] . '\') 400';


		if( in_category('tekstschrijven') )
			$is_in_tekstschrijven = true;
		else
			$is_in_tekstschrijven = false;

if( $is_in_tekstschrijven ) : ?><li id="post-<?php echo $postID; ?>" class=" <?php echo $too_small ? "image-too-small" : "" ?>">
		<div class="post tekstschrijven-publicatie">
			<div class="post__image__inside lazyload background-cover responsive-bg" data-bg="<?php echo $str_responsive_bg; ?>"></div>
			<div class="post__content">
				<h4><a class="ajax-popup-link post__content__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				<div class="post__meta">
					<?php dickdenbraber_posted_on(); ?>
				</div><!-- .post-meta -->
				<p><?php the_excerpt(); ?></p>
				<a class="post__read-more-link ajax-popup-link" href="<?php the_permalink(); ?>">Lees meer</a>
			</div>
		</div>
	</li><?php


else : ?><li id="post-<?php echo $postID; ?>" class=" <?php echo $too_small ? "image-too-small" : "" ?>">
		<div class="post">
			<a href="<?php the_permalink(); ?>" <?php echo $too_small ? 'title="Mijn excuses, voor deze publicatie heb ik helaas geen betere kwaliteit foto."' : ''; ?> class="post__image ajax-popup-link">
				<div class="post__image__inside lazyload background-cover responsive-bg" data-bg="<?php echo $str_responsive_bg; ?>"></div>
			</a>
			<div class="post__content">
				<h4><a class="ajax-popup-link post__content__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				<div class="post__meta">
					<?php dickdenbraber_posted_on(); ?>
				</div><!-- .post-meta -->
				<p><?php the_excerpt(); ?></p>
				<a class="post__read-more-link ajax-popup-link" href="<?php the_permalink(); ?>">Lees meer</a>
			</div>
		</div>
	</li><?php 
endif; 