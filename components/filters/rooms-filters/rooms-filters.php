<?php
/**
 * Reusable rooms filters component
 *
 * @package mroomy_s
 *
 * @param array $args {
 *     Optional. Array of arguments.
 *
 *     @type string $context          Context: 'inspiration', 'category', 'archive'. Default 'inspiration'.
 *     @type int    $category_id      Pre-filter by category ID. Default null.
 *     @type array  $default_filters  Default filter values. Default empty array.
 *     @type bool   $show_results     Show results notification bar. Default false.
 *     @type string $action_url       Form action URL. Default current URL.
 *     @type string $button_text      Submit button text. Default 'Filtruj'.
 * }
 * @return string HTML output of the filters component.
 */
function mroomy_rooms_filters( $args = array() ) {
	$defaults = array(
		'context'         => 'inspiration',
		'category_id'     => null,
		'default_filters' => array(),
		'show_results'    => false,
		'action_url'      => '',
		'button_text'     => 'Filtruj',
	);

	$args = wp_parse_args( $args, $defaults );

	$action_url = $args['action_url'] ? $args['action_url'] : '';

	ob_start();
	?>
	<div class="rooms-filters" data-context="<?php echo esc_attr( $args['context'] ); ?>">
		<form class="rooms-filters__form flex items-center gap-4 flex-wrap" method="get" action="<?php echo esc_url( $action_url ); ?>">
			<?php
			mroomy_dropdown_select(
				array(
					'name'        => 'sortuj',
					'placeholder' => 'Sortuj',
					'options'     => array(
						'najnowsze'          => 'Najnowsze',
						'najpopularniejsze'  => 'Najpopularniejsze',
					),
				)
			);

			mroomy_dropdown_select(
				array(
					'name'        => 'dla-kogo',
					'placeholder' => 'Dla kogo',
					'options'     => array(
						'dla-dziewczynki' => 'Dla dziewczynki',
						'dla-chlopca'     => 'Dla chłopca',
						'dla-rodzenstwa'  => 'Dla rodzeństwa',
					),
				)
			);

			mroomy_dropdown_select(
				array(
					'name'        => 'wiek',
					'placeholder' => 'Wiek dziecka',
					'options'     => array(
						'0-1'  => '0-1 lat',
						'2-3'  => '2-3 lata',
						'4-6'  => '4-6 lat',
						'7-12' => '7-12 lat',
						'13+'  => '13+ lat',
					),
				)
			);

			mroomy_dropdown_select(
				array(
					'name'        => 'metraz',
					'placeholder' => 'Metraż pokoju',
					'options'     => array(
						'maly'        => 'Mały (do 10m²)',
						'sredni'      => 'Średni (10-15m²)',
						'duzy'        => 'Duży (15-20m²)',
						'bardzo-duzy' => 'Bardzo duży (20m²+)',
					),
				)
			);

			mroomy_dropdown_select(
				array(
					'name'        => 'kolor',
					'placeholder' => 'Kolor',
					'options'     => array(
						'bialy'      => 'Biały',
						'szary'      => 'Szary',
						'niebieski'  => 'Niebieski',
						'rozowy'     => 'Różowy',
						'zielony'    => 'Zielony',
						'brazowy'    => 'Brązowy',
						'czarny'     => 'Czarny',
					),
				)
			);
			?>

			<button
				type="button"
				class="rooms-filters__all-filters flex items-center justify-center gap-2 h-[48px] px-3 py-3 border-2 border-[#ede2dc] rounded-lg bg-white font-nunito font-extrabold text-[16px] leading-[20px] text-primary hover:bg-gray-50 transition-colors cursor-pointer"
			>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 6h18M8 12h8M11 18h2" stroke="#e20c7b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span>Wszystkie filtry</span>
			</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}