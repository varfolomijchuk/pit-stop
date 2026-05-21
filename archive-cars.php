<?php get_header(); ?>

<style>
    .cars-archive { padding: 40px 20px; }
    .cars-search { margin-bottom: 24px; }
    .cars-search input {
        width: 100%;
        max-width: 400px;
        padding: 8px 12px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .cars-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        transition: opacity 0.15s;
    }
    .car-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #eee;
        border-radius: 6px;
        overflow: hidden;
        padding: 16px;
        gap: 16px;
    }
    .car-card__info { flex: 1; }
    .car-card__title { margin: 0; font-size: 20px; }
    .car-card__title a { text-decoration: none; color: inherit; }
    .car-card__title a:hover { text-decoration: underline; }
    .car-card__image { flex-shrink: 0; }
    .car-card__image img { width: 160px; height: 120px; object-fit: cover; display: block; border-radius: 4px; }
    .car-card__placeholder { width: 160px; height: 120px; background: #d0d0d0; border-radius: 4px; }
</style>

<div class="cars-archive">
    <h1><?php post_type_archive_title(); ?></h1>

    <div class="cars-search">
        <input type="text" id="car-search" placeholder="<?php esc_attr_e('Search by title…', 'pitstop'); ?>" />
    </div>

    <div id="cars-list" class="cars-list">
        <?php
        $query = new WP_Query([
            'post_type'      => 'cars',
            'posts_per_page' => 10,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();
                get_template_part('template-part/car-card');
            endwhile;
            wp_reset_postdata();
        else : ?>
            <p><?php esc_html_e('No cars found.', 'pitstop'); ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    var input = document.getElementById('car-search');
    var list  = document.getElementById('cars-list');
    var timer;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            list.style.opacity = '0.5';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= esc_js(admin_url('admin-ajax.php')) ?>');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    list.innerHTML     = xhr.responseText;
                    list.style.opacity = '1';
                }
            };
            xhr.send(
                'action=filter_cars' +
                '&search=' + encodeURIComponent(input.value) +
                '&nonce=<?= esc_js(wp_create_nonce('filter_cars_nonce')) ?>'
            );
        }, 300);
    });
}());
</script>

<?php get_footer(); ?>
