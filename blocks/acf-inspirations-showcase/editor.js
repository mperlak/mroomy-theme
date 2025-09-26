/**
 * ACF Block: Inspirations Showcase
 * Editor JavaScript for preview functionality
 *
 * @package Mroomy
 */

(function($) {
    'use strict';

    /**
     * Initialize block preview in editor
     */
    var initializeBlock = function($block) {
        // Block is already rendered by PHP
        // This file is for any additional editor-specific JS if needed

        // Add editor-specific class
        $block.addClass('acf-inspirations-showcase-editor');

        // Disable carousel in editor preview
        $block.find('.swiper').removeClass('swiper').addClass('editor-carousel');
        $block.find('.swiper-wrapper').removeClass('swiper-wrapper').addClass('editor-carousel-wrapper');
        $block.find('.swiper-slide').removeClass('swiper-slide').addClass('editor-slide');

        // Make tiles display in a grid in editor
        $block.find('.editor-carousel-wrapper').css({
            'display': 'flex',
            'flex-wrap': 'wrap',
            'gap': '1rem'
        });

        $block.find('.editor-slide').css({
            'width': 'auto',
            'flex': '0 0 auto'
        });
    };

    // Initialize on ready
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=acf-inspirations-showcase', initializeBlock);
    }

})(jQuery);