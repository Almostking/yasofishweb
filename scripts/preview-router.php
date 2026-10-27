<?php
// Temporary loopback-only preview: does not change WooCommerce coming-soon settings.
if ( 'cli-server' !== PHP_SAPI || ! in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ), true ) ) { exit; }
define( 'DISABLE_WP_CRON', true );
define( 'WP_USE_THEMES', true );
if ( function_exists( 'opcache_invalidate' ) ) {
    foreach ( array( 'functions.php', 'front-page.php' ) as $file ) {
        opcache_invalidate( getenv( 'YASOFISH_WP_ROOT' ) . '/wp-content/themes/yasofish-storefront-child/' . $file, true );
    }
}
require getenv( 'YASOFISH_WP_ROOT' ) . '/wp-load.php';
add_filter( 'option_woocommerce_coming_soon', static function () { return 'no'; } );
add_filter( 'redirect_canonical', '__return_false' );
require ABSPATH . 'wp-blog-header.php';
