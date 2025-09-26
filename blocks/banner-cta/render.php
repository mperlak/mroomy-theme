<?php
/**
 * Server-side render callback for mroomy/banner-cta.
 */

return static function( $attributes, $content, $block ) {
	$variant         = isset( $attributes['variant'] ) ? sanitize_key( $attributes['variant'] ) : 'normal';
	$title_html      = isset( $attributes['title'] ) ? $attributes['title'] : '';
	$subtitle_html   = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
	$button_text     = isset( $attributes['buttonText'] ) ? $attributes['buttonText'] : '';
	$button_url      = isset( $attributes['buttonUrl'] ) ? $attributes['buttonUrl'] : '';
	$button_target   = isset( $attributes['buttonTarget'] ) ? esc_attr( $attributes['buttonTarget'] ) : '_self';
	$desktop_id      = isset( $attributes['desktopImageId'] ) ? (int) $attributes['desktopImageId'] : 0;
	$desktop_url     = isset( $attributes['desktopImageUrl'] ) ? $attributes['desktopImageUrl'] : '';
	$desktop_alt     = isset( $attributes['desktopImageAlt'] ) ? sanitize_text_field( $attributes['desktopImageAlt'] ) : '';
	$mobile_id       = isset( $attributes['mobileImageId'] ) ? (int) $attributes['mobileImageId'] : 0;
	$mobile_url      = isset( $attributes['mobileImageUrl'] ) ? $attributes['mobileImageUrl'] : '';
	$mobile_alt      = isset( $attributes['mobileImageAlt'] ) ? sanitize_text_field( $attributes['mobileImageAlt'] ) : '';

	if ( $desktop_id && empty( $desktop_url ) ) {
		$desktop_src = wp_get_attachment_image_src( $desktop_id, 'full' );
		if ( $desktop_src ) {
			$desktop_url = $desktop_src[0];
		}
	}

	if ( $mobile_id && empty( $mobile_url ) ) {
		$mobile_src = wp_get_attachment_image_src( $mobile_id, 'full' );
		if ( $mobile_src ) {
			$mobile_url = $mobile_src[0];
		}
	}

	$default_button = ( 'large' === $variant ) ? __( 'Poznaj mroomy', 'mroomy_s' ) : __( 'Zobacz cennik', 'mroomy_s' );
	$cta_label      = $button_text ? $button_text : $default_button;
	$cta_url        = $button_url ? $button_url : '#';

	$align_class     = isset( $block->context['align'] ) ? 'align' . $block->context['align'] : '';
	$wrapper_classes = array_filter( array( 'banner-cta', 'banner-cta--' . $variant, $align_class ) );

	$heading_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'banner-cta-title-' ) : 'banner-cta-title';

	$media_img = '';
	if ( $desktop_url || $mobile_url ) {
		$img_id  = $desktop_url ? $desktop_id : $mobile_id;
		$img_url = $desktop_url ? $desktop_url : $mobile_url;
		$img_alt = $desktop_url ? $desktop_alt : $mobile_alt;

		$media_img = $img_id
			? wp_get_attachment_image(
				$img_id,
				'full',
				false,
				array(
					'class'       => 'banner-cta__media-img',
					'alt'         => $img_alt,
					'loading'     => 'lazy',
					'fetchpriority' => 'low',
				)
			)
			: sprintf(
				'<img class="banner-cta__media-img" src="%1$s" alt="%2$s" loading="lazy" />',
				esc_url( $img_url ),
				esc_attr( $img_alt )
			);
	}

	op_start();
	?>
	<section class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
		<div class="banner-cta__inner">
			<?php if ( $media_img ) : ?>
				<div class="banner-cta__media" aria-hidden="true">
					<?php echo $media_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<div class="banner-cta__body">
				<?php if ( $title_html ) : ?>
					<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="banner-cta__title">
						<?php echo wp_kses_post( $title_html ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $subtitle_html ) : ?>
					<div class="banner-cta__subtitle">
						<?php echo wp_kses_post( $subtitle_html ); ?>
					</div>
				<?php endif; ?>

				<div class="banner-cta__button">
					<?php
					echo mroomy_button(
						array(
							'text'    => $cta_label,
							'url'     => $cta_url,
							'target'  => $button_target,
							'variant' => 'primary',
							'size'    => 'lg',
							'chevron' => 'right',
							'class'   => 'banner-cta__cta',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
};


