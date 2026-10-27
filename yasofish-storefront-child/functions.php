<?php
/** YASOFISH Chile Storefront child theme. */
defined( 'ABSPATH' ) || exit;

function yasofish_storefront_setup() {
	load_child_theme_textdomain( 'yasofish-storefront', get_stylesheet_directory() . '/languages' );
	add_theme_support( 'woocommerce', array( 'thumbnail_image_width' => 420, 'single_image_width' => 720 ) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'yasofish_storefront_setup', 20 );

function yasofish_storefront_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'yasofish-storefront-style', get_stylesheet_uri(), array( 'storefront-style' ), $version );
	wp_enqueue_script( 'yasofish-announcement-rotator', get_stylesheet_directory_uri() . '/assets/js/announcement-rotator.js', array(), $version, true );
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script( 'yasofish-related-carousel', get_stylesheet_directory_uri() . '/assets/js/related-carousel.js', array(), $version, true );
	}
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_script( 'yasofish-delivery-quote', get_stylesheet_directory_uri() . '/assets/js/delivery-quote.js', array(), $version, true );
	}
}
add_action( 'wp_enqueue_scripts', 'yasofish_storefront_assets', 20 );

/** Editable store copy; payment secrets remain in the official server-side gateway. */
function yasofish_storefront_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'yasofish_storefront', array(
		'title' => __( 'YASOFISH Chile', 'yasofish-storefront' ),
		'description' => __( 'Edita marca, contacto y dirección. Configura productos, despacho y pagos en WooCommerce y sus extensiones.', 'yasofish-storefront' ),
		'priority' => 35,
	) );
	$settings = array(
		'yasofish_announcement' => array( __( 'Avisos rotativos (uno por línea)', 'yasofish-storefront' ), __( "EQUÍPATE PARA PESCAR · Cañas, carretes, señuelos y accesorios\nATENCIÓN PERSONALIZADA · Te asesoramos por WhatsApp\nDESPACHOS A TODO CHILE · Consulta opciones según tu destino", 'yasofish-storefront' ), 320 ),
		'yasofish_hero_title' => array( __( 'Título de portada', 'yasofish-storefront' ), __( '¡Que pique la aventura!', 'yasofish-storefront' ), 100 ),
		'yasofish_hero_copy' => array( __( 'Texto de portada', 'yasofish-storefront' ), __( 'Cañas, carretes y señuelos para tu próxima salida. Explora tu equipo y conversemos de pesca.', 'yasofish-storefront' ), 240 ),
		'yasofish_hero_button' => array( __( 'Texto del botón principal', 'yasofish-storefront' ), __( 'Ver catálogo', 'yasofish-storefront' ), 40 ),
		'yasofish_whatsapp' => array( __( 'WhatsApp comercial (solo dígitos con código país)', 'yasofish-storefront' ), '', 20 ),
		'yasofish_phone' => array( __( 'Teléfono de atención (solo dígitos con código país)', 'yasofish-storefront' ), '', 20 ),
		'yasofish_email' => array( __( 'Correo de atención', 'yasofish-storefront' ), '', 120 ),
		'yasofish_store_location' => array( __( 'Dirección de atención o retiro', 'yasofish-storefront' ), '', 180 ),
		'yasofish_store_hours' => array( __( 'Horario de la tienda', 'yasofish-storefront' ), '', 180 ),
		'yasofish_delivery_hours' => array( __( 'Delivery local', 'yasofish-storefront' ), '', 180 ),
		'yasofish_pickup_point' => array( __( 'Punto de retiro coordinado', 'yasofish-storefront' ), '', 220 ),
	);
	$settings['yasofish_dispatch_terms'] = array( __( 'Condiciones de despacho', 'yasofish-storefront' ), '', 500 );
	foreach ( array( 'instagram', 'facebook', 'tiktok' ) as $network ) {
		$settings[ 'yasofish_' . $network ] = array( ucfirst( $network ) . ' URL', '', 250 );
	}
	foreach ( $settings as $id => $data ) {
		$sanitizer = in_array( $id, array( 'yasofish_whatsapp', 'yasofish_phone' ), true ) ? 'yasofish_storefront_sanitize_whatsapp' : ( 'yasofish_email' === $id ? 'sanitize_email' : ( 'yasofish_announcement' === $id ? 'yasofish_storefront_sanitize_notices' : 'yasofish_storefront_sanitize_setting' ) );
		if ( in_array( $id, array( 'yasofish_instagram', 'yasofish_facebook', 'yasofish_tiktok' ), true ) ) {
			$sanitizer = 'esc_url_raw';
		}
		$wp_customize->add_setting( $id, array(
			'default' => $data[1],
			'sanitize_callback' => $sanitizer,
			'capability' => 'edit_theme_options',
		) );
		$wp_customize->add_control( $id, array(
			'label' => $data[0], 'section' => 'yasofish_storefront', 'type' => 'yasofish_announcement' === $id ? 'textarea' : 'text',
			'input_attrs' => array( 'maxlength' => $data[2] ),
		) );
	}
}
add_action( 'customize_register', 'yasofish_storefront_customize_register' );

