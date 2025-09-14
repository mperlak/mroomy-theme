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
     * Start the list before the elements are added.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        // For now, we only handle first level menu items
        // Submenus will be handled in future iterations
    }
    
    /**
     * End the list after the elements are added.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        // For now, we only handle first level menu items
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
        
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        // Figma: Subtitle-2 (20/26) ExtraBold dla głównych pozycji
        $extra_classes = 'font-nunito font-extrabold text-[20px] leading-[26px] text-neutral-text';

        // Anchor jako inline-flex z chevronem tuż po tekście, jeśli są dzieci
        $item_output .= '<a' . $attributes . ' class="inline-flex items-center gap-2 py-0 px-0 ' . $extra_classes . ' hover:text-primary transition-colors">';
        $item_output .= ( isset($args->link_before) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset($args->link_after) ? $args->link_after : '' );

        if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
            $item_output .= '<span class="inline-flex w-4 h-4 items-center justify-center text-primary" aria-hidden="true">';
            $item_output .= file_get_contents( get_template_directory() . '/assets/icons/chevron-right.svg' );
            $item_output .= '</span>';
        }

        $item_output .= '</a>';

        // chevron już dodany wewnątrz <a>
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    
    /**
     * End the element output.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
