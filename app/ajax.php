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
