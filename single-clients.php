<?php get_header(); ?>

<style>
    .single-client { display: flex; gap: 40px; align-items: flex-start; padding: 40px 20px; }
    .single-client__body { flex: 1; }
    .single-client__title { margin-top: 0; }
    .single-client__phone { margin: 8px 0; }
    .single-client__car { margin-top: 16px; }
    .single-client__image { flex-shrink: 0; width: 280px; }
    .single-client__image img { width: 100%; height: 280px; object-fit: cover; border-radius: 6px; display: block; }
    .single-client__placeholder { width: 280px; height: 280px; background: #d0d0d0; border-radius: 6px; }
</style>

<?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-client'); ?>>

        <div class="single-client__body">
            <h1 class="single-client__title"><?php the_title(); ?></h1>

            <?php
            $phone  = get_post_meta(get_the_ID(), '_phone_number', true);
            $car_id = get_post_meta(get_the_ID(), '_related_car_id', true);
            $car    = $car_id ? get_post($car_id) : null;
            ?>

            <?php if ($phone) : ?>
                <p class="single-client__phone"><?php echo esc_html($phone); ?></p>
            <?php endif; ?>

            <?php if ($car) : ?>
                <div class="single-client__car">
                    <strong><?php esc_html_e('Attached car:', 'pitstop'); ?></strong>
                    <a href="<?php echo esc_url(get_permalink($car->ID)); ?>">
                        <?php echo esc_html($car->post_title); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="single-client__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large'); ?>
            <?php else : ?>
                <div class="single-client__placeholder"></div>
            <?php endif; ?>
        </div>

    </article>

<?php endwhile; ?>

<?php get_footer(); ?>
