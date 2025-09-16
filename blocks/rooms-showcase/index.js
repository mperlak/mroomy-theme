/**
 * Rooms Showcase Block
 *
 * Main entry point for the block
 */

import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
    edit: Edit,
    save: () => null, // Server-side render
} );