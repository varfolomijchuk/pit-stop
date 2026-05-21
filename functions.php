<?php

/**
 * Load ./app files
 */
$dir = get_template_directory() . '/app';

foreach (glob($dir . '/*.php') as $file) {
    require_once $file;
}

/**
 * Load ./cpt files
 */
foreach (glob(get_template_directory() . '/cpt/*.php') as $file) {
    require_once $file;
}


function ascent_asset( $asset ) {
    static $manifest = null;
    if ( $manifest === null ) {
        $manifest_path = get_template_directory() . '/dist/manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];
    }
    $path = isset( $manifest[ $asset ] ) ? $manifest[ $asset ] : $asset;
    return get_template_directory_uri() . '/dist/' . $path;
}

// Enqueue theme scripts and styles
function theme_scripts() {
    wp_enqueue_style( 'theme-styles', ascent_asset( 'app.css' ), array(), false );
    wp_enqueue_script( 'theme-scripts', ascent_asset( 'app.js' ), array(), false, true );

    // Enqueue editor styles for Gutenberg
    add_editor_style( ascent_asset( 'editor.css' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// Basic theme support
function theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'spacehills' ),
    ) );
}
add_action( 'after_setup_theme', 'theme_setup' );

// Include Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