function yasofish_storefront_sanitize_setting( $value ) {
	return sanitize_text_field( $value );
}

function yasofish_storefront_sanitize_notices( $value ) {
	$lines = preg_split( '/\r\n|\r|\n/', sanitize_textarea_field( $value ) );
	$lines = array_slice( array_filter( array_map( 'trim', $lines ) ), 0, 6 );
	return implode( "\n", array_map( 'sanitize_text_field', $lines ) );
}

function yasofish_storefront_sanitize_whatsapp( $value ) {
	return preg_replace( '/[^0-9]/', '', sanitize_text_field( $value ) );
}

function yasofish_storefront_whatsapp_url( $message = '' ) {
	$phone = preg_replace( '/[^0-9]/', '', (string) get_theme_mod( 'yasofish_whatsapp', '' ) );
	if ( empty( $phone ) || strlen( $phone ) < 8 || strlen( $phone ) > 15 ) {
		return '';
	}
	return 'https://wa.me/' . $phone . '?text=' . rawurlencode( $message );
}

function yasofish_storefront_announcement() {
	$notice = get_theme_mod( 'yasofish_announcement', __( 'Despachos a todo Chile · Atención de pescador a pescador', 'yasofish-storefront' ) );
	$notices = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $notice ) ) ) );
	if ( empty( $notices ) ) {
		$notices = array( __( 'Novedades de pesca · Cañas, carretes, señuelos y accesorios', 'yasofish-storefront' ) );
	}
	if ( $notice || function_exists( 'wc_get_page_permalink' ) ) {
		echo '<div class="yf-announcement" data-yf-announcements="' . esc_attr( wp_json_encode( $notices ) ) . '"><div class="yf-announcement__content"><strong class="yf-announcement__label">' . esc_html__( 'YASOFISH', 'yasofish-storefront' ) . '</strong><span class="yf-announcement__message" data-yf-announcement-message>' . esc_html( $notices[0] ) . '</span><button class="yf-announcement__toggle" type="button" data-yf-announcement-toggle aria-label="' . esc_attr__( 'Pausar avisos', 'yasofish-storefront' ) . '" title="' . esc_attr__( 'Pausar avisos', 'yasofish-storefront' ) . '">Ⅱ</button></div>';
		if ( function_exists( 'wc_get_page_permalink' ) ) {
			$account_url = wc_get_page_permalink( 'myaccount' );
			if ( $account_url ) {
				printf( '<a class="yf-account-link" href="%1$s"><strong>%2$s</strong><small>%3$s</small></a>', esc_url( $account_url ), esc_html__( 'Iniciar sesión', 'yasofish-storefront' ), esc_html__( 'Crear cuenta', 'yasofish-storefront' ) );
			}
		}
		echo '</div>';
	}
}
add_action( 'storefront_before_header', 'yasofish_storefront_announcement', 5 );

