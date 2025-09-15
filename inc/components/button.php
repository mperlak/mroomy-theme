<?php
/**
 * Reusable button component for theme
 * Variants map to Figma tokens; Chevron icons optional.
 */

if (!function_exists('mroomy_button')) {
    /**
     * Render a button anchor element.
     *
     * @param array $args {
     *   @type string $text       Button label (required)
     *   @type string $url        Href (default '#')
     *   @type string $target     Link target (default '_self')
     *   @type string $variant    Visual variant: 'primary'|'secondary'|'info'|'success'|'warning'|'danger' (default 'primary')
     *   @type string $size       'md'|'lg' (default 'md')
     *   @type bool   $fullWidth  Force width (default false)
     *   @type string|bool $chevron Chevron direction: 'right'|'left'|false (default false)
     *   @type string $class      Extra classes
     * }
     */
    function mroomy_button(array $args = []) {
        $text      = isset($args['text']) ? $args['text'] : '';
        if ($text === '') return '';
        $url       = isset($args['url']) ? $args['url'] : '#';
        $target    = isset($args['target']) ? $args['target'] : '_self';
        $variant   = isset($args['variant']) ? $args['variant'] : 'primary';
        $size      = isset($args['size']) ? $args['size'] : 'md';
        $fullWidth = !empty($args['fullWidth']);
        $chevron   = isset($args['chevron']) ? $args['chevron'] : false; // true|'right'|'left'|false
        if ($chevron === true) { $chevron = 'right'; }
        $extra     = isset($args['class']) ? $args['class'] : '';
        $disabled  = !empty($args['disabled']);

        $base = 'btn font-extrabold text-body-2 rounded-[8px]';
        $variantClass = 'btn-' . $variant;
        if ($variant === 'primary') {
            $variantClass .= ' text-white';
        }
        // Size utility mapping
        switch ($size) {
            case 'sm':
                $sizeClass = 'btn-size-sm';
                break;
            case 'md':
                $sizeClass = 'btn-size-md';
                break;
            case 'xl':
                $sizeClass = 'btn-size-xl';
                break;
            case 'lg':
            default:
                $sizeClass = 'btn-size-lg';
        }
        $widthClass = $fullWidth ? 'w-full' : '';

        $stateClass = $disabled ? ' is-disabled' : '';
        $classes = trim($base . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $widthClass . ' ' . $extra . $stateClass);

        ob_start();
        ?>
        <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>" <?php echo $target === '_blank' ? 'rel=\"noopener noreferrer\"' : ''; ?>>
            <?php if ($chevron === 'left') : ?>
                <svg class="mr-2 inline-block w-4 h-4" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7071 5.29289C16.0976 5.68342 16.0976 6.31658 15.7071 6.70711L10.4142 12L15.7071 17.2929C16.0976 17.6834 16.0976 18.3166 15.7071 18.7071C15.3166 19.0976 14.6834 19.0976 14.2929 18.7071L8.29289 12.7071C7.90237 12.3166 7.90237 11.6834 8.29289 11.2929L14.2929 5.29289C14.6834 4.90237 15.3166 4.90237 15.7071 5.29289Z" />
                </svg>
            <?php endif; ?>
            <span><?php echo esc_html($text); ?></span>
            <?php if ($chevron === 'right') : ?>
                <svg class="ml-2 inline-block w-4 h-4" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 5.29289C8.68342 4.90237 9.31658 4.90237 9.70711 5.29289L15.7071 11.2929C16.0976 11.6834 16.0976 12.3166 15.7071 12.7071L9.70711 18.7071C9.31658 19.0976 8.68342 19.0976 8.29289 18.7071C7.90237 18.3166 7.90237 17.6834 8.29289 17.2929L13.5858 12L8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289Z" />
                </svg>
            <?php endif; ?>
        </a>
        <?php
        return ob_get_clean();
    }
}


