<?php
/**
 * Related styles placeholder component
 *
 * Placeholder for future gallery of inspirations in different styles.
 *
 * @package mroomy_s
 *
 * @param array $args {
 *     Optional. Array of arguments.
 *
 *     @type int    $inspiration_id Current inspiration post ID.
 *     @type string $title          Section title. Default 'Poznaj kategorie w innych stylach'.
 * }
 * @return string HTML output of the related styles placeholder.
 */
function mroomy_related_styles_placeholder( $args = array() ) {
	$defaults = array(
		'inspiration_id' => 0,
		'title'          => __( 'Poznaj kategorie w innych stylach', 'mroomy_s' ),
	);

	$args = wp_parse_args( $args, $defaults );

	ob_start();
	?>
	<section class="related-styles mt-16 px-[106px] mb-16">
		<h2 class="related-styles__title font-nunito font-extrabold text-[24px] text-[#222222] mb-12">
			<?php echo esc_html( $args['title'] ); ?>
		</h2>

		<div class="related-styles-placeholder border-2 border-dashed border-[#E0E0E0] rounded-lg bg-[#F9F9F9] p-12 text-center">
			<p class="font-nunito font-semibold text-[16px] text-[#888888]">
				Galeria inspiracji w innych stylach – wkrótce
			</p>
		</div>
	</section>
	<?php
	return ob_get_clean();
}