function yasofish_storefront_whatsapp_button( $message, $label = '' ) {
	$url = yasofish_storefront_whatsapp_url( $message );
	if ( $url ) {
		printf( '<a class="yf-whatsapp" href="%1$s" target="_blank" rel="noopener noreferrer"><svg class="yf-brand-icon yf-whatsapp-mark" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.198.297-.768.967-.942 1.165-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.654-2.059-.174-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.15-.174.198-.298.298-.497.099-.198.05-.372-.025-.52-.075-.15-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.478 1.065 2.875 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.307 1.265.49 1.697.627.713.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.174-1.413-.075-.124-.273-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.999-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.893-9.884 2.64.001 5.12 1.03 6.99 2.91a9.825 9.825 0 0 1 2.892 6.987c-.003 5.45-4.437 9.879-9.897 9.879m8.413-18.294A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.88 11.88 0 0 0 5.684 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.41z"/></svg>%2$s</a>', esc_url( $url ), esc_html( $label ? $label : __( 'Consultar por WhatsApp', 'yasofish-storefront' ) ) );
	}
}

/** Build a cart summary for a customer-initiated dispatch quote; no order is submitted. */
function yasofish_storefront_cart_quote_button() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
		return;
	}

	$lines = array();
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$product = isset( $cart_item['data'] ) ? $cart_item['data'] : false;
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$name = wp_strip_all_tags( $product->get_name() );
		$lines[] = sprintf( '%1$d × %2$s', absint( $cart_item['quantity'] ), $name );
	}
	if ( empty( $lines ) ) {
		return;
	}

	$phone = preg_replace( '/\D+/', '', (string) get_theme_mod( 'yasofish_whatsapp', '' ) );
	if ( yasofish_storefront_whatsapp_url() ) {
		printf( '<form class="yf-delivery-quote" data-phone="%1$s" data-items="%2$s">', esc_attr( $phone ), esc_attr( wp_json_encode( $lines ) ) );
		echo '<h3>' . esc_html__( 'Cotiza tu despacho', 'yasofish-storefront' ) . '</h3><p>' . esc_html__( 'Indica el destino para confirmar disponibilidad, costo y plazo antes de pagar.', 'yasofish-storefront' ) . '</p>';
		echo '<label for="yf-delivery-method">Forma de entrega</label><select id="yf-delivery-method" name="method" required><option value="">Selecciona una opción</option><option value="shipping-region">Despacho a domicilio</option><option value="pickup-iqq">Retiro coordinado</option></select>';
		printf( '<p class="yf-field-note">%s</p>', esc_html( get_theme_mod( 'yasofish_dispatch_terms', '' ) ) );
		echo '<label for="yf-delivery-name">Nombre de quien recibe <span aria-hidden="true">*</span></label><input id="yf-delivery-name" name="name" type="text" autocomplete="name" maxlength="80" required>';
		echo '<div class="yf-delivery-grid"><div><label for="yf-delivery-region">Región <span aria-hidden="true">*</span></label><select id="yf-delivery-region" name="region" required><option value="">Selecciona una región</option>';
		$regions = array( 'Arica y Parinacota', 'Tarapacá', 'Antofagasta', 'Atacama', 'Coquimbo', 'Valparaíso', 'Metropolitana de Santiago', 'O’Higgins', 'Maule', 'Ñuble', 'Biobío', 'La Araucanía', 'Los Ríos', 'Los Lagos', 'Aysén', 'Magallanes y de la Antártica Chilena' );
		foreach ( $regions as $region ) {
			printf( '<option value="%1$s">%1$s</option>', esc_attr( $region ) );
		}
		echo '</select></div><div><label for="yf-delivery-commune">Comuna <span aria-hidden="true">*</span></label><input id="yf-delivery-commune" name="commune" type="text" autocomplete="address-level2" maxlength="80" required></div></div>';
		echo '<label for="yf-delivery-city">Ciudad o localidad <span aria-hidden="true">*</span></label><input id="yf-delivery-city" name="city" type="text" autocomplete="address-level2" maxlength="80" required>';
		echo '<div class="yf-delivery-address"><label for="yf-delivery-street">Calle y número <span aria-hidden="true">*</span></label><input id="yf-delivery-street" name="street" type="text" autocomplete="street-address" maxlength="120"><label for="yf-delivery-unit">Casa, departamento, block o piso <span class="yf-optional">(opcional)</span></label><input id="yf-delivery-unit" name="unit" type="text" maxlength="100"><label for="yf-delivery-notes">Referencias o instrucciones para la entrega <span class="yf-optional">(opcional)</span></label><textarea id="yf-delivery-notes" name="notes" rows="3" maxlength="300"></textarea></div>';
		echo '<p class="yf-quote-note">' . esc_html__( 'Este paso solo solicita una cotización. Primero confirmaremos stock, costo y plazo. Si aceptas el total, YASOFISH registrará el pedido y te indicará cómo pagar por transferencia. El pago se verifica antes de preparar el pedido.', 'yasofish-storefront' ) . '</p><button class="checkout-button button alt wc-forward yf-quote-button" type="submit">' . esc_html__( 'Preparar consulta por WhatsApp', 'yasofish-storefront' ) . '</button></form>';
	} else {
		echo '<p class="yf-quote-note">' . esc_html__( 'La tienda habilitará la cotización de despacho por WhatsApp antes de aceptar pagos online.', 'yasofish-storefront' ) . '</p>';
	}
}

