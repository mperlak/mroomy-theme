<?php
/**
 * Mobile Menu Walker
 * 
 * Custom walker for mobile navigation menu
 *
 * @package mroomy_s
 */

class Mroomy_Mobile_Walker extends Walker_Nav_Menu {

    /**
     * Stores current level-0 parent context so we can annotate its submenu.
     */
    private $current_parent_title = '';
    private $current_parent_url = '';

    /**
     * Start the list before the elements are added.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );

        // Depth 0 -> first submenu level (shown as dedicated second-level view)
        if ( $depth === 0 ) {
            $parent_title_attr = esc_attr( $this->current_parent_title );
            $parent_url_attr = esc_url( $this->current_parent_url );
            $output .= "\n{$indent}<ul class=\"mobile-submenu hidden space-y-6 pl-0\" data-parent-title=\"{$parent_title_attr}\" data-parent-url=\"{$parent_url_attr}\">\n";
        }
        // Depth 1 -> list under a section header
        elseif ( $depth === 1 ) {
            $output .= "\n{$indent}<ul class=\"flex flex-col gap-4 pl-0\">\n";
        }
        else {
            $output .= "\n{$indent}<ul>\n"; // default fallback
        }
    }
    
    /**
     * End the list after the elements are added.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "{$indent}</ul>\n";
    }
    
    /**
     * Start the element output.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        $id_attr = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id_attr = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';
        
        $output .= $indent . '<li' . $id_attr . $class_names .'>';
        
        $attributes = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );
        
        // Root items
        if ( $depth === 0 ) {
            // Remember the parent for the following start_lvl call
            $this->current_parent_title = $item->title;
            $this->current_parent_url = ! empty( $item->url ) ? $item->url : '';

            // Figma: Subtitle-2 (20/26) ExtraBold dla głównych pozycji
            $extra_classes = 'font-nunito font-extrabold text-[20px] leading-[26px] text-neutral-text';
            $item_output = isset($args->before) ? $args->before : '';
            $item_output .= '<a' . $attributes . ' class="inline-flex items-center gap-2 py-0 px-0 ' . $extra_classes . ' hover:text-primary transition-colors">';
            $item_output .= ( isset($args->link_before) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset($args->link_after) ? $args->link_after : '' );

            if ( $has_children ) {
                $item_output .= '<span class="inline-flex w-4 h-4 items-center justify-center text-primary" aria-hidden="true">';
                $item_output .= file_get_contents( get_template_directory() . '/assets/icons/chevron-right.svg' );
                $item_output .= '</span>';
            }

            $item_output .= '</a>';
            $item_output .= isset($args->after) ? $args->after : '';
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
        // Depth 1: section headers or direct links inside submenu
        elseif ( $depth === 1 ) {
            $is_view_all = in_array( 'view-all', (array) $item->classes, true );

            if ( $is_view_all ) {
                // Special "Zobacz wszystkie ..." link per Figma
                $output .= '<div class="mobile-submenu-viewall mb-8">';
                $chevron_right_svg = '';
                if ( file_exists( get_template_directory() . '/assets/icons/chevron-right.svg' ) ) {
                    ob_start();
                    include get_template_directory() . '/assets/icons/chevron-right.svg';
                    $chevron_right_svg = ob_get_clean();
                }
                // Match main-level chevron layout: chevron inside container with slight baseline alignment
                $output .= '<a' . $attributes . ' class="font-nunito text-body-2 text-neutral-text hover:text-primary inline-flex items-center gap-1 align-middle">';
                $output .= apply_filters( 'the_title', $item->title, $item->ID );
                $output .= '<span class="w-4 h-4 inline-flex items-center justify-center">' . $chevron_right_svg . '</span>';
                $output .= '</a>';
                $output .= '</div>';
            }
            elseif ( $has_children ) {
                // Section header (uppercase, pink), with top margin 32px between sections
                $output .= '<div class="mt-8 font-nunito-sans font-bold text-caption-12-2 text-primary uppercase mb-4">' . esc_html( $item->title ) . '</div>';
                // The children list will be printed by start_lvl at depth 1
            } else {
                // Sub-item using Tailwind preset: body-2 (16/20, 800)
                $link_classes = 'font-nunito text-body-2 text-neutral-text hover:text-primary transition-colors m-0 p-0';
                $item_output = '<a' . $attributes . ' class="' . $link_classes . '">';
                $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
                $item_output .= '</a>';
                $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
        }
        // Depth 2: simple links
        else {
            // Depth 2 items should use the same preset
            $link_classes = 'font-nunito text-body-2 text-neutral-text hover:text-primary transition-colors m-0 p-0';
            $item_output = '<a' . $attributes . ' class="' . $link_classes . '">';
            $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= '</a>';
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }
    
    /**
     * End the element output.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
