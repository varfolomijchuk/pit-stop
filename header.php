<!DOCTYPE html>
<html lang="<?php language_attributes(); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
    <div class="app">

    <header id="site-header" class="site-header">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <span class="site-title"><?php bloginfo( 'name' ); ?></span>
                    <?php if ( get_bloginfo( 'description' ) ) : ?>
                        <span class="site-description"><?php bloginfo( 'description' ); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>

        <nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'spacehills' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>
    </header>

    <main id="main-content" class="site-main">
