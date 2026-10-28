<?php
/** Read-only checks: local visual data never becomes WooCommerce inventory. */
ob_start();
require __DIR__ . '/audit-local.php';
ob_end_clean();
$tests = array();
$tests['local_environment'] = 'local' === wp_get_environment_type();
$tests['local_hostname'] = 'yasofish.local' === wp_parse_url( home_url(), PHP_URL_HOST );
$tests['reference_enabled'] = yasofish_reference_mode();
$before = wp_count_posts( 'product' );
$data = yasofish_reference_data();
$tests['nine_original_visual_products'] = 9 === count( $data['products'] );
$tests['visual_products_are_arrays'] = is_array( $data['products'][0] );
$tests['no_inventory_writes'] = $before == wp_count_posts( 'product' );
$_GET['yf_live'] = '1';
$tests['native_mode_switch'] = ! yasofish_reference_mode();
unset( $_GET['yf_live'] );
$tests['classic_cart'] = '[woocommerce_cart]' === trim( get_post_field( 'post_content', wc_get_page_id( 'cart' ) ) );
$tests['bacs_disabled'] = 'no' === get_option( 'woocommerce_bacs_settings' )['enabled'];
$tests['coming_soon_stored'] = 'yes' === get_option( 'woocommerce_coming_soon' );
echo wp_json_encode( $tests, JSON_PRETTY_PRINT ) . PHP_EOL;
exit( in_array( false, $tests, true ) ? 1 : 0 );
