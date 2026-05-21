<?php

function pitstop_filter_clients_handler(): void
{
    check_ajax_referer('filter_clients_nonce', 'nonce');

    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    $args = [
        'post_type'      => 'clients',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($search !== '') {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-part/client-card');
        }
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No clients found.', 'pitstop') . '</p>';
    }

    wp_die();
}

add_action('wp_ajax_filter_clients', 'pitstop_filter_clients_handler');
add_action('wp_ajax_nopriv_filter_clients', 'pitstop_filter_clients_handler');

function pitstop_filter_service_records_handler(): void
{
    check_ajax_referer('filter_service_records_nonce', 'nonce');

    $post_id   = absint($_POST['post_id'] ?? 0);
    $date_from = sanitize_text_field($_POST['date_from'] ?? '');
    $date_to   = sanitize_text_field($_POST['date_to'] ?? '');

    $records = get_post_meta($post_id, '_service_records', true);
    $records = is_array($records) ? $records : [];

    if ($date_from !== '') {
        $records = array_filter($records, fn($r) => !empty($r['date']) && $r['date'] >= $date_from);
    }

    if ($date_to !== '') {
        $records = array_filter($records, fn($r) => !empty($r['date']) && $r['date'] <= $date_to);
    }

    if (empty($records)) {
        echo '<p>' . esc_html__('No service records found.', 'pitstop') . '</p>';
        wp_die();
    }

    foreach ($records as $record) {
        ?>
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
        <?php
    }

    wp_die();
}

add_action('wp_ajax_filter_service_records', 'pitstop_filter_service_records_handler');
add_action('wp_ajax_nopriv_filter_service_records', 'pitstop_filter_service_records_handler');

function pitstop_filter_cars_handler(): void
{
    check_ajax_referer('filter_cars_nonce', 'nonce');

    $search = sanitize_text_field($_POST['search'] ?? '');

    $args = [
        'post_type'      => 'cars',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($search !== '') {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-part/car-card');
        }
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No cars found.', 'pitstop') . '</p>';
    }

    wp_die();
}

add_action('wp_ajax_filter_cars', 'pitstop_filter_cars_handler');
add_action('wp_ajax_nopriv_filter_cars', 'pitstop_filter_cars_handler');
