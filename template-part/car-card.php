<div class="car-card">
    <div class="car-card__info">
        <h2 class="car-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
    </div>
    <div class="car-card__image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium'); ?>
        <?php else : ?>
            <div class="car-card__placeholder"></div>
        <?php endif; ?>
    </div>
</div>
