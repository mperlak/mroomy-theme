<?php
/**
 * Template part for displaying the rooms grid on single Inspiracja
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mroomy_s
 */

global $post;
$inspiration_id = $post->ID;
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$rooms_query = mroomy_get_inspiration_rooms_query( $inspiration_id, array( 'paged' => $paged ) );

if ( ! $rooms_query || ! $rooms_query->have_posts() ) {
	return;
}
?>

<section class="inspiration-rooms-grid mt-[88px] px-[107px]">
	<?php if ( $rooms_query->have_posts() ) : ?>
		<div class="inspiration-rooms-grid__notification mb-10">
			<p class="font-nunito font-semibold text-[14px] text-[#3c3c3b]">
				Znaleziono <?php echo esc_html( $rooms_query->found_posts ); ?> projektów
			</p>
		</div>

		<div class="inspiration-rooms-grid__grid grid grid-cols-3 gap-8">
			<?php
			while ( $rooms_query->have_posts() ) :
				$rooms_query->the_post();

				if ( function_exists( 'mroomy_room_tile' ) ) {
					mroomy_room_tile(
						array(
							'post_id'      => get_the_ID(),
							'size'         => 'large',
							'show_actions' => false,
						)
					);
				}
			endwhile;
			?>
		</div>

		<?php
		mroomy_save_viewed_rooms( $rooms_query, $inspiration_id );
		wp_reset_postdata();
		?>

		<?php if ( $rooms_query->max_num_pages > 1 ) : ?>
			<div class="inspiration-rooms-grid__pagination mt-16">
				<?php
				echo paginate_links(
					array(
						'base'      => get_pagenum_link( 1 ) . '%_%',
						'format'    => 'page/%#%/',
						'current'   => max( 1, $paged ),
						'total'     => $rooms_query->max_num_pages,
						'mid_size'  => 2,
						'prev_text' => __( 'Poprzednia', 'mroomy_s' ),
						'next_text' => __( 'Następna', 'mroomy_s' ),
						'type'      => 'list',
					)
				);
				?>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="inspiration-rooms-grid__empty text-center py-16">
			<p class="font-nunito font-semibold text-[18px] text-[#888888]">
				Brak pokoi w tej kategorii.
			</p>
		</div>
	<?php endif; ?>
</section>