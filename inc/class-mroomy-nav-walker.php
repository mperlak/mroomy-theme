<?php
/**
 * Custom Navigation Menu Walker
 *
 * @package mroomy_s
 */

/**
 * Custom Navigation Walker for Mroomy theme
 *
 * Adds Tailwind CSS classes and custom markup for navigation menus
 */
class Mroomy_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu absolute left-0 top-full mt-2 bg-white shadow-lg rounded-lg p-2 min-w-[200px] hidden group-hover:block\">\n";
	}

	/**
	 * Ends the list after the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function end_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array('menu-item-has-children', $classes);

		$class_names = ' class="' . ($has_children ? 'group relative' : 'relative') . '"';

		$output .= $indent . '<li' . $class_names . '>';

		$attributes = !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$item_output = '<a' . $attributes . ' class="flex items-center gap-1 text-subtitle-1 font-nunito text-neutral-text hover:text-primary transition-colors whitespace-nowrap capitalize">';
		$item_output .= apply_filters('the_title', $item->title, $item->ID);

		// Add dropdown arrow for parent items
		if ($has_children && $depth == 0) {
			ob_start();
			include get_template_directory() . '/assets/icons/chevron-down.svg';
			$svg_content = ob_get_clean();
			$item_output .= '<div class="w-4 h-4 flex items-center justify-center">' . $svg_content . '</div>';
		}

		$item_output .= '</a>';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function end_el(&$output, $item, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}