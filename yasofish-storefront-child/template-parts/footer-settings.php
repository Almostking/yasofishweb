<?php
/** Editable reference information; explicit approval is bound to its exact values. */
defined( 'ABSPATH' ) || exit;

function yasofish_footer_reference_keys() {
    return array( 'yasofish_store_location', 'yasofish_store_hours', 'yasofish_pickup_point', 'yasofish_delivery_hours', 'yasofish_preparation', 'yasofish_footer_dispatch_terms' );
}
function yasofish_footer_fingerprint( $values ) {
    return hash( 'sha256', wp_json_encode( $values ) );
}
function yasofish_footer_approved() {
    $values = array();
    foreach ( yasofish_footer_reference_keys() as $key ) { $values[ $key ] = (string) get_theme_mod( $key, '' ); }
    $approval = get_theme_mod( 'yasofish_footer_approval', '' );
    return is_string( $approval ) && hash_equals( yasofish_footer_fingerprint( $values ), $approval );
}
function yasofish_footer_reference_visible() {
    return yasofish_footer_approved() || current_user_can( 'edit_theme_options' );
}
function yasofish_footer_sanitize_approval( $value, $setting ) {
    if ( ! $value || ! current_user_can( 'edit_theme_options' ) ) { return ''; }
    $values = array();
    foreach ( yasofish_footer_reference_keys() as $key ) {
        $field = $setting->manager->get_setting( $key );
        $values[ $key ] = $field ? (string) $field->sanitize( $field->post_value( $field->value() ) ) : (string) get_theme_mod( $key, '' );
    }
    return yasofish_footer_fingerprint( $values );
}
add_action( 'customize_register', static function ( $customizer ) {
    foreach ( array( 'yasofish_preparation' => 'Preparación de pedidos (referencia pendiente)', 'yasofish_footer_dispatch_terms' => 'Despacho del footer (referencia pendiente)', 'yasofish_footer_news' => 'Texto de novedades del footer' ) as $key => $label ) {
        $customizer->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field', 'capability' => 'edit_theme_options' ) );
        $customizer->add_control( $key, array( 'label' => $label, 'section' => 'yasofish_storefront', 'type' => 'textarea' ) );
    }
    $customizer->add_setting( 'yasofish_footer_approval', array( 'default' => '', 'sanitize_callback' => 'yasofish_footer_sanitize_approval', 'sanitize_js_callback' => static function () { return yasofish_footer_approved(); }, 'capability' => 'edit_theme_options' ) );
    $customizer->add_control( 'yasofish_footer_approval', array( 'label' => 'Apruebo dirección, horario, retiro, delivery, preparación y despacho para publicación comercial', 'description' => 'Sin aprobación, estos datos solo los ve un administrador. Cambiar cualquiera invalida la aprobación: revisa y vuelve a aprobar. No activa ventas ni pagos.', 'section' => 'yasofish_storefront', 'type' => 'checkbox' ) );
}, 20 );
