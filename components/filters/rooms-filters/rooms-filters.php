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
			<select
				name="pokoj"
				class="rooms-filters__select w-[113px] h-[48px] px-4 py-3 border border-[#E0E0E0] rounded-lg font-nunito text-[14px] text-[#3c3c3b] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors"
			>
				<option value="">Pokój</option>
				<option value="pokoj-dziewczynki">Pokój dziewczynki</option>
				<option value="pokoj-chlopca">Pokój chłopca</option>
				<option value="pokoj-rodzenstwa">Pokój rodzeństwa</option>
				<option value="pokoj-niemowlaka">Pokój niemowlaka</option>
			</select>

			<select
				name="wiek"
				class="rooms-filters__select w-[134px] h-[48px] px-4 py-3 border border-[#E0E0E0] rounded-lg font-nunito text-[14px] text-[#3c3c3b] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors"
			>
				<option value="">Wiek dziecka</option>
				<option value="0-1">0-1 lat</option>
				<option value="2-3">2-3 lata</option>
				<option value="4-6">4-6 lat</option>
				<option value="7-12">7-12 lat</option>
				<option value="13+">13+ lat</option>
			</select>

			<select
				name="powierzchnia"
				class="rooms-filters__select w-[165px] h-[48px] px-4 py-3 border border-[#E0E0E0] rounded-lg font-nunito text-[14px] text-[#3c3c3b] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors"
			>
				<option value="">Powierzchnia</option>
				<option value="maly">Mały (do 10m²)</option>
				<option value="sredni">Średni (10-15m²)</option>
				<option value="duzy">Duży (15-20m²)</option>
				<option value="bardzo-duzy">Bardzo duży (20m²+)</option>
			</select>

			<select
				name="kolor"
				class="rooms-filters__select w-[172px] h-[48px] px-4 py-3 border border-[#E0E0E0] rounded-lg font-nunito text-[14px] text-[#3c3c3b] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors"
			>
				<option value="">Kolor wiodący</option>
				<option value="bialy">Biały</option>
				<option value="szary">Szary</option>
				<option value="niebieski">Niebieski</option>
				<option value="rozowy">Różowy</option>
				<option value="zielony">Zielony</option>
				<option value="brazowy">Brązowy</option>
				<option value="czarny">Czarny</option>
			</select>

			<select
				name="styl"
				class="rooms-filters__select w-[107px] h-[48px] px-4 py-3 border border-[#E0E0E0] rounded-lg font-nunito text-[14px] text-[#3c3c3b] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors"
			>
				<option value="">Styl</option>
				<option value="skandynawski">Skandynawski</option>
				<option value="nowoczesny">Nowoczesny</option>
				<option value="klasyczny">Klasyczny</option>
				<option value="minimalistyczny">Minimalistyczny</option>
				<option value="boho">Boho</option>
			</select>

			<button
				type="submit"
				class="rooms-filters__button w-[183px] h-[48px] px-6 py-3 bg-[#E20C7B] hover:bg-[#830747] text-white font-nunito font-bold text-[14px] rounded-lg transition-colors"
			>
				<?php echo esc_html( $args['button_text'] ); ?>
			</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}