/**
 * Rooms Showcase Block Editor
 *
 * Editor interface for the block
 */

import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    // Full implementation will be added in Issue #6
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Ustawienia sekcji', 'mroomy')}>
                    {/* Controls will be added in Issue #6 */}
                </PanelBody>
            </InspectorControls>
            <div className="mroomy-rooms-showcase-editor">
                <p>Rooms Showcase Block - Implementation in Issue #6</p>
            </div>
        </>
    );
}