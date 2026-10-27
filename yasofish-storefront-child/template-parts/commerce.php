<?php
/** Native catalog filters and owner-facing readiness checks. */
defined( 'ABSPATH' ) || exit;

// Empty HTML price inputs mean no bound, not a zero-price ceiling in WC_Query.
add_action( 'init', static function () {
    foreach ( array( 'min_price', 'max_price' ) as $key ) {
        if ( isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] ) && '' === trim( wp_unslash( $_GET[ $key ] ) ) ) { unset( $_GET[ $key ] ); }
    }
}, 5 );

function yasofish_commerce_filters() {
    if ( ! function_exists( 'is_shop' ) || ! ( is_shop() || is_product_taxonomy() || ( is_search() && 'product' === get_query_var( 'post_type' ) ) ) ) { return; }
    $GLOBALS['yasofish_catalog_open'] = true;
    $raw_category = isset( $_GET['product_cat'] ) && is_scalar( $_GET['product_cat'] ) ? wp_unslash( $_GET['product_cat'] ) : '';
    $category = is_product_category() ? get_queried_object()->slug : sanitize_title( $raw_category );
    $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
    echo '<div class="yf-native-catalog"><aside><form class="yf-catalog-filters" method="get" action="' . esc_url( yasofish_visual_shop_url() ) . '" role="search"><h2>Encuentra tu equipo</h2><input type="hidden" name="post_type" value="product">';
    echo '<label for="yf-product-search">Buscar productos</label><input id="yf-product-search" type="search" name="s" value="' . esc_attr( get_search_query() ) . '" placeholder="Cañas, carretes, señuelos…">';
    echo '<label for="yf-category">Categoría</label><select id="yf-category" name="product_cat"><option value="">Todas las categorías</option>';
    if ( ! is_wp_error( $terms ) ) { foreach ( $terms as $term ) { printf( '<option value="%s" %s>%s</option>', esc_attr( $term->slug ), selected( $category, $term->slug, false ), esc_html( $term->name ) ); } }
    echo '</select><div class="yf-sale-filter"><label><input type="checkbox" name="yf_on_sale" value="1" ' . checked( isset( $_GET['yf_on_sale'] ) && '1' === $_GET['yf_on_sale'], true, false ) . '> Solo ofertas</label></div><div class="yf-price-fields">';
    foreach ( array( 'min_price' => 'Precio mínimo (CLP)', 'max_price' => 'Precio máximo (CLP)' ) as $key => $label ) {
        $value = isset( $_GET[ $key ] ) && is_scalar( $_GET[ $key ] ) ? wc_format_decimal( wp_unslash( $_GET[ $key ] ) ) : '';
        printf( '<label for="yf-%1$s">%2$s<input id="yf-%1$s" type="number" name="%1$s" min="0" step="1" value="%3$s"></label>', esc_attr( $key ), esc_html( $label ), esc_attr( $value ) );
    }
    echo '</div>';
    $orderby = isset( $_GET['orderby'] ) && is_scalar( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '';
    if ( $orderby ) { printf( '<input type="hidden" name="orderby" value="%s">', esc_attr( $orderby ) ); }
    echo '<button type="submit">Aplicar filtros</button><a class="yf-clear-filters" href="' . esc_url( yasofish_visual_shop_url() ) . '">Limpiar filtros</a></form></aside><div class="yf-native-results">';
}
add_action( 'woocommerce_before_shop_loop', 'yasofish_commerce_filters', 5 );
add_action( 'woocommerce_no_products_found', 'yasofish_commerce_filters', 5 );
add_action( 'woocommerce_after_shop_loop', static function () { if ( ! empty( $GLOBALS['yasofish_catalog_open'] ) ) { echo '</div></div>'; $GLOBALS['yasofish_catalog_open'] = false; } }, 100 );
add_action( 'woocommerce_no_products_found', static function () {
    if ( ! empty( $GLOBALS['yasofish_catalog_open'] ) ) { echo '<p>Estamos preparando el catálogo. Prueba otra categoría o consulta disponibilidad por WhatsApp.</p></div></div>'; $GLOBALS['yasofish_catalog_open'] = false; }
}, 20 );
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
add_action( 'woocommerce_product_query', static function ( $query ) {
    if ( ! isset( $_GET['yf_on_sale'] ) || '1' !== $_GET['yf_on_sale'] ) { return; }
    $ids = wc_get_product_ids_on_sale();
    $existing = $query->get( 'post__in' );
    if ( $existing ) { $ids = array_intersect( $ids, $existing ); }
    $query->set( 'post__in', $ids ? array_values( $ids ) : array( 0 ) );
} );

function yasofish_commerce_page_url( $key ) {
    $pages = get_option( 'yasofish_information_pages', array() );
    $id = (int) ( $pages[ $key ] ?? 0 );
    if ( ! $id || 'publish' !== get_post_status( $id ) ) { return ''; }
    if ( get_post_meta( $id, '_yasofish_policy_review', true ) && 'yasofish.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) && ! current_user_can( 'edit_pages' ) ) { return ''; }
    return get_permalink( $id );
}
function yasofish_commerce_page_links() {
    $pages = get_option( 'yasofish_information_pages', array() );
    $labels = array( 'compra' => 'Información de compra', 'despachos' => 'Despachos y retiros', 'devoluciones' => 'Cambios y devoluciones', 'garantia' => 'Garantía legal', 'privacidad' => 'Privacidad', 'terminos' => 'Términos y condiciones', 'contacto' => 'Contacto' );
    foreach ( $labels as $key => $label ) {
        $url = yasofish_commerce_page_url( $key );
        if ( $url ) { printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) ); }
    }
}
add_filter( 'wp_robots', static function ( $robots ) {
    if ( is_page() && get_post_meta( get_queried_object_id(), '_yasofish_policy_review', true ) ) { $robots['noindex'] = true; $robots['nofollow'] = true; }
    return $robots;
} );
add_action( 'add_meta_boxes_page', static function ( $post ) {
    if ( ! get_post_meta( $post->ID, '_yasofish_policy_review', true ) && ! get_post_meta( $post->ID, '_yasofish_policy_approved', true ) ) { return; }
    add_meta_box( 'yasofish-policy-review', 'Revisión comercial YASOFISH', static function ( $page ) {
        wp_nonce_field( 'yasofish_policy_approval', 'yasofish_policy_nonce' );
        echo '<p>Confirma los datos y la revisión legal antes de aprobar. Edita el contenido y retira el aviso de borrador solamente después de esa aprobación.</p><label><input type="checkbox" name="yasofish_policy_approved" value="1" ' . checked( get_post_meta( $page->ID, '_yasofish_policy_approved', true ), 'yes', false ) . '> Revisado y aprobado por el titular</label><p>Esta marca no activa pagos ni ventas.</p>';
    }, 'page', 'side' );
} );
add_action( 'save_post_page', static function ( $id ) {
    if ( wp_is_post_revision( $id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $id ) || ! isset( $_POST['yasofish_policy_nonce'] ) || ! is_string( $_POST['yasofish_policy_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['yasofish_policy_nonce'] ) ), 'yasofish_policy_approval' ) ) { return; }
    if ( isset( $_POST['yasofish_policy_approved'] ) && '1' === $_POST['yasofish_policy_approved'] ) {
        update_post_meta( $id, '_yasofish_policy_approved', 'yes' ); delete_post_meta( $id, '_yasofish_policy_review' );
    } else {
        delete_post_meta( $id, '_yasofish_policy_approved' ); update_post_meta( $id, '_yasofish_policy_review', 'yes' );
    }
} );
add_action( 'template_redirect', static function () {
    if ( is_page() && get_post_meta( get_queried_object_id(), '_yasofish_policy_review', true ) && 'yasofish.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) && ! current_user_can( 'edit_pages' ) ) {
        global $wp_query;
        $wp_query->set_404(); status_header( 404 ); nocache_headers();
    }
}, 1 );

add_action( 'customize_register', static function ( $customizer ) {
    foreach ( array( 'business_name' => 'Razón social (pendiente de confirmar)', 'business_rut' => 'RUT del proveedor (pendiente de confirmar)', 'attention_days' => 'Días de atención', 'dispatch_zones' => 'Zonas de despacho', 'dispatch_rates' => 'Tarifas o criterio de cotización', 'dispatch_times' => 'Plazos de despacho confirmados' ) as $key => $label ) {
        $id = 'yasofish_' . $key;
        $customizer->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field', 'capability' => 'edit_theme_options' ) );
        $customizer->add_control( $id, array( 'label' => $label, 'section' => 'yasofish_storefront', 'type' => 'text' ) );
    }
} );
add_action( 'admin_menu', static function () {
    add_theme_page( 'Preparación YASOFISH', 'Preparación YASOFISH', 'edit_theme_options', 'yasofish-readiness', 'yasofish_commerce_admin' );
} );
function yasofish_commerce_admin() {
    if ( ! current_user_can( 'edit_theme_options' ) ) { return; }
    echo '<div class="wrap"><h1>Preparación comercial YASOFISH</h1><p>Los campos vacíos permanecen pendientes. No se publican bancos ni datos tributarios desde el tema. Antes de habilitar ventas revisa impuestos, boleta y todos los borradores.</p><table class="widefat striped"><tbody>';
    foreach ( array( 'whatsapp' => 'WhatsApp', 'email' => 'Correo', 'store_location' => 'Dirección', 'store_hours' => 'Horarios', 'attention_days' => 'Días de atención', 'pickup_point' => 'Retiro', 'delivery_hours' => 'Delivery', 'dispatch_zones' => 'Zonas', 'dispatch_rates' => 'Tarifas', 'dispatch_times' => 'Plazos', 'business_name' => 'Razón social', 'business_rut' => 'RUT' ) as $key => $label ) {
        printf( '<tr><th>%s</th><td>%s</td></tr>', esc_html( $label ), get_theme_mod( 'yasofish_' . $key, '' ) ? 'Guardado: requiere validación del titular' : 'Pendiente de completar' );
    }
    echo '</tbody></table><h2>Administración nativa</h2><ul>';
    foreach ( array( 'edit.php?post_type=product' => 'Productos, fotos, precios y existencias', 'edit-tags.php?taxonomy=product_cat&post_type=product' => 'Categorías', 'admin.php?page=wc-orders' => 'Pedidos y estados', 'admin.php?page=wc-settings&tab=checkout&section=bacs' => 'BACS y bancos (mantener desactivado)', 'admin.php?page=wc-settings&tab=tax' => 'Impuestos: confirmar con asesor', 'edit.php?post_type=page' => 'Páginas y borradores legales', 'customize.php' => 'Datos comerciales y redes' ) as $path => $label ) { printf( '<li><a href="%s">%s</a></li>', esc_url( admin_url( $path ) ), esc_html( $label ) ); }
    echo '</ul><p>No hay pasarela Mercado Pago online. El POS presencial no se administra desde este tema.</p></div>';
}
add_action( 'wp_enqueue_scripts', static function () {
    wp_enqueue_style( 'yasofish-commerce', get_theme_file_uri( 'assets/css/commerce.css' ), array( 'yasofish-visual' ), (string) filemtime( __DIR__ . '/../assets/css/commerce.css' ) );
}, 110 );
