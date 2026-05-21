<?php
$phone  = get_post_meta(get_the_ID(), '_phone_number', true);
$car_id = get_post_meta(get_the_ID(), '_related_car_id', true);
$car    = $car_id ? get_post($car_id) : null;
?>
<div class="client-card">
    <a href="<?php the_permalink(); ?>" class="client-card__inner">
        <div class="client-card__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
                <div class="client-card__placeholder"></div>
            <?php endif; ?>
        </div>
        <div class="client-card__info">
            <h2 class="client-card__title"><?php the_title(); ?></h2>
            <?php if ($phone) : ?>
                <p class="client-card__phone"><?php echo esc_html($phone); ?></p>
            <?php endif; ?>
        </div>
    </a>
    <?php if ($car) : ?>
        <div class="client-card__car">
            <a href="<?php echo esc_url(get_permalink($car->ID)); ?>">
                <?php echo esc_html($car->post_title); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
