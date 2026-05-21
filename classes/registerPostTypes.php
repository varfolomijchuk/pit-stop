<?php

namespace theme\pitstop;

class RegisterPostTypes
{
    public static function registerPostType(string $slug, string $singular, string $plural, array $args = [])
    {
        $labels = array_merge([
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => _x('Add New', 'backend: post type label', 'test'),
            'add_new_item' => sprintf(_x('Add New %s', 'backend: post type label', 'test'), $singular),
            'edit_item' => sprintf(_x('Edit %s', 'backend: post type label', 'test'), $singular),
            'new_item' => sprintf(_x('New %s', 'backend: post type label', 'test'), $singular),
            'view_item' => sprintf(_x('View %s', 'backend: post type label', 'test'), $singular),
            'search_items' => sprintf(_x('Search %s', 'backend: post type label', 'test'), $plural),
            'not_found' => sprintf(_x('No %s found', 'backend: post type label', 'test'), $singular),
            'not_found_in_trash' => sprintf(
                _x('No %s  in Trash', 'backend: post type label', 'test'),
                $plural
            ),
            'parent_item_colon' => sprintf(_x('Parent %s:', 'backend: post type label', 'test'), $singular),
            'menu_name' => $plural,
        ], isset($args['labels']) ? $args['labels'] : []);

        register_post_type($slug, array_merge([
            'public' => true,
            'has_archive' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'revisions', 'excerpt'],
            'rewrite' => ['with_front' => false]
        ], $args, ['labels' => $labels]));
    }

    public static function registerTaxonomy($slug, $posttype, $singular, $plural, array $args = [])
    {
        register_taxonomy($slug, $posttype, array_merge([
            'labels' => [
                'name' => $plural,
                'singular_name' => $singular,
                'search_items' => sprintf(_x('Search %s', 'post type label', 'test'), $plural),
                'all_items' => sprintf(_x('All %s', 'post type label', 'test'), $plural),
                'parent_item' => sprintf(_x('Parent %s', 'post type label', 'test'), $singular),
                'parent_item_colon' => sprintf(_x('Parent %s:', 'post type label', 'test'), $singular),
                'edit_item' => sprintf(_x('Edit %s', 'post type label', 'test'), $singular),
                'update_item' => sprintf(_x('Update %s', 'post type label', 'test'), $singular),
                'add_new_item' => sprintf(_x('Add New %s', 'post type label', 'test'), $singular),
                'new_item_name' => sprintf(_x('New %sname', 'post type label', 'test'), $singular),
                'menu_name' => $plural,
            ],
            'show_ui' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
        ], $args));
    }
}
