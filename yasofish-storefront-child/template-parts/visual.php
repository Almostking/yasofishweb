<?php
/** Original YASOFISH composition with WordPress/WooCommerce URLs and data. */
defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/icons.php';

function yasofish_visual_shop_url() {
    return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
}
function yasofish_visual_category_url( $slug, $label ) {
    $term = get_term_by( 'slug', $slug, 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) {
        $url = get_term_link( $term );
        if ( ! is_wp_error( $url ) ) { return $url; }
    }
    return add_query_arg( 'product_cat', $slug, yasofish_visual_shop_url() );
}
function yasofish_visual_brand( $footer = false ) {
    $id = get_theme_mod( 'custom_logo' );
    $logo = $id ? wp_get_attachment_image_url( $id, 'medium' ) : get_theme_file_uri( 'assets/images/yasofish-logo.png' );
    printf( '<a class="logo %s" href="%s" aria-label="YASOFISH Chile, inicio"><img src="%s" width="%d" height="%d" alt="YASOFISH Chile"><span class="brandcopy"><b>YASOFISH<span>CHILE</span></b><small>ARTÍCULOS DE PESCA</small></span></a>', $footer ? 'footerlogo' : '', esc_url( home_url( '/' ) ), esc_url( $logo ), $footer ? 82 : 100, $footer ? 82 : 100 );
}
function yasofish_visual_whatsapp( $label, $class = 'btn outline' ) {
    $url = yasofish_storefront_whatsapp_url( 'Hola YASOFISH, necesito asesoría para mi próxima salida de pesca.' );
    if ( $url ) { printf( '<a class="%s" href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_attr( $class ), esc_url( $url ), esc_html( $label ) ); }
}
function yasofish_visual_socials() {
    $defaults = array( 'instagram' => 'https://www.instagram.com/yasofish.chile/', 'facebook' => 'https://www.facebook.com/share/1K7tk5ff9G/', 'tiktok' => 'https://www.tiktok.com/@yasofishchile' );
    foreach ( $defaults as $name => $default ) {
        $url = get_theme_mod( 'yasofish_' . $name, $default );
        if ( $url ) {
            printf( '<a class="yf-social" href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s<span class="yf-social-name">%s</span></a>', esc_url( $url ), esc_attr( ucfirst( $name ) ), yasofish_visual_icon( $name ), esc_html( ucfirst( $name ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static local SVG.
        }
    }
}
function yasofish_visual_topline() {
    if ( yasofish_reference_mode() ) { $html=yasofish_reference_data()['sourceHeader']; echo yasofish_faithful_html(substr($html,0,strpos($html,'<header>'))); return; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Immutable original markup; links escaped by adapter.
    $notices = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_theme_mod( 'yasofish_announcement', 'De pescador a pescador · Conversemos de tu próxima salida' ) ) ) ) );
    if ( ! $notices ) { $notices = array( 'YASOFISH Chile · De pescador a pescador' ); }
    echo '<div class="yf-status">YASOFISH CHILE · Equipamiento de pesca · De pescador a pescador</div>';
    printf( '<div class="topline" data-yf-announcements="%s"><div class="announcement"><span class="announcement-label">YASOFISH</span><span class="announcement-message" data-yf-announcement-message>%s</span></div><button class="announcement-toggle" type="button" data-yf-announcement-toggle aria-label="Pausar avisos">Ⅱ</button></div>', esc_attr( wp_json_encode( $notices ) ), esc_html( $notices[0] ) );
}
function yasofish_visual_header() {
    if ( yasofish_reference_mode() ) {
        $html=yasofish_reference_data()['sourceHeader'];$html=substr($html,strpos($html,'<header>'));
        $html=str_replace(array('<header>','</header>','<nav aria-label=','<form id="search">','<input aria-label="Buscar productos"'),array('<div class="yf-masthead">','</div>','<nav class="yf-primary-nav" aria-label=','<form id="search" method="get" action="'.esc_url(yasofish_visual_shop_url()).'"><input type="hidden" name="post_type" value="product">','<input name="s" type="search" aria-label="Buscar productos"'),$html);
        $count=WC()->cart?WC()->cart->get_cart_contents_count():0;
        $html=str_replace('<span id="count">0</span>','<span id="count" data-yf-cart-count>'.absint($count).'</span>',$html);
        echo yasofish_faithful_html($html);return; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Original markup with escaped links.
    }
    echo '<div class="yf-masthead">';
    yasofish_visual_brand();
    printf( '<form role="search" method="get" action="%s"><input type="search" name="s" value="%s" aria-label="Buscar productos" placeholder="¿Qué necesitas para tu próxima pesca?"><input type="hidden" name="post_type" value="product"><button aria-label="Buscar" type="submit">⌕</button></form>', esc_url( home_url( '/' ) ), esc_attr( get_search_query() ) );
    if ( function_exists( 'wc_get_page_permalink' ) ) {
        printf( '<a class="accountlink" href="%s"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M12 12a4.2 4.2 0 1 0 0-8.4 4.2 4.2 0 0 0 0 8.4Zm-8 8.2a8 8 0 0 1 16 0" fill="none" stroke="currentColor" stroke-width="1.8"/></svg><span>%s<small>Mi cuenta</small></span></a>', esc_url( wc_get_page_permalink( 'myaccount' ) ), esc_html( is_user_logged_in() ? 'Mi cuenta' : 'Iniciar sesión' ) );
        $count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        printf( '<a class="cartlink" href="%s">Mi carro <span data-yf-cart-count>%d</span> ↗</a>', esc_url( wc_get_cart_url() ), absint( $count ) );
    }
    echo '</div><nav id="site-navigation" class="yf-primary-nav" aria-label="Navegación principal">';
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'yf-assigned-menu', 'fallback_cb' => false ) );
    } else {
        printf( '<a href="%s">Inicio</a><a href="%s">Todo el catálogo</a>', esc_url( home_url( '/' ) ), esc_url( yasofish_visual_shop_url() ) );
        foreach ( array( 'canas' => 'Cañas', 'carretes' => 'Carretes', 'senuelos' => 'Señuelos', 'accesorios' => 'Accesorios' ) as $slug => $label ) {
            printf( '<a href="%s">%s</a>', esc_url( yasofish_visual_category_url( $slug, $label ) ), esc_html( $label ) );
        }
        $information_url = yasofish_commerce_page_url( 'compra' );
        printf( '<a href="%s">Información</a>', esc_url( $information_url ? $information_url : home_url( '/#yf-information' ) ) );
    }
    yasofish_visual_whatsapp( 'Te asesoramos ↗', 'yf-nav-advice' );
    echo '</nav>';
}
function yasofish_visual_footer() {
    if ( yasofish_reference_mode() ) { $html=yasofish_reference_data()['sourceFooter'];$html=preg_replace('~^<footer class="yf-footer">~','<div class="yf-footer" id="yf-information">',$html);$html=preg_replace('~</footer>$~','</div>',$html);echo yasofish_faithful_html($html);echo '<button class="floating" type="button" data-wa="general">'.yasofish_visual_icon('whatsapp').'<span>Conversemos</span></button>';return; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Immutable reference markup and static SVG; local mode only.
    echo '<div class="yf-footer" id="yf-information"><div class="yf-footer-inner"><div class="yf-footer-top">';
    yasofish_visual_brand( true );
    echo '<div class="yf-footer-socials"><span>SÍGUENOS</span>';
    yasofish_visual_socials();
    $wa = yasofish_storefront_whatsapp_url( 'Hola YASOFISH, necesito asesoría.' );
    if ( $wa ) { printf( '<a class="yf-social" href="%s" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( $wa ), yasofish_visual_icon( 'whatsapp' ) ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static local SVG.
    echo '</div></div>';
    $references_visible = yasofish_footer_reference_visible();
    if ( $references_visible && ! yasofish_footer_approved() ) { echo '<p class="yf-footer-review">Referencia de desarrollo · datos comerciales pendientes de aprobación.</p>'; }
    echo '<div class="yf-footer-columns"><section><h3>Pesca</h3>';
    foreach ( array( 'canas' => 'Cañas', 'carretes' => 'Carretes', 'senuelos' => 'Señuelos', 'accesorios' => 'Accesorios' ) as $slug => $label ) { printf( '<a href="%s">%s</a>', esc_url( yasofish_visual_category_url( $slug, $label ) ), esc_html( $label ) ); }
    echo '</section><section><h3>Atención al cliente</h3>';
    if ( $wa ) { printf( '<a class="yf-footer-cta" href="%s" target="_blank" rel="noopener noreferrer">%s Consultar por WhatsApp ↗</a>', esc_url( $wa ), yasofish_visual_icon( 'whatsapp' ) ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
    if ( function_exists( 'wc_get_page_permalink' ) ) { printf( '<a href="%s">Mi cuenta / Crear cuenta</a><a href="%s">Mi carrito</a>', esc_url( wc_get_page_permalink( 'myaccount' ) ), esc_url( wc_get_cart_url() ) ); }
    foreach ( array( 'compra' => 'Información de compra y privacidad', 'terminos' => 'Términos y condiciones · borrador' ) as $key => $label ) {
        $url = yasofish_commerce_page_url( $key );
        if ( 'terminos' === $key ) { $pages = get_option( 'yasofish_information_pages', array() ); if ( get_post_meta( (int) ( $pages[$key] ?? 0 ), '_yasofish_policy_approved', true ) ) { $label = 'Términos y condiciones'; } }
        if ( $url ) { printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) ); }
    }
    $phone = yasofish_storefront_sanitize_whatsapp( get_theme_mod( 'yasofish_phone', '' ) );
    if ( $phone ) { $display_phone = preg_match( '/^56(9)(\d{4})(\d{4})$/', $phone, $parts ) ? '+56 ' . $parts[1] . ' ' . $parts[2] . ' ' . $parts[3] : '+' . $phone; printf( '<a href="tel:+%s">Llámanos: %s</a>', esc_attr( $phone ), esc_html( $display_phone ) ); }
    $email = sanitize_email( get_theme_mod( 'yasofish_email', '' ) );
    if ( $email ) { printf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) ); }
    if ( $references_visible ) {
        $copy = trim( get_theme_mod( 'yasofish_preparation', '' ) . ' ' . get_theme_mod( 'yasofish_footer_dispatch_terms', '' ) );
        if ( $copy ) { printf( '<p>%s</p>', esc_html( $copy ) ); }
    }
    echo '</section><section><h3>Ubicación</h3>';
    if ( $references_visible ) {
        foreach ( array( 'yasofish_store_location', 'yasofish_store_hours', 'yasofish_pickup_point', 'yasofish_delivery_hours' ) as $key ) {
            $value = get_theme_mod( $key, '' );
            if ( ! $value ) { continue; }
            if ( 'yasofish_store_location' === $key ) {
                $address_parts = explode( ',', $value, 2 );
                $address_html = esc_html( $address_parts[0] ) . ( isset( $address_parts[1] ) ? '<br><strong>' . esc_html( trim( $address_parts[1] ) ) . '</strong>' : '' );
                echo '<p class="yf-location"><svg class="yf-location-mark" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 22s8-4.5 8-12a8 8 0 1 0-16 0c0 7.5 8 12 8 12z"/><circle cx="12" cy="10" r="2.5" fill="#082b57"/></svg><span>' . $address_html . '</span></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Both address parts escaped above.
            } else { printf( '<p>%s</p>', esc_html( $value ) ); }
        }
    }
    echo '</section><section><h3>Novedades de pesca</h3>';
    $news = get_theme_mod( 'yasofish_footer_news', '' );
    if ( $news ) { printf( '<p>%s</p>', esc_html( $news ) ); }
    echo '<p class="yf-footer-social-label">Síguenos en redes</p><div class="yf-footer-novelty-social">';
    yasofish_visual_socials();
    echo '</div></section></div><div class="yf-footer-payments"><div><h3>Métodos de pago previstos</h3><small>Stock y despacho confirmados antes de pagar</small></div><div class="yf-payment-list"><span>Transferencia directa <small>previa coordinación</small></span><span>Mercado Pago en tienda <small>solo presencial</small></span></div></div>';
    printf( '<div class="yf-footer-bottom"><span>© %s YASOFISH Chile</span><span>Catálogo exclusivamente de pesca</span></div></div></div>', esc_html( wp_date( 'Y' ) ) );
    if ( $wa ) { printf( '<a class="floating" href="%s" target="_blank" rel="noopener noreferrer">%s<span>Conversemos</span></a>', esc_url( $wa ), yasofish_visual_icon( 'whatsapp' ) ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static local SVG.
}
function yasofish_visual_setup() {
    foreach ( array( 0 => 'storefront_header_container', 20 => 'storefront_site_branding', 30 => 'storefront_secondary_navigation', 40 => 'storefront_product_search', 41 => 'storefront_header_container_close', 42 => 'storefront_primary_navigation_wrapper', 50 => 'storefront_primary_navigation', 60 => 'storefront_header_cart', 68 => 'storefront_primary_navigation_wrapper_close' ) as $priority => $callback ) { remove_action( 'storefront_header', $callback, $priority ); }
    remove_action( 'storefront_header', 'yasofish_storefront_branding', 20 );
    add_action( 'storefront_header', 'yasofish_visual_header', 20 );
    remove_action( 'storefront_before_header', 'yasofish_storefront_announcement', 5 );
    add_action( 'storefront_before_header', 'yasofish_visual_topline', 5 );
    remove_action( 'storefront_footer', 'yasofish_storefront_footer_contact', 5 );
    remove_action( 'storefront_footer', 'yasofish_storefront_footer_details', 10 );
    remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
    add_action( 'storefront_footer', 'yasofish_visual_footer', 10 );
}
add_action( 'after_setup_theme', 'yasofish_visual_setup', 200 );
add_filter( 'body_class', static function ( $classes ) { $classes[] = 'yf-design'; return $classes; } );
add_action( 'get_header', static function () {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    }
} );
add_action( 'wp_enqueue_scripts', static function () {
    wp_dequeue_style( 'storefront-fonts' );
    $file = get_stylesheet_directory() . '/assets/css/visual.css';
    wp_enqueue_style( 'yasofish-visual', get_theme_file_uri( 'assets/css/visual.css' ), array( 'yasofish-storefront-style' ), (string) filemtime( $file ) );
}, 100 );
add_filter( 'woocommerce_add_to_cart_fragments', static function ( $fragments ) {
    $fragments['[data-yf-cart-count]'] = '<span data-yf-cart-count>' . absint( WC()->cart->get_cart_contents_count() ) . '</span>';
    return $fragments;
} );
