<?php

/**
 * Meta box for the client where we could attach the related car
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'client_related_car',
        __('Related Car', 'pitstop'),
        function (WP_Post $post) {
            $selected = get_post_meta($post->ID, '_related_car_id', true);
            $cars = get_posts(['post_type' => 'cars', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC']);

            wp_nonce_field('client_meta_save', 'client_meta_nonce');
            ?>
            <select name="related_car_id" style="width:100%">
                <option value=""><?= esc_html__('— Select a car —', 'pitstop') ?></option>
                <?php foreach ($cars as $car) : ?>
                    <option value="<?= esc_attr($car->ID) ?>" <?= selected($selected, $car->ID, false) ?>>
                        <?= esc_html($car->post_title) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
        },
        'clients',
        'side'
    );

    add_meta_box(
        'client_phone_number',
        __('Phone Number', 'pitstop'),
        function (WP_Post $post) {
            $phone = get_post_meta($post->ID, '_phone_number', true);
            ?>
            <input
                type="tel"
                name="phone_number"
                value="<?= esc_attr($phone) ?>"
                style="width:100%"
                placeholder="+1 234 567 8900"
            />
            <?php
        },
        'clients',
        'side'
    );
});

add_action('save_post_clients', function (int $post_id) {
    if (
        !isset($_POST['client_meta_nonce']) ||
        !wp_verify_nonce($_POST['client_meta_nonce'], 'client_meta_save') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    ) {
        return;
    }

    if (isset($_POST['related_car_id']) && $_POST['related_car_id'] !== '') {
        update_post_meta($post_id, '_related_car_id', absint($_POST['related_car_id']));
    } else {
        delete_post_meta($post_id, '_related_car_id');
    }

    $phone = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';
    if ($phone !== '') {
        update_post_meta($post_id, '_phone_number', $phone);
    } else {
        delete_post_meta($post_id, '_phone_number');
    }
});