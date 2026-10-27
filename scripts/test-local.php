<?php
// Read-only integration checks against the active LocalWP theme.
ob_start();
require __DIR__ . '/audit-local.php';
ob_end_clean();
$tests = array();
$tests['active_version'] = '1.2.1' === wp_get_theme()->get( 'Version' );
$tests['sanitize_phone'] = '56912345678' === yasofish_storefront_sanitize_whatsapp( '+56 (9) 1234-5678' );
$tests['sanitize_copy'] = 'Hola' === yasofish_storefront_sanitize_setting( '<b>Hola</b>' );
$errors = new WP_Error();
yasofish_storefront_require_quote( array(), $errors );
$tests['classic_checkout_guard'] = in_array( 'yasofish_quote_required', $errors->get_error_codes(), true );
$request = new WP_REST_Request( 'POST', '/wc/store/v1/checkout' );
$blocked = yasofish_storefront_guard_store_api( null, null, $request );
$tests['store_api_checkout_guard'] = is_wp_error( $blocked ) && 403 === $blocked->get_error_data()['status'];
$request = new WP_REST_Request( 'GET', '/wc/store/v1/products' );
$tests['catalog_api_allowed'] = null === yasofish_storefront_guard_store_api( null, null, $request );
$tests['only_bacs'] = array( 'bacs' => 'bank' ) === yasofish_storefront_bacs_only( array( 'bacs' => 'bank', 'cod' => 'cash', 'mercadopago' => 'card' ) );
$tests['disabled_bacs_stays_disabled'] = array() === yasofish_storefront_bacs_only( array( 'cod' => 'cash' ) );
$tests['parent_preserved'] = 'storefront' === get_template();
echo wp_json_encode( $tests, JSON_PRETTY_PRINT ) . PHP_EOL;
exit( in_array( false, $tests, true ) ? 1 : 0 );
