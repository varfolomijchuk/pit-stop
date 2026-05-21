<?php

use theme\pitstop\RegisterPostTypes;

add_action('init', function () {
    RegisterPostTypes::registerPostType(
        'clients',
        'Client',
        'Clients',
        ['supports' => ['title', 'thumbnail', 'revisions'], 'public' => false, 'publicly_queryable' => true, 'has_archive' => true, 'rewrite' => true, 'hierarchical' => true, 'show_in_nav_menus' => true, 'menu_icon' => 'dashicons-universal-access', 'labels' => ['add_new' => _x('Add Client', 'backend: post type label', 'pitstop')]]
    );
});
