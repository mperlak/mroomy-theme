<?php
/**
 * Template for displaying single Inspiracja posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mroomy_s
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		// Hero section with image and title
		get_template_part( 'template-parts/inspiration', 'hero' );

		// Intro text section
		get_template_part( 'template-parts/inspiration', 'intro-text' );

		// Rooms filters
		if ( function_exists( 'mroomy_rooms_filters' ) ) {
			echo '<div class="mt-[64px]">';
			echo '<div class="container mx-auto px-4">';
			echo mroomy_rooms_filters(
				array(
					'context'     => 'inspiration',
					'category_id' => get_the_ID(),
				)
			);
			echo '</div>';
			echo '</div>';
		}

		// Rooms grid with pagination
		get_template_part( 'template-parts/inspiration', 'rooms-grid' );

		// Related styles section (placeholder)
		if ( function_exists( 'mroomy_related_styles_placeholder' ) ) {
			echo mroomy_related_styles_placeholder(
				array(
					'inspiration_id' => get_the_ID(),
				)
			);
		}

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer();