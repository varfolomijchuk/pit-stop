<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

        <?php if (has_post_thumbnail()) : ?>
            <div class="single-post__image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

        <div class="single-post__body">
            <h1 class="single-post__title"><?php the_title(); ?></h1>

            <div class="single-post__meta">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
                <span class="single-post__author">
                    <?php esc_html_e('By', 'pitstop'); ?> <?php the_author(); ?>
                </span>
            </div>

            <div class="single-post__content">
                <?php the_content(); ?>
            </div>
        </div>

    </article>

<?php endwhile; ?>

<?php get_footer(); ?>
