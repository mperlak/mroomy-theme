/**
 * ACF Rooms Showcase Block - Editor JavaScript
 * Forces preview refresh when fields change
 */

(function($) {
    // Wait for ACF to be ready
    if (typeof acf !== 'undefined') {

        // Force refresh preview when any field changes in our block
        acf.addAction('change', function($el) {
            // Check if the changed element is within our block
            var $block = $el.closest('.acf-block-fields');

            if ($block.length) {
                var blockData = $block.data('block');

                // Check if it's our rooms showcase block
                if (blockData && blockData.name === 'acf/acf-rooms-showcase') {
                    // Get the block editor instance
                    var editor = wp.data.select('core/block-editor');
                    var blocks = editor.getBlocks();

                    // Find our block and trigger update
                    blocks.forEach(function(block) {
                        if (block.name === 'acf/acf-rooms-showcase') {
                            // Dispatch a block update to force re-render
                            wp.data.dispatch('core/block-editor').updateBlock(
                                block.clientId,
                                {
                                    attributes: {
                                        ...block.attributes,
                                        _timestamp: Date.now() // Force update
                                    }
                                }
                            );
                        }
                    });
                }
            }
        });

        // Alternative method: refresh on specific field changes
        acf.addAction('change_field/name=show_tile_buttons', function(field) {
            // Force block preview refresh
            if (window.acf && window.acf.doAction) {
                acf.doAction('refresh');
            }
        });

        acf.addAction('change_field/name=enable_carousel', function(field) {
            // Force block preview refresh
            if (window.acf && window.acf.doAction) {
                acf.doAction('refresh');
            }
        });

        acf.addAction('change_field/name=show_header', function(field) {
            // Force block preview refresh
            if (window.acf && window.acf.doAction) {
                acf.doAction('refresh');
            }
        });
    }

})(jQuery);