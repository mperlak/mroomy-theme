<?php
/**
 * Dropdown Select Component
 *
 * Reusable dropdown select component matching Figma design
 *
 * @package Mroomy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render dropdown select
 *
 * @param array $args {
 *     Optional. Arguments for the dropdown select.
 *
 *     @type string $name          Field name attribute. Required.
 *     @type string $id            Field id attribute. Default empty.
 *     @type string $placeholder   Placeholder text. Default 'Select...'.
 *     @type array  $options       Array of options [value => label]. Default empty array.
 *     @type string $selected      Currently selected value. Default empty.
 *     @type string $size          Size: 'medium' or 'small'. Default 'medium'.
 *     @type string $class         Additional CSS classes. Default empty.
 *     @type bool   $required      Is field required. Default false.
 * }
 */
function mroomy_dropdown_select( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'id'          => '',
		'placeholder' => 'Select...',
		'options'     => array(),
		'selected'    => '',
		'size'        => 'medium',
		'class'       => '',
		'required'    => false,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( empty( $args['name'] ) ) {
		return;
	}

	// Generate ID if not provided
	$field_id = ! empty( $args['id'] ) ? $args['id'] : 'dropdown-' . sanitize_title( $args['name'] );

	// Size-specific classes
	$size_classes = array(
		'medium' => 'h-[48px] px-4 py-3 text-[16px] leading-[20px]',
		'small'  => 'h-[40px] px-3 py-2 text-[16px] leading-[20px]',
	);

	$size_class = isset( $size_classes[ $args['size'] ] ) ? $size_classes[ $args['size'] ] : $size_classes['medium'];

	// Build CSS classes
	$css_classes = array(
		'dropdown-select',
		$size_class,
		'bg-white',
		'border',
		'border-[#c4c4c4]',
		'rounded-lg',
		'font-nunito',
		'font-semibold',
		'text-[#222222]',
		'cursor-pointer',
		'appearance-none',
		'focus:outline-none',
		'focus:border-primary',
		'transition-colors',
	);

	// Placeholder style (gray text)
	$has_selection = ! empty( $args['selected'] );
	if ( ! $has_selection ) {
		$css_classes[] = 'text-[#888888]';
	}

	if ( ! empty( $args['class'] ) ) {
		$css_classes[] = $args['class'];
	}

	// Chevron down icon as background
	$chevron_svg = "data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M6 9L12 15L18 9' stroke='%233d3d3d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E";

	?>
	<select
		name="<?php echo esc_attr( $args['name'] ); ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
		class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>"
		style="border-color: #c4c4c4; background-image: url('<?php echo $chevron_svg; ?>'); background-repeat: no-repeat; background-position: right 12px center; padding-right: 44px;"
		<?php echo $args['required'] ? 'required' : ''; ?>
	>
		<?php if ( ! empty( $args['placeholder'] ) ) : ?>
			<option value=""><?php echo esc_html( $args['placeholder'] ); ?></option>
		<?php endif; ?>

		<?php foreach ( $args['options'] as $value => $label ) : ?>
			<option
				value="<?php echo esc_attr( $value ); ?>"
				<?php selected( $args['selected'], $value ); ?>
			>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}