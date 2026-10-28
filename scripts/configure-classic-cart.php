<?php
/** CLI only. Back up and convert the assigned cart, preserving page ID and URL. */
if ( 'cli' !== PHP_SAPI ) { exit( 1 ); }
$root = getenv( 'YASOFISH_WP_ROOT' );
$backup_dir = getenv( 'YASOFISH_BACKUP_DIR' );
if ( ! $root || ! is_file( $root . '/wp-load.php' ) || ! $backup_dir ) {
    fwrite( STDERR, "Set YASOFISH_WP_ROOT and YASOFISH_BACKUP_DIR (outside the web root).\n" ); exit( 2 );
}
if ( ! is_dir( $backup_dir ) && ! mkdir( $backup_dir, 0700, true ) ) { exit( 2 ); }
$resolved_root = str_replace( '\\', '/', realpath( $root ) );
$resolved_backup = str_replace( '\\', '/', realpath( $backup_dir ) );
if ( 0 === stripos( $resolved_backup . '/', $resolved_root . '/' ) ) {
    fwrite( STDERR, "Backups must be outside the public web root.\n" ); exit( 2 );
}
define( 'DISABLE_WP_CRON', true );
require $root . '/wp-load.php';
if ( ! function_exists( 'wc_get_page_id' ) ) { throw new RuntimeException( 'WooCommerce required.' ); }
$host = wp_parse_url( home_url(), PHP_URL_HOST );
if ( 'yasofish.local' !== $host && ! in_array( '--allow-nonlocal', $argv, true ) ) {
    throw new RuntimeException( 'Nonlocal site requires separate approval and --allow-nonlocal.' );
}
$id = wc_get_page_id( 'cart' );
$page = get_post( $id, ARRAY_A );
if ( ! $page || 'page' !== $page['post_type'] ) { throw new RuntimeException( 'Assigned cart page missing.' ); }
$bacs = get_option( 'woocommerce_bacs_settings', array() );
$before = array(
    'page' => $page, 'url' => get_permalink( $id ),
    'cart_assignment' => get_option( 'woocommerce_cart_page_id' ),
    'bacs_enabled' => $bacs['enabled'] ?? 'no',
    'coming_soon' => get_option( 'woocommerce_coming_soon' ),
    'theme_version' => wp_get_theme()->get( 'Version' ),
);
$target_content = '[woocommerce_cart]';
$restoring = false;
foreach ( $argv as $arg ) {
    if ( 0 !== strpos( $arg, '--restore=' ) ) { continue; }
    $source = substr( $arg, strlen( '--restore=' ) );
    if ( ! is_file( $source ) || ! is_file( $source . '.sha256' ) ) { throw new RuntimeException( 'Restore backup/checksum missing.' ); }
    $expected = explode( ' ', trim( file_get_contents( $source . '.sha256' ) ) )[0];
    if ( ! hash_equals( $expected, hash_file( 'sha256', $source ) ) ) { throw new RuntimeException( 'Restore checksum mismatch.' ); }
    $saved = json_decode( file_get_contents( $source ), true );
    if ( (int) ( $saved['page']['ID'] ?? 0 ) !== $id || ( $saved['url'] ?? '' ) !== $before['url'] || ( $saved['cart_assignment'] ?? null ) !== $before['cart_assignment'] ) {
        throw new RuntimeException( 'Backup belongs to a different page or site.' );
    }
    $target_content = $saved['page']['post_content'];
    $restoring = true;
}
if ( ! in_array( '--apply', $argv, true ) ) {
    echo wp_json_encode( array( 'cart_id' => $id, 'url' => $before['url'], 'blocks' => has_block( 'woocommerce/cart', $page['post_content'] ), 'classic' => has_shortcode( $page['post_content'], 'woocommerce_cart' ) ), JSON_PRETTY_PRINT ) . PHP_EOL;
    exit;
}
$file = $resolved_backup . '/cart-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json';
$json = wp_json_encode( $before, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
if ( false === file_put_contents( $file, $json, LOCK_EX ) ) { throw new RuntimeException( 'Backup write failed.' ); }
$hash = hash_file( 'sha256', $file );
if ( false === file_put_contents( $file . '.sha256', $hash . '  ' . basename( $file ) . "\n", LOCK_EX ) || json_decode( file_get_contents( $file ), true ) !== $before ) {
    throw new RuntimeException( 'Backup verification failed; page unchanged.' );
}
$changed = $target_content !== $page['post_content'];
if ( $changed ) {
    if ( $restoring ) {
        // Restore existing block HTML exactly; do not run it through CLI KSES filters.
        global $wpdb;
        if ( false === $wpdb->update( $wpdb->posts, array( 'post_content' => $target_content ), array( 'ID' => $id ) ) ) { throw new RuntimeException( 'Restore failed.' ); }
    } else {
        $result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $target_content ) ), true );
        if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
    }
}
$bacs['enabled'] = 'no';
update_option( 'woocommerce_bacs_settings', $bacs );
update_option( 'woocommerce_coming_soon', 'yes' );
clean_post_cache( $id );
$ok = $target_content === get_post_field( 'post_content', $id )
    && get_permalink( $id ) === $before['url']
    && get_option( 'woocommerce_cart_page_id' ) === $before['cart_assignment']
    && get_post_field( 'post_name', $id ) === $page['post_name']
    && 'no' === get_option( 'woocommerce_bacs_settings' )['enabled']
    && 'yes' === get_option( 'woocommerce_coming_soon' );
echo wp_json_encode( array( 'verified' => $ok, 'changed' => $changed, 'restoring' => $restoring, 'cart_id' => $id, 'url' => get_permalink( $id ), 'backup' => $file, 'sha256' => $hash, 'bacs_enabled' => 'no', 'coming_soon' => 'yes' ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
exit( $ok ? 0 : 1 );
