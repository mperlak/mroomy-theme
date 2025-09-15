<?php
/**
 * Server-side render closure for mroomy/top-section
 * This file must return a callable when referenced by block.json "render".
 */

return function( $attributes, $content, $block ) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[mroomy/top-section] render attributes: ' . wp_json_encode($attributes));
    }
    $image_id   = isset($attributes['imageId']) ? intval($attributes['imageId']) : 0;
    $image_url  = isset($attributes['imageUrl']) ? esc_url($attributes['imageUrl']) : '';
    $image_alt  = isset($attributes['imageAlt']) ? esc_attr($attributes['imageAlt']) : '';
    $bg_pos     = isset($attributes['backgroundPosition']) ? esc_attr($attributes['backgroundPosition']) : 'center';
    $bg_size    = isset($attributes['backgroundSize']) ? esc_attr($attributes['backgroundSize']) : 'cover';
    $title_html = isset($attributes['title']) ? $attributes['title'] : '';
    $content_html = isset($attributes['content']) ? $attributes['content'] : '';
    $btn_text   = isset($attributes['buttonText']) ? esc_html($attributes['buttonText']) : '';
    $btn_url    = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';
    $btn_target = isset($attributes['buttonTarget']) ? esc_attr($attributes['buttonTarget']) : '_self';
    $box_opacity = isset($attributes['boxBgOpacity']) ? floatval($attributes['boxBgOpacity']) : 0.9;
    $box_radius = isset($attributes['boxRadius']) ? intval($attributes['boxRadius']) : 32;

    if ($image_id && empty($image_url)) {
        $img = wp_get_attachment_image_src($image_id, 'full');
        if ($img) {
            $image_url = $img[0];
        }
    }

    $bg_style = '';
    // Background now rendered as <img>; keep position/size in case of fallback styles
    if ($image_url) {
        $bg_style = sprintf('background-position:%s;background-size:%s;', $bg_pos, $bg_size);
    }
    $card_style = sprintf('background-color:rgba(255,255,255,%s);border-radius:%dpx;', esc_attr($box_opacity), intval($box_radius));

    $heading_id = function_exists('wp_unique_id') ? wp_unique_id('hero-title-') : 'hero-title';

    ob_start();
    ?>
    <section class="relative w-full" role="region" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
        <div class="relative w-full" style="height:655.835px;<?php echo esc_attr($bg_style); ?>">
            <?php if ($image_url) : ?>
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image(
                        $image_id,
                        'full',
                        false,
                        array(
                            'class' => 'absolute inset-0 w-full h-full object-cover',
                            'loading' => 'eager',
                            'fetchpriority' => 'high',
                            'alt' => $image_alt,
                        )
                    ); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="eager" fetchpriority="high" class="absolute inset-0 w-full h-full object-cover" />
                <?php endif; ?>
            <?php endif; ?>
            <div class="absolute left-[58px] top-[109px] w-[559px] p-[48px] flex flex-col gap-6 items-start justify-center z-10" style="<?php echo esc_attr($card_style); ?>">
                <?php if ($title_html) : ?>
                    <h2 id="<?php echo esc_attr($heading_id); ?>" class="headline-2 text-neutral-text"><?php echo wp_kses_post($title_html); ?></h2>
                <?php endif; ?>
                <?php if ($content_html) : ?>
                    <div class="title-small-1 text-[#3c3c3b] hero-content"><?php echo wp_kses_post($content_html); ?></div>
                <?php endif; ?>
                <?php
                    $cta_text = $btn_text ?: __('Zobacz nasze projekty', 'mroomy_s');
                    $cta_url  = $btn_url ?: '#';
                    echo '<div>' . mroomy_button([
                        'text' => $cta_text,
                        'url' => $cta_url,
                        'target' => $btn_target,
                        'variant' => 'primary',
                        'size' => 'lg',
                        'chevron' => false,
                        'class' => 'btn-cta w-[240px] justify-center'
                    ]) . '</div>';
                ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
};


