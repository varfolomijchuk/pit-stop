<?php

use theme\pitstop\RegisterPostTypes;

add_action('init', function () {
    RegisterPostTypes::registerPostType(
        'cars',
        'Car',
        'Cars',
        ['supports' => ['title', 'thumbnail', 'revisions'], 'public' => false, 'publicly_queryable' => true, 'has_archive' => true, 'rewrite' => true, 'hierarchical' => true, 'show_in_nav_menus' => true, 'menu_icon' => 'dashicons-car', 'labels' => ['add_new' => _x('Add Car', 'backend: post type label', 'pitstop')]]
    );
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'car_service_records',
        __('Service Records', 'pitstop'),
        function (WP_Post $post) {
            $records = get_post_meta($post->ID, '_service_records', true);
            $records = is_array($records) ? $records : [];

            wp_nonce_field('car_service_records_save', 'car_service_records_nonce');
            ?>
            <style>
                .service-record-row { border: 4px solid #ff0000; padding: 12px; margin-bottom: 12px; background: #5a8a67; border-radius: 3px; }
                .service-record-row label { display: block; font-weight: 600; margin: 10px 0 4px; }
                .service-record-row label:first-child { margin-top: 0; }
                .service-record-row input[type="date"],
                .service-record-row input[type="number"] { width: 100%; }
                .remove-service-record { margin-top: 10px !important; color: #b32d2e !important; border-color: #b32d2e !important; }
            </style>

            <div id="car-service-records">
                <?php foreach ($records as $index => $record) : ?>
                    <div class="service-record-row">
                        <label><?= esc_html__('Date', 'pitstop') ?></label>
                        <input type="date" name="service_records[<?= $index ?>][date]" value="<?= esc_attr($record['date'] ?? '') ?>">

                        <label><?= esc_html__('Notes', 'pitstop') ?></label>
                        <?php wp_editor(
                            wp_kses_post($record['wysiwyg'] ?? ''),
                            'car_wysiwyg_' . $index,
                            [
                                'textarea_name' => "service_records[{$index}][wysiwyg]",
                                'media_buttons' => false,
                                'teeny'         => true,
                                'textarea_rows' => 5,
                            ]
                        ); ?>

                        <label><?= esc_html__('Number', 'pitstop') ?></label>
                        <input type="number" name="service_records[<?= $index ?>][number]" value="<?= esc_attr($record['number'] ?? '') ?>">

                        <button type="button" class="button remove-service-record"><?= esc_html__('Remove Row', 'pitstop') ?></button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" id="add-service-record" class="button button-primary" style="margin-top:8px">
                <?= esc_html__('+ Add Row', 'pitstop') ?>
            </button>

            <script>
            (function () {
                let wrap     = document.getElementById('car-service-records');
                let rowCount = <?= count($records) ?>;

                function bindRemove(row, editorId) {
                    row.querySelector('.remove-service-record').addEventListener('click', function () {
                        if (editorId && typeof wp !== 'undefined' && wp.editor) {
                            wp.editor.remove(editorId);
                        }
                        row.remove();
                    });
                }

                wrap.querySelectorAll('.service-record-row').forEach(function (row, i) {
                    bindRemove(row, 'car_wysiwyg_' + i);
                });

                document.getElementById('add-service-record').addEventListener('click', function () {
                    let index    = rowCount++;
                    let editorId = 'car_wysiwyg_' + index;

                    let row = document.createElement('div');
                    row.className = 'service-record-row';
                    row.innerHTML =
                        '<label><?= esc_js(__('Date', 'pitstop')) ?></label>' +
                        '<input type="date" name="service_records[' + index + '][date]" value="">' +
                        '<label><?= esc_js(__('Notes', 'pitstop')) ?></label>' +
                        '<textarea id="' + editorId + '" name="service_records[' + index + '][wysiwyg]" rows="5" style="width:100%"></textarea>' +
                        '<label><?= esc_js(__('Number', 'pitstop')) ?></label>' +
                        '<input type="number" name="service_records[' + index + '][number]" value="">' +
                        '<button type="button" class="button remove-service-record"><?= esc_js(__('Remove Row', 'pitstop')) ?></button>';

                    wrap.appendChild(row);

                    if (typeof wp !== 'undefined' && wp.editor) {
                        wp.editor.initialize(editorId, {
                            tinymce:      { wpautop: true, toolbar1: 'bold italic | bullist numlist | link unlink' },
                            quicktags:    true,
                            mediaButtons: false,
                        });
                    }

                    bindRemove(row, editorId);
                });

                // Sync all TinyMCE instances to their textareas before the form submits
                document.getElementById('post').addEventListener('submit', function () {
                    if (typeof tinyMCE !== 'undefined') {
                        tinyMCE.triggerSave();
                    }
                });
            }());
            </script>
            <?php
        },
        'cars',
        'normal',
        'high'
    );
});

add_action('save_post_cars', function (int $post_id) {
    if (
        !isset($_POST['car_service_records_nonce']) ||
        !wp_verify_nonce($_POST['car_service_records_nonce'], 'car_service_records_save') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    ) {
        return;
    }

    $records = [];

    if (!empty($_POST['service_records']) && is_array($_POST['service_records'])) {
        foreach ($_POST['service_records'] as $record) {
            $records[] = [
                'date'    => sanitize_text_field($record['date'] ?? ''),
                'wysiwyg' => wp_kses_post($record['wysiwyg'] ?? ''),
                'number'  => isset($record['number']) && $record['number'] !== '' ? intval($record['number']) : '',
            ];
        }
    }

    update_post_meta($post_id, '_service_records', $records);
});