/** Keep the initial checkout quote-first; the emailed WooCommerce order-pay link remains available. */
function yasofish_storefront_quote_before_checkout() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-pay' ) ) || ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) ) {
		return;
	}
	if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
		wc_add_notice( __( 'Antes de pagar, solicita y acepta la cotización de despacho por WhatsApp. YASOFISH te enviará un enlace de pago con el total final.', 'yasofish-storefront' ), 'notice' );
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}
}
add_action( 'template_redirect', 'yasofish_storefront_quote_before_checkout', 20 );

function yasofish_storefront_replace_cart_checkout_button() {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
		add_action( 'woocommerce_proceed_to_checkout', 'yasofish_storefront_cart_quote_button', 20 );
	}
}
add_action( 'wp', 'yasofish_storefront_replace_cart_checkout_button' );

function yasofish_storefront_product_enquiry() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$message = sprintf( __( 'Hola YASOFISH, quisiera consultar por %1$s (%2$s): %3$s', 'yasofish-storefront' ), $product->get_name(), $product->get_sku() ? $product->get_sku() : 'ID ' . $product->get_id(), get_permalink( $product->get_id() ) );
	yasofish_storefront_whatsapp_button( $message );
}
add_action( 'woocommerce_single_product_summary', 'yasofish_storefront_product_enquiry', 35 );

function yasofish_storefront_footer_contact() {
	yasofish_storefront_whatsapp_button( __( 'Hola YASOFISH, necesito asesoría para mi próxima salida de pesca.', 'yasofish-storefront' ) );
}
add_action( 'storefront_footer', 'yasofish_storefront_footer_contact', 5 );
add_action( 'storefront_footer', 'yasofish_storefront_footer_details', 10 );

