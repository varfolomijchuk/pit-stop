<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-car'); ?>>

        <h1 class="single-car__title"><?php the_title(); ?></h1>

        <?php if (has_post_thumbnail()) : ?>
            <div class="single-car__image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

        <?php
        $records = get_post_meta(get_the_ID(), '_service_records', true);
        $records = is_array($records) ? $records : [];
        ?>

        <?php if ($records) : ?>
            <div class="single-car__records">
                <h2><?php esc_html_e('Service Records', 'pitstop'); ?></h2>

                <?php foreach ($records as $record) : ?>
                    <div class="service-record">
                        <?php if (!empty($record['date'])) : ?>
                            <p class="service-record__date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($record['date']))); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($record['wysiwyg'])) : ?>
                            <div class="service-record__notes">
                                <?php echo wp_kses_post($record['wysiwyg']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($record['number'] !== '') : ?>
                            <p class="service-record__number">
                                <?php echo esc_html($record['number']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </article>

<?php endwhile; ?>

<?php get_footer(); ?>
