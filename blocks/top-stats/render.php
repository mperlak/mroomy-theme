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
?>

<div class="mroomy-top-stats py-14 bg-beige-100">
    <div class="container mx-auto px-4">
        <div class="flex justify-center items-start gap-[81px]">
            <?php foreach ($stats as $index => $stat) : ?>
                <div class="stat-item relative flex flex-col items-center text-center w-[340px]">
                    <?php if (!empty($stat['number'])) : ?>
                        <div class="stat-number">
                            <span class="text-[56px] font-extrabold leading-[1.1] text-[#222222] font-nunito">
                                <?php echo wp_kses_post($stat['number']); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($stat['description'])) : ?>
                        <div class="stat-description mt-[13.18px]">
                            <span class="text-[24px] font-extrabold leading-[30px] text-[#3c3c3b] font-nunito">
                                <?php echo wp_kses_post(nl2br($stat['description'])); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($stat['imageUrl'])) : ?>
                        <div class="stat-image mt-[2px]">
                            <img src="<?php echo esc_url($stat['imageUrl']); ?>" alt="" class="h-[29.886px] w-auto mx-auto">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($stat['buttonText']) && !empty($stat['buttonUrl'])) : ?>
                        <div class="stat-button mt-[20px]">
                            <a href="<?php echo esc_url($stat['buttonUrl']); ?>" class="btn-tertiary-md group">
                                <span class="btn-text">
                                    <?php echo esc_html($stat['buttonText']); ?>
                                </span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                                    <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .mroomy-top-stats {
        font-family: 'Nunito', sans-serif;
    }

    .mroomy-top-stats .stat-description br {
        display: block;
        content: "";
        margin: 0;
    }

    @media (max-width: 1280px) {
        .mroomy-top-stats .flex {
            flex-wrap: wrap;
        }

        .mroomy-top-stats .stat-item {
            flex: 0 0 calc(50% - 40px);
        }
    }

    @media (max-width: 768px) {
        .mroomy-top-stats .stat-item {
            flex: 0 0 100%;
            margin-bottom: 40px;
        }
    }
</style>