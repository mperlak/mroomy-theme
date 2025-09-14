<?php
/**
 * Custom Mega Menu Walker for WordPress
 *
 * @package mroomy_s
 */

/**
 * Mega Menu Walker - dynamically creates mega menu from WordPress menu structure
 */
class Mroomy_Mega_Walker extends Walker_Nav_Menu {

	/**
	 * Track current mega menu context
	 */
	private $is_mega_active = false;
	private $current_column = null;
	private $column_count = 0;

	/**
	 * Start Level - begins a new level of menu
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( $depth === 0 ) {
			// Start mega menu container for first level dropdown - full width
			$output .= "\n$indent<div class=\"mega-dropdown fixed left-0 right-0 top-[89px] w-full bg-white shadow-lg invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 z-50\">\n";
			$output .= "$indent\t<div class=\"container max-w-[1440px] mx-auto px-[106px] py-8\">\n";
			$output .= "$indent\t\t<div class=\"flex gap-16\">\n";
			$this->is_mega_active = true;
			$this->column_count = 0;
		} elseif ( $depth === 1 && $this->is_mega_active ) {
			// Start a list within a column
			$output .= "\n$indent<ul class=\"flex flex-col gap-4\">\n";
		} else {
			// Regular nested menu
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}
	}

	/**
	 * End Level
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( $depth === 0 && $this->is_mega_active ) {
			// Close mega menu container
			if ( $this->current_column ) {
				$output .= "$indent\t\t\t</ul>\n";
				$output .= "$indent\t\t</div>\n";
				$this->current_column = null;
			}
			$output .= "$indent\t\t</div>\n"; // close grid
			$output .= "$indent\t</div>\n"; // close container
			$output .= "$indent</div>\n"; // close mega-dropdown
			$this->is_mega_active = false;
		} elseif ( $depth === 1 && $this->is_mega_active ) {
			$output .= "$indent</ul>\n";
		} else {
			$output .= "$indent</ul>\n";
		}
	}

	/**
	 * Start Element
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes );

		// Level 0 - Main navigation items
		if ( $depth === 0 ) {
			$class_names = $has_children ? 'group relative' : 'relative';
			$output .= $indent . '<li class="' . esc_attr( $class_names ) . '">';

			$atts = array(
				'href'  => ! empty( $item->url ) ? $item->url : '',
				'class' => 'flex items-center gap-1 text-subtitle-1 font-nunito text-neutral-text hover:text-primary transition-colors',
			);

			$output .= '<a ' . $this->build_attributes( $atts ) . '>';
			$output .= esc_html( $item->title );

			// Add chevron for items with children
			if ( $has_children ) {
				$output .= '<svg class="w-4 h-4 transition-transform group-hover:rotate-180" viewBox="0 0 16 16" fill="none">';
				$output .= '<path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
				$output .= '</svg>';
			}

			$output .= '</a>';
		}
		// Level 1 - Category headers in mega menu
		elseif ( $depth === 1 && $this->is_mega_active ) {
			// Check if this is a category header (has children) or a direct link
			if ( $has_children ) {
				// Close previous column if exists
				if ( $this->current_column ) {
					$output .= "\t\t\t\t</ul>\n";
					$output .= "\t\t\t</div>\n";
				}

				// Start new column
				$output .= "\t\t\t<div class=\"mega-menu-column\">\n";
				$output .= "\t\t\t\t<h3 class=\"font-nunito-sans font-bold text-caption-14-2 text-primary uppercase mb-4\">";
				$output .= esc_html( $item->title );
				$output .= "</h3>\n";
				$output .= "\t\t\t\t<ul class=\"flex flex-col gap-4\">\n";

				$this->current_column = $item->ID;
				$this->column_count++;
			} else {
				// Direct link in mega menu
				$output .= $indent . '<li>';
				$atts = array(
					'href'  => ! empty( $item->url ) ? $item->url : '',
					'class' => 'font-nunito font-extrabold text-body-2 text-neutral-text-subtle hover:text-primary transition-colors',
				);
				$output .= '<a ' . $this->build_attributes( $atts ) . '>';
				$output .= esc_html( $item->title );
				$output .= '</a></li>';
			}
		}
		// Level 2 - Links under category headers
		elseif ( $depth === 2 && $this->is_mega_active ) {
			$output .= $indent . '<li>';
			$atts = array(
				'href'  => ! empty( $item->url ) ? $item->url : '',
				'class' => 'font-nunito font-extrabold text-body-2 text-neutral-text-subtle hover:text-primary transition-colors',
			);
			$output .= '<a ' . $this->build_attributes( $atts ) . '>';
			$output .= esc_html( $item->title );
			$output .= '</a></li>';
		}
		// Regular menu items
		else {
			$output .= $indent . '<li>';
			$atts = array(
				'href'  => ! empty( $item->url ) ? $item->url : '',
				'class' => 'text-body-1 text-neutral-text hover:text-primary transition-colors',
			);
			$output .= '<a ' . $this->build_attributes( $atts ) . '>';
			$output .= esc_html( $item->title );
			$output .= '</a></li>';
		}
	}

	/**
	 * End Element
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		// Only close li tags for depth 0 and regular menu items
		if ( $depth === 0 ) {
			$output .= "</li>\n";
		} elseif ( $depth === 1 && $this->is_mega_active ) {
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$has_children = in_array( 'menu-item-has-children', $classes );

			// Don't close li for category headers
			if ( ! $has_children ) {
				// Already closed in start_el
			}
		} elseif ( $depth === 2 && $this->is_mega_active ) {
			// Already closed in start_el
		} else {
			// Regular items
		}
	}

	/**
	 * Helper function to build HTML attributes
	 */
	private function build_attributes( $atts ) {
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		return $attributes;
	}
}