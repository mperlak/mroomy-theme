<?php
/**
 * Top Stats Block Template.
 *
 * @param   array $attributes - Block attributes.
 * @param   string $content - Block default content.
 * @param   WP_Block $block - Block instance.
 */

$stats = $attributes['stats'] ?? [];

if (empty($stats)) {
    return;
}

$total_stats = count($stats);
$carousel_id = 'top-stats-carousel-' . wp_unique_id();
?>

<div class="mroomy-top-stats py-14 bg-beige-100">
    <div class="container mx-auto px-4">
        <!-- Single HTML source for both mobile carousel and desktop grid -->
        <div id="<?php echo esc_attr($carousel_id); ?>"
             class="mroomy-top-stats-container"
             data-carousel="mobile-only"
             data-total="<?php echo esc_attr($total_stats); ?>">

            <!-- Stats container - becomes carousel track on mobile, grid on desktop -->
            <ul role="list" class="stats-wrapper sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:gap-10 list-none p-0 m-0">
                <?php foreach ($stats as $index => $stat) : ?>
                    <li class="stat-item w-full flex flex-col items-center text-center" data-stat-index="<?php echo $index; ?>">
                        <?php if (!empty($stat['number'])) : ?>
                            <div class="stat-number">
                                <span class="text-[40px] sm:text-[56px] font-extrabold leading-[1.25] sm:leading-[1.1] text-[#222222] font-nunito">
                                    <?php echo wp_kses_post($stat['number']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($stat['description'])) : ?>
                            <div class="stat-description mt-[10px] sm:mt-[13px]">
                                <span class="text-[20px] sm:text-[24px] font-extrabold leading-[26px] sm:leading-[30px] text-[#3c3c3b] font-nunito">
                                    <?php echo wp_kses_post(nl2br($stat['description'])); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($stat['imageId']) && $stat['imageId'] > 0) : ?>
                            <div class="stat-image mt-[4px] sm:mt-[2px]">
                                <?php echo wp_get_attachment_image($stat['imageId'], 'full', false, array('class' => 'h-[30px] w-auto mx-auto')); ?>
                            </div>
                        <?php elseif (!empty($stat['imageUrl'])) : ?>
                            <div class="stat-image mt-[4px] sm:mt-[2px]">
                                <img src="<?php echo esc_url($stat['imageUrl']); ?>" alt="" class="h-[30px] w-auto mx-auto">
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($stat['buttonText']) && !empty($stat['buttonUrl'])) : ?>
                            <div class="stat-button mt-5 sm:mt-[20px]">
                                <a href="<?php echo esc_url($stat['buttonUrl']); ?>" class="btn-tertiary-md group">
                                    <span class="btn-text">
                                        <?php echo esc_html($stat['buttonText']); ?>
                                    </span>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" aria-hidden="true" focusable="false">
                                        <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Carousel dots for mobile only -->
            <?php if ($total_stats > 1) : ?>
                <div class="carousel-dots flex sm:hidden items-center justify-center gap-4 mt-6" role="tablist">
                    <?php for ($i = 0; $i < $total_stats; $i++) : ?>
                        <button
                            class="carousel-dot <?php echo $i === 0 ? 'w-8 h-3 rounded-[16px] bg-primary' : 'w-3 h-3 rounded-full bg-neutral-field-border'; ?> transition-all duration-300"
                            data-slide="<?php echo $i; ?>"
                            role="tab"
                            aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('Go to slide %d', 'mroomy_s'), $i + 1)); ?>">
                            <span class="sr-only"><?php echo esc_html(sprintf(__('Slide %d', 'mroomy_s'), $i + 1)); ?></span>
                        </button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>