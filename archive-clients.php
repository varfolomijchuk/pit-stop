<?php get_header(); ?>

<style>
    .clients-archive { padding: 40px 20px; }
    .clients-search { margin-bottom: 24px; }
    .clients-search input {
        width: 100%;
        max-width: 400px;
        padding: 8px 12px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .clients-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 24px;
        transition: opacity 0.15s;
    }
    .client-card__inner {
        display: block;
        text-decoration: none;
        color: inherit;
        border: 1px solid #eee;
        border-radius: 6px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .client-card__inner:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .client-card__image img { width: 100%; height: 180px; object-fit: cover; display: block; }
    .client-card__placeholder { width: 100%; height: 180px; background: #d0d0d0; }
    .client-card__info { padding: 12px; }
    .client-card__title { font-size: 16px; margin: 0 0 6px; }
    .client-card__phone { font-size: 14px; color: #555; margin: 0; }
</style>

<div class="clients-archive">
    <h1><?php post_type_archive_title(); ?></h1>

    <div class="clients-search">
        <input type="text" id="client-search" placeholder="<?php esc_attr_e('Search by name…', 'pitstop'); ?>" />
    </div>

    <div id="clients-list" class="clients-grid">
        <?php
        $query = new WP_Query([
            'post_type'      => 'clients',
            'posts_per_page' => 10,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();
                get_template_part('template-part/client-card');
            endwhile;
            wp_reset_postdata();
        else : ?>
            <p><?php esc_html_e('No clients found.', 'pitstop'); ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    var input = document.getElementById('client-search');
    var list  = document.getElementById('clients-list');
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
                'action=filter_clients' +
                '&search=' + encodeURIComponent(input.value) +
                '&nonce=<?= esc_js(wp_create_nonce('filter_clients_nonce')) ?>'
            );
        }, 300);
    });
}());
</script>

<?php get_footer(); ?>
