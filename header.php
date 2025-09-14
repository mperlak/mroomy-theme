<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mroomy_s
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header id="masthead" class="bg-white border-b border-[#E0E0E0]">
		<!-- Mobile header -->
		<div class="lg:hidden relative px-6 pt-[22px] pb-5">
			<div class="flex items-center justify-between h-6">
				<!-- Logo -->
				<div class="shrink-0">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="block">
						<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/mroomy_logo_2025_200px_wide_no_safespace.png' ) ); ?>"
							 alt="<?php bloginfo( 'name' ); ?>"
							 class="h-6 w-auto"
							 style="max-width: 127px;">
					</a>
				</div>

				<!-- Mobile right icons -->
				<div class="flex items-center gap-6 relative">
					<!-- Search -->
					<button class="icon-link size-6 shrink-0" aria-label="Search">
						<?php include get_template_directory() . '/assets/icons/search.svg'; ?>
					</button>

					<!-- Cart -->
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="relative shrink-0">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-link flex size-6" aria-label="Cart">
							<?php include get_template_directory() . '/assets/icons/basket.svg'; ?>
						</a>
						<?php
						$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
						if ( $cart_count > 0 ) : ?>
						<span class="absolute -top-1 -right-1 min-w-4 h-4 px-[5px] bg-primary-pressed rounded-lg flex items-center justify-center">
							<span class="font-nunito-sans font-bold text-[10px] leading-none text-white uppercase"><?php echo esc_html( $cart_count ); ?></span>
						</span>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<!-- Mobile menu toggle -->
					<button class="icon-link size-6 shrink-0" aria-label="Menu" id="mobile-menu-toggle">
						<?php include get_template_directory() . '/assets/icons/menu-hamburger.svg'; ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Desktop header -->
		<div class="hidden lg:flex h-[88px] relative">
			<div class="container max-w-[1440px] mx-auto px-8 lg:px-[106px] h-full">
				<div class="flex items-center justify-between h-full">
					<!-- Logo -->
					<div class="shrink-0">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="block">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/mroomy_logo_2025_200px_wide_no_safespace.png' ) ); ?>"
								 alt="<?php bloginfo( 'name' ); ?>"
								 class="h-[27px] w-auto">
						</a>
					</div>

					<!-- Navigation -->
					<nav class="flex flex-1 items-center justify-start gap-10 ml-16 static">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'container' => false,
								'menu_class' => 'flex items-center gap-10 list-none',
								'fallback_cb' => false,
								'walker' => new Mroomy_Mega_Walker(),
							)
						);
						?>
					</nav>

					<!-- Right side icons -->
					<div class="flex items-center gap-6 lg:gap-[46px]">
						<!-- Search -->
						<button class="icon-link w-6 h-6 hover:opacity-70 transition-opacity" aria-label="Search">
							<?php include get_template_directory() . '/assets/icons/search.svg'; ?>
						</button>

						<!-- Cart -->
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="relative">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-link flex w-6 h-6 hover:opacity-70 transition-opacity" aria-label="Cart">
								<?php include get_template_directory() . '/assets/icons/basket.svg'; ?>
								<?php
								$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
								if ( $cart_count > 0 ) : ?>
								<span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-[5px] bg-primary rounded-lg flex items-center justify-center">
									<span class="font-nunito-sans text-caption-10-2 text-white uppercase"><?php echo esc_html( $cart_count ); ?></span>
								</span>
								<?php endif; ?>
							</a>
						</div>
						<?php endif; ?>

						<!-- Language selector -->
						<div class="flex items-center gap-1 cursor-pointer hover:opacity-70 transition-opacity">
							<span class="text-subtitle-1 font-nunito text-neutral-text">PL</span>
							<div class="w-4 h-4 flex items-center justify-center">
								<?php include get_template_directory() . '/assets/icons/chevron-down.svg'; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
