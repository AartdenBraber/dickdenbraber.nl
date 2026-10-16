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
<!-- if this page doesn't work, restore backup 15-10-2018 -->
<?php
get_footer(); ?>
</div><!-- #archive-page -->