function yasofish_storefront_product_disclaimer() {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		echo '<p class="yf-store-disclaimer">' . esc_html__( 'Precios en pesos chilenos (CLP). Impuestos, despacho y disponibilidad se informan durante la compra según la configuración de la tienda.', 'yasofish-storefront' ) . '</p>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'yasofish_storefront_product_disclaimer', 8 );

/**
 * Keep product recommendations tied to the current fishing category.
 * Store categories should remain focused on fishing gear (rods, reels, lures and accessories).
 */
function yasofish_storefront_related_products( $related_ids, $product_id, $args ) {
	$product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
	if ( is_wp_error( $product_categories ) || empty( $product_categories ) ) {
		return array();
	}

	return array_values( array_filter( $related_ids, function ( $related_id ) use ( $product_categories ) {
		$related_categories = wp_get_post_terms( $related_id, 'product_cat', array( 'fields' => 'ids' ) );
		return ! is_wp_error( $related_categories ) && ! empty( array_intersect( $product_categories, $related_categories ) );
	} ) );
}
add_filter( 'woocommerce_related_products', 'yasofish_storefront_related_products', 10, 3 );

function yasofish_storefront_related_products_args( $args ) {
	$args['posts_per_page'] = 8;
	$args['columns'] = 5;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'yasofish_storefront_related_products_args' );

function yasofish_storefront_related_products_heading() {
	return __( 'Productos recomendados de pesca', 'yasofish-storefront' );
}
add_filter( 'woocommerce_product_related_products_heading', 'yasofish_storefront_related_products_heading' );
function yasofish_storefront_footer_novelty_socials() {
	$profiles = array(
		array( 'Instagram', 'https://www.instagram.com/yasofish.chile/', '<svg class="yf-brand-icon yf-instagram-mark" viewBox="0 0 448 512" aria-hidden="true"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9S160.5 370.8 224.1 370.8 339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.2 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.5 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.9-26.9 26.9s-26.9-12-26.9-26.9 12-26.9 26.9-26.9 26.9 12 26.9 26.9zm76.1 27.2c-1.7-35.9-9.9-67.8-36.2-94.1-26.3-26.3-58.2-34.5-94.1-36.2-37.1-2.1-148.2-2.1-185.3 0-35.8 1.7-67.7 9.9-94 36.2S2.5 107.1.8 143c-2.1 37.1-2.1 148.2 0 185.3 1.7 35.9 9.9 67.8 36.2 94.1 26.3 26.3 58.2 34.5 94.1 36.2 37.1 2.1 148.2 2.1 185.3 0 35.9-1.7 67.8-9.9 94.1-36.2 26.3-26.3 34.5-58.2 36.2-94.1 2.1-37.1 2.1-148.2 0-185.3zM398.8 388c-7.8 19.6-23 34.8-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.6 2.6-132.1-9c-19.6-7.8-34.8-23-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.6 9-132.1c7.8-19.6 23-34.8 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.6-2.6 132.1 9c19.6 7.8 34.8 23 42.6 42.6 11.7 19.6 9 99.5 9 132.1s2.6 102.6-9 132.1z"/></svg>' ),
		array( 'Facebook', 'https://www.facebook.com/share/1K7tk5ff9G/', '<svg class="yf-brand-icon yf-facebook-mark" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.4 21v-8.3h2.8l.4-3.2h-3.2V7.4c0-.9.3-1.5 1.6-1.5h1.7V3.1c-.8-.1-1.7-.2-2.5-.2-2.5 0-4.2 1.5-4.2 4.3v2.4H7.2v3.2H10V21h3.4Z"/></svg>' ),
		array( 'TikTok', 'https://www.tiktok.com/@yasofishchile', '<svg class="yf-brand-icon yf-tiktok-mark" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.73 4.17 1.1 1.11 2.66 1.62 4.17 1.79v4.03c-1.42-.05-2.84-.34-4.17-.95-.58-.25-1.13-.56-1.66-.91-.01 2.77.01 5.54-.02 8.3-.07 1.33-.51 2.66-1.28 3.74-1.25 1.79-3.42 2.95-5.62 2.99-1.35.08-2.7-.29-3.85-.99-1.91-1.14-3.18-3.26-3.22-5.49-.03-1.14.16-2.3.68-3.32.82-1.67 2.31-2.99 4.09-3.57 1.06-.36 2.18-.35 3.29-.16.01 1.48-.01 2.97.02 4.45-.99-.32-2.08-.23-3.01.25-.68.35-1.2.99-1.47 1.71-.23.55-.17 1.18-.06 1.76.25.99 1.1 1.84 2.1 2.08.6.16 1.26.12 1.84-.13.8-.32 1.45-.99 1.76-1.79.1-.35.14-.71.14-1.07.01-4.28-.01-8.56 0-12.84.01-1.1 0-2.2.01-3.3Z"/></svg>' ),
	);
	echo '<p class="yf-footer-social-label">' . esc_html__( 'Síguenos en redes', 'yasofish-storefront' ) . '</p><nav class="yf-footer-novelty-social" aria-label="' . esc_attr__( 'Redes sociales YASOFISH Chile', 'yasofish-storefront' ) . '">';
	foreach ( $profiles as $profile ) {
		$profile[1] = get_theme_mod( 'yasofish_' . strtolower( $profile[0] ), '' );
		if ( ! $profile[1] ) { continue; }
		printf( '<a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s" title="%3$s">%4$s<span>%3$s</span></a>', esc_url( $profile[1] ), esc_attr( sprintf( __( 'YASOFISH Chile en %s', 'yasofish-storefront' ), $profile[0] ) ), esc_html( $profile[0] ), $profile[2] );
	}
	echo '</nav>';
}

function yasofish_storefront_footer_details() {
	echo '<div class="yf-footer-details"><div class="yf-footer-top">';
	printf( '<div class="yf-footer-brand"><a href="%1$s"><img src="%2$s" alt="%3$s" width="86" height="86"><span><strong>YASOFISH CHILE</strong><small>ARTÍCULOS DE PESCA</small></span></a><p>%4$s</p></div>', esc_url( home_url( '/' ) ), esc_url( get_stylesheet_directory_uri() . '/assets/images/yasofish-logo.png' ), esc_attr__( 'YASOFISH Chile', 'yasofish-storefront' ), esc_html__( 'Equipo para tu próxima salida. De pescador a pescador, de norte a sur.', 'yasofish-storefront' ) );
	echo '</div>';
	echo '<div class="yf-footer-columns"><div><h3>' . esc_html__( 'Pesca', 'yasofish-storefront' ) . '</h3>';
	foreach ( array( 'canas' => 'Cañas', 'carretes' => 'Carretes', 'senuelos' => 'Señuelos', 'accesorios' => 'Accesorios' ) as $slug => $label ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( get_term_link( $term ) ), esc_html( $label ) );
		}
	}
	echo '</div><div><h3>' . esc_html__( 'Atención al cliente', 'yasofish-storefront' ) . '</h3>';
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		printf( '<a href="%1$s">%2$s</a>', esc_url( wc_get_page_permalink( 'myaccount' ) ), esc_html__( 'Mi cuenta / Crear cuenta', 'yasofish-storefront' ) );
	}
	yasofish_storefront_whatsapp_button( __( 'Hola YASOFISH, necesito ayuda con mi compra.', 'yasofish-storefront' ), __( 'Consultar por WhatsApp ↗', 'yasofish-storefront' ) );
	echo '<p>' . esc_html__( 'Stock, costo y plazo se confirman antes de pagar.', 'yasofish-storefront' ) . '</p></div><div><h3>' . esc_html__( 'Ubicación', 'yasofish-storefront' ) . '</h3>';
	$location = get_theme_mod( 'yasofish_store_location', '' );
	echo '<p class="yf-location">' . esc_html( $location ) . '</p>';
	printf( '<p class="yf-hours">%s</p>', esc_html( get_theme_mod( 'yasofish_store_hours', '' ) ) );
	printf( '<p class="yf-pickup">%s</p>', esc_html( get_theme_mod( 'yasofish_pickup_point', '' ) ) );
	printf( '<p class="yf-delivery">%s</p>', esc_html( get_theme_mod( 'yasofish_delivery_hours', '' ) ) );
	$phone = preg_replace( '/[^0-9]/', '', (string) get_theme_mod( 'yasofish_phone', '' ) );
	if ( $phone ) {
		printf( '<a class="yf-phone-link" href="tel:+%1$s">%2$s</a>', esc_attr( $phone ), esc_html__( 'Llamar a +', 'yasofish-storefront' ) . esc_html( $phone ) );
	}
	$email = sanitize_email( (string) get_theme_mod( 'yasofish_email', '' ) );
	if ( $email ) {
		printf( '<a class="yf-email-link" href="%1$s">%2$s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) );
	}
	echo '</div><div><h3>' . esc_html__( 'Novedades de pesca', 'yasofish-storefront' ) . '</h3><p>' . esc_html__( 'Conoce nuestras novedades y comparte tu próxima salida de pesca.', 'yasofish-storefront' ) . '</p>';
	yasofish_storefront_footer_novelty_socials();
	echo '</div></div>';
	$privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
	$terms_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'terms' ) : 0;
	$terms_url = $terms_page_id > 0 ? get_permalink( $terms_page_id ) : '';
	if ( $privacy_url || $terms_url ) {
		echo '<nav class="yf-footer-legal" aria-label="' . esc_attr__( 'Información legal', 'yasofish-storefront' ) . '">';
		if ( $privacy_url ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( $privacy_url ), esc_html__( 'Política de privacidad', 'yasofish-storefront' ) );
		}
		if ( $terms_url ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( $terms_url ), esc_html__( 'Términos y condiciones', 'yasofish-storefront' ) );
		}
		echo '</nav>';
	}
	echo '<div class="yf-footer-bottom"><span>' . esc_html__( 'YASOFISH Chile · De pescador a pescador.', 'yasofish-storefront' ) . '</span></div></div>';
}

