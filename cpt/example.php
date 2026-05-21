<?php

use theme\pitstop\RegisterPostTypes;

add_action('init', function () {
    RegisterPostTypes::registerPostType(
        'example',
        'Example',
        'Examples',
        ['supports' => ['title', 'thumbnail', 'revisions'], 'public' => false, 'publicly_queryable' => false, 'has_archive' => false, 'rewrite' => true, 'hierarchical' => true, 'menu_icon' => 'dashicons-database', 'labels' => ['add_new' => _x('Add Example', 'backend: post type label', 'Ai')]]
    );
});
