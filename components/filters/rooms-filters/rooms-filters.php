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
				name="sortuj"
				class="rooms-filters__select h-[48px] px-4 py-3 border border-[#c4c4c4] rounded-lg font-nunito font-semibold text-[16px] leading-[20px] text-[#3d3d3d] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors appearance-none cursor-pointer"
				style="background-image: url('data:image/svg+xml,%3Csvg width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cpath d=%27M6 9L12 15L18 9%27 stroke=%27%233d3d3d%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
			>
				<option value="">Sortuj</option>
				<option value="najnowsze">Najnowsze</option>
				<option value="najpopularniejsze">Najpopularniejsze</option>
			</select>

			<select
				name="dla-kogo"
				class="rooms-filters__select h-[48px] px-4 py-3 border border-[#c4c4c4] rounded-lg font-nunito font-semibold text-[16px] leading-[20px] text-[#3d3d3d] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors appearance-none cursor-pointer"
				style="background-image: url('data:image/svg+xml,%3Csvg width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cpath d=%27M6 9L12 15L18 9%27 stroke=%27%233d3d3d%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
			>
				<option value="">Dla kogo</option>
				<option value="dla-dziewczynki">Dla dziewczynki</option>
				<option value="dla-chlopca">Dla chłopca</option>
				<option value="dla-rodzenstwa">Dla rodzeństwa</option>
			</select>

			<select
				name="wiek"
				class="rooms-filters__select h-[48px] px-4 py-3 border border-[#c4c4c4] rounded-lg font-nunito font-semibold text-[16px] leading-[20px] text-[#3d3d3d] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors appearance-none cursor-pointer"
				style="background-image: url('data:image/svg+xml,%3Csvg width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cpath d=%27M6 9L12 15L18 9%27 stroke=%27%233d3d3d%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
			>
				<option value="">Wiek dziecka</option>
				<option value="0-1">0-1 lat</option>
				<option value="2-3">2-3 lata</option>
				<option value="4-6">4-6 lat</option>
				<option value="7-12">7-12 lat</option>
				<option value="13+">13+ lat</option>
			</select>

			<select
				name="metraz"
				class="rooms-filters__select h-[48px] px-4 py-3 border border-[#c4c4c4] rounded-lg font-nunito font-semibold text-[16px] leading-[20px] text-[#3d3d3d] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors appearance-none cursor-pointer"
				style="background-image: url('data:image/svg+xml,%3Csvg width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cpath d=%27M6 9L12 15L18 9%27 stroke=%27%233d3d3d%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
			>
				<option value="">Metraż pokoju</option>
				<option value="maly">Mały (do 10m²)</option>
				<option value="sredni">Średni (10-15m²)</option>
				<option value="duzy">Duży (15-20m²)</option>
				<option value="bardzo-duzy">Bardzo duży (20m²+)</option>
			</select>

			<select
				name="kolor"
				class="rooms-filters__select h-[48px] px-4 py-3 border border-[#c4c4c4] rounded-lg font-nunito font-semibold text-[16px] leading-[20px] text-[#3d3d3d] bg-white focus:outline-none focus:border-[#E20C7B] transition-colors appearance-none cursor-pointer"
				style="background-image: url('data:image/svg+xml,%3Csvg width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cpath d=%27M6 9L12 15L18 9%27 stroke=%27%233d3d3d%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
			>
				<option value="">Kolor</option>
				<option value="bialy">Biały</option>
				<option value="szary">Szary</option>
				<option value="niebieski">Niebieski</option>
				<option value="rozowy">Różowy</option>
				<option value="zielony">Zielony</option>
				<option value="brazowy">Brązowy</option>
				<option value="czarny">Czarny</option>
			</select>

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