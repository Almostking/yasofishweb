<?php
/** Local-only, backed-up preparation of categories, interface and review pages. */
if ( 'cli' !== PHP_SAPI ) { exit( 1 ); }
$root = getenv( 'YASOFISH_WP_ROOT' );
$backup = getenv( 'YASOFISH_COMMERCE_BACKUP' );
if ( ! $root || ! $backup || ! is_file( $root . '/wp-load.php' ) ) { fwrite( STDERR, "Set YASOFISH_WP_ROOT and YASOFISH_COMMERCE_BACKUP outside public web root.\n" ); exit( 2 ); }
$parent = dirname( $backup );
if ( ! is_dir( $parent ) ) { mkdir( $parent, 0700, true ); }
if ( 0 === stripos( str_replace( '\\', '/', realpath( $parent ) ) . '/', str_replace( '\\', '/', realpath( $root ) ) . '/' ) ) { exit( 2 ); }
define( 'DISABLE_WP_CRON', true );
require $root . '/wp-load.php';
if ( 'yasofish.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) ) { throw new RuntimeException( 'This script only runs on yasofish.local. Production requires a separately approved migration.' ); }
$keys = array( 'WPLANG', 'theme_mods_yasofish-storefront-child', 'yasofish_information_pages' );
if ( in_array( '--rollback', $argv, true ) ) {
    $hash = trim( file_get_contents( $backup . '.sha256' ) );
    if ( ! hash_equals( $hash, hash_file( 'sha256', $backup ) ) ) { throw new RuntimeException( 'Invalid backup checksum.' ); }
    $state = json_decode( file_get_contents( $backup ), true );
    foreach ( $state['created_pages'] as $id ) { if ( 'yes' === get_post_meta( $id, '_yasofish_policy_review', true ) ) { wp_delete_post( $id, true ); } }
    foreach ( $state['created_terms'] as $id ) { $term = get_term( $id, 'product_cat' ); if ( $term && ! is_wp_error( $term ) && 0 === (int) $term->count ) { wp_delete_term( $id, 'product_cat' ); } }
    foreach ( $state['page_titles'] as $id => $title ) { if ( get_the_title( $id ) !== $title ) { wp_update_post( array( 'ID' => $id, 'post_title' => $title ) ); } }
    foreach ( $state['options'] as $key => $value ) { if ( false === $value ) { delete_option( $key ); } else { update_option( $key, $value ); } }
    echo "Rollback completed: only recorded local changes restored.\n"; exit;
}
if ( file_exists( $backup ) ) { throw new RuntimeException( 'Use a new backup filename. Existing snapshots are never overwritten.' ); }
$state = array( 'options' => array(), 'page_titles' => array(), 'created_pages' => array(), 'created_terms' => array() );
foreach ( $keys as $key ) { $state['options'][ $key ] = get_option( $key ); }
$titles = array( 'shop' => 'Catálogo', 'cart' => 'Carrito', 'checkout' => 'Finalizar compra', 'myaccount' => 'Mi cuenta' );
foreach ( $titles as $key => $title ) { $id = wc_get_page_id( $key ); if ( $id > 0 ) { $state['page_titles'][ $id ] = get_the_title( $id ); } }
$save = static function () use ( &$state, $backup ) {
    $json = wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    if ( false === file_put_contents( $backup, $json, LOCK_EX ) ) { throw new RuntimeException( 'Backup failed.' ); }
    file_put_contents( $backup . '.sha256', hash_file( 'sha256', $backup ), LOCK_EX );
};
$save();
foreach ( array( 'canas' => 'Cañas', 'carretes' => 'Carretes', 'senuelos' => 'Señuelos', 'accesorios' => 'Accesorios' ) as $slug => $name ) {
    if ( ! get_term_by( 'slug', $slug, 'product_cat' ) ) {
        $term = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
        if ( is_wp_error( $term ) ) { throw new RuntimeException( $term->get_error_message() ); }
        $state['created_terms'][] = $term['term_id']; $save();
    }
}
update_option( 'WPLANG', 'es_CL' );
set_theme_mod( 'yasofish_whatsapp', '56966509563' );
set_theme_mod( 'yasofish_email', 'yasofishchile@gmail.com' );
foreach ( $titles as $key => $title ) { $id = wc_get_page_id( $key ); if ( $id > 0 && get_the_title( $id ) !== $title ) { wp_update_post( array( 'ID' => $id, 'post_title' => $title ) ); } }
$files = glob( __DIR__ . '/../docs/legal/*.html' );
$pages = get_option( 'yasofish_information_pages', array() );
foreach ( $files as $file ) {
    $key = basename( $file, '.html' );
    if ( ! empty( $pages[ $key ] ) && get_post( $pages[ $key ] ) ) { continue; }
    $html = file_get_contents( $file );
    preg_match( '#<h1>(.*?)</h1>#s', $html, $match );
    $title = wp_strip_all_tags( $match[1] ?? $key );
    $html = preg_replace( '#<h1>.*?</h1>#s', '', $html, 1 );
    // Published only in protected LocalWP, visibly marked as unapproved review content.
    $id = wp_insert_post( wp_slash( array( 'post_type' => 'page', 'post_title' => $title, 'post_name' => 'informacion-' . $key, 'post_status' => 'publish', 'post_content' => $html ) ), true );
    if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
    update_post_meta( $id, '_yasofish_policy_review', 'yes' );
    $state['created_pages'][] = $id; $pages[ $key ] = $id; $save();
}
update_option( 'yasofish_information_pages', $pages );
echo wp_json_encode( array( 'language' => get_option( 'WPLANG' ), 'created_terms' => count( $state['created_terms'] ), 'created_review_pages' => count( $state['created_pages'] ), 'payment_settings_untouched' => true, 'backup_sha256' => hash_file( 'sha256', $backup ) ), JSON_PRETTY_PRINT ) . PHP_EOL;
