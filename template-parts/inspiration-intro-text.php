<?php
/**
 * Template part for displaying the intro text section on single Inspiracja
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mroomy_s
 */
$header_text = get_field( 'header_text' );

if ( empty( $header_text ) ) {
	return;
}
?>

<section class="inspiration-intro-text mt-12">
	<div class="container mx-auto px-4">
		<div class="inspiration-intro-text__inner max-w-[1228px] mx-auto">
			<div class="font-nunito font-semibold text-[20px] leading-[26px] text-[#333333]">
				<?php echo wp_kses_post( $header_text ); ?>
			</div>
		</div>
	</div>
</section>