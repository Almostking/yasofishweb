<?php
/** Read-only business configuration audit. Never emits account details or secrets. */
if ( 'cli' !== PHP_SAPI ) { exit( 1 ); }
$root = getenv( 'YASOFISH_WP_ROOT' );
if ( ! $root || ! preg_match( '#Local Sites[/\\\\]yasofish[/\\\\]app[/\\\\]public$#', $root ) ) { exit( 2 ); }
define( 'DISABLE_WP_CRON', true );
require $root . '/wp-load.php';
$keys = array( 'stylesheet', 'template', 'woocommerce_currency', 'woocommerce_default_country', 'woocommerce_manage_stock', 'woocommerce_calc_taxes', 'woocommerce_enable_guest_checkout', 'woocommerce_enable_myaccount_registration', 'woocommerce_shop_page_id', 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id', 'woocommerce_myaccount_page_id', 'blog_public' );
$result = array( 'wordpress' => get_bloginfo( 'version' ), 'php' => PHP_VERSION, 'woocommerce' => defined( 'WC_VERSION' ) ? WC_VERSION : null, 'theme_version' => wp_get_theme()->get( 'Version' ) );
foreach ( $keys as $key ) { $result[$key] = get_option( $key ); }
$result['bacs_enabled'] = get_option( 'woocommerce_bacs_settings', array() )['enabled'] ?? 'no';
$result['published_products'] = wp_count_posts( 'product' )->publish ?? 0;
$result['pages'] = array();
foreach ( array( 'shop', 'cart', 'checkout', 'myaccount' ) as $page ) { $result['pages'][$page] = wc_get_page_permalink( $page ); }
echo wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