function yasofish_storefront_header_setup() {
    remove_action( 'storefront_header', 'storefront_site_branding', 20 );
    add_action( 'storefront_header', 'yasofish_storefront_branding', 20 );
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
}
add_action( 'after_setup_theme', 'yasofish_storefront_header_setup', 100 );
function yasofish_storefront_layout() {
    if ( is_front_page() ) {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
        remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
    }
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
}
add_action( 'wp', 'yasofish_storefront_layout' );
add_action( 'get_header', 'yasofish_storefront_layout' );
function yasofish_storefront_branding() {
    $logo = get_theme_mod( 'custom_logo' );
    $url = $logo ? wp_get_attachment_image_url( $logo, 'thumbnail' ) : get_theme_file_uri( 'assets/images/yasofish-logo.png' );
    printf( '<div class="site-branding yf-header-brand"><a href="%s" rel="home"><img src="%s" width="72" height="72" alt="%s"><span>YASOFISH <small>CHILE · ARTÍCULOS DE PESCA</small></span></a></div>', esc_url( home_url( '/' ) ), esc_url( $url ), esc_attr( get_bloginfo( 'name' ) ) );
}

/** Server-side guard also covers direct classic checkout and Store API requests. */
function yasofish_storefront_require_quote( $data, $errors ) {
    $errors->add( 'yasofish_quote_required', __( 'Coordina el total por WhatsApp y utiliza el enlace de pago del pedido confirmado.', 'yasofish-storefront' ) );
}
add_action( 'woocommerce_after_checkout_validation', 'yasofish_storefront_require_quote', 10, 2 );
function yasofish_storefront_guard_store_api( $result, $server, $request ) {
    if ( 'POST' === $request->get_method() && preg_match( '#^/wc/store/v[0-9]+/checkout/?$#', $request->get_route() ) ) {
        return new WP_Error( 'yasofish_quote_required', __( 'Solicita la cotización antes de pagar.', 'yasofish-storefront' ), array( 'status' => 403 ) );
    }
    return $result;
}
add_filter( 'rest_pre_dispatch', 'yasofish_storefront_guard_store_api', 10, 3 );
function yasofish_storefront_bacs_only( $gateways ) {
    return isset( $gateways['bacs'] ) ? array( 'bacs' => $gateways['bacs'] ) : array();
}
add_filter( 'woocommerce_available_payment_gateways', 'yasofish_storefront_bacs_only', 100 );

/** Only replace WordPress's automatic page list; assigned menus remain editable. */
function yasofish_storefront_menu_args( $args ) {
    if ( isset( $args['theme_location'] ) && in_array( $args['theme_location'], array( 'primary', 'handheld' ), true ) ) {
        $args['fallback_cb'] = 'yasofish_storefront_menu_fallback';
    }
    return $args;
}
add_filter( 'wp_nav_menu_args', 'yasofish_storefront_menu_args' );
function yasofish_storefront_menu_fallback( $args ) {
    $container_class = isset( $args['container_class'] ) ? $args['container_class'] : 'primary-navigation';
    echo '<div class="' . esc_attr( $container_class ) . '"><ul class="menu">';
    printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Inicio', 'yasofish-storefront' ) );
    if ( function_exists( 'wc_get_page_permalink' ) ) {
        foreach ( array( 'shop' => 'Catálogo', 'cart' => 'Carrito', 'myaccount' => 'Mi cuenta' ) as $page => $label ) {
            printf( '<li><a href="%s">%s</a></li>', esc_url( wc_get_page_permalink( $page ) ), esc_html( $label ) );
        }
    }
    echo '</ul></div>';
}
