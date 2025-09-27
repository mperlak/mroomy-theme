<?php
/**
 * Template part for displaying the hero section on single Inspiracja
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mroomy_s
 */

$header_picture = get_field( 'header_picture' );
$image_id = is_array( $header_picture ) ? $header_picture['ID'] : $header_picture;
?>

<section class="inspiration-hero relative h-[320px] overflow-hidden">
	<?php if ( $image_id ) : ?>
		<?php echo wp_get_attachment_image(
			absint( $image_id ),
			'full',
			false,
			array(
				'class' => 'absolute inset-0 w-full h-full object-cover',
				'loading' => 'eager',
				'fetchpriority' => 'high'
			)
		); ?>
	<?php endif; ?>

	<div class="absolute inset-0 bg-gradient-to-b from-black/30 to-transparent"></div>

	<div class="relative z-10 h-full flex flex-col justify-between px-[106px] pt-4">
		<nav class="inspiration-hero__breadcrumbs" aria-label="Breadcrumb">
			<ol class="flex items-center gap-2 text-white font-nunito font-semibold text-[14px] leading-[18px]">
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:opacity-80 transition-opacity">
						Strona główna
					</a>
				</li>
				<li aria-hidden="true">&gt;</li>
				<li>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'inspiracja' ) ); ?>" class="hover:opacity-80 transition-opacity">
						Inspiracje
					</a>
				</li>
				<li aria-hidden="true">&gt;</li>
				<li aria-current="page"><?php the_title(); ?></li>
			</ol>
		</nav>

		<h1 class="inspiration-hero__title pb-[88px] text-white font-nunito font-extrabold text-[52px] leading-tight text-center">
			<?php the_title(); ?>
		</h1>
	</div>
</section>