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
		'yasofish_announcement' => array( __( 'Avisos rotativos (uno por línea)', 'yasofish-storefront' ), __( "OFERTAS DEMO · Revisa ofertas de artículos de pesca\nNOVEDADES DEMO · Cañas, carretes, señuelos y accesorios\nDESTACADO DEMO · Caña Austral Spinning 2,10 m", 'yasofish-storefront' ), 320 ),
		'yasofish_hero_title' => array( __( 'Título de portada', 'yasofish-storefront' ), __( '¡Que pique la aventura!', 'yasofish-storefront' ), 100 ),
		'yasofish_hero_copy' => array( __( 'Texto de portada', 'yasofish-storefront' ), __( 'Cañas, carretes y señuelos para tu próxima salida. Explora tu equipo y conversemos de pesca.', 'yasofish-storefront' ), 240 ),
		'yasofish_hero_button' => array( __( 'Texto del botón principal', 'yasofish-storefront' ), __( 'Ver catálogo', 'yasofish-storefront' ), 40 ),
		'yasofish_whatsapp' => array( __( 'WhatsApp comercial (solo dígitos con código país)', 'yasofish-storefront' ), '', 20 ),
    'yasofish_store_location' => array( __( 'Dirección de atención o retiro (si aplica)', 'yasofish-storefront' ), '', 180 ),
	);
	foreach ( $settings as $id => $data ) {
		$sanitizer = 'yasofish_whatsapp' === $id ? 'yasofish_storefront_sanitize_whatsapp' : ( 'yasofish_announcement' === $id ? 'yasofish_storefront_sanitize_notices' : 'yasofish_storefront_sanitize_setting' );
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
	} elseif ( current_user_can( 'edit_theme_options' ) ) {
		echo '<p class="yf-config-note">' . esc_html__( 'Agrega el número comercial en Apariencia → Personalizar → YASOFISH Chile para activar WhatsApp.', 'yasofish-storefront' ) . '</p>';
	}
}

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
function yasofish_storefront_footer_details() {
	echo '<div class="yf-footer-details"><div class="yf-footer-top">';
	printf( '<div class="yf-footer-brand"><a href="%1$s"><img src="%2$s" alt="%3$s" width="86" height="86"><span><strong>YASOFISH CHILE</strong><small>ARTÍCULOS DE PESCA</small></span></a><p>%4$s</p></div>', esc_url( home_url( '/' ) ), esc_url( get_stylesheet_directory_uri() . '/assets/images/yasofish-logo.png' ), esc_attr__( 'YASOFISH Chile', 'yasofish-storefront' ), esc_html__( 'Equipo para tu próxima salida. De pescador a pescador, de norte a sur.', 'yasofish-storefront' ) );
	echo '<div class="yf-footer-social"><strong>' . esc_html__( 'Síguenos', 'yasofish-storefront' ) . '</strong><a href="https://www.instagram.com/yasofish.chile/" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Instagram YASOFISH Chile', 'yasofish-storefront' ) . '"><svg class="yf-brand-icon yf-instagram-mark" viewBox="0 0 448 512" aria-hidden="true"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9S160.5 370.8 224.1 370.8 339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.2 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.5 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.9-26.9 26.9s-26.9-12-26.9-26.9 12-26.9 26.9-26.9 26.9 12 26.9 26.9zm76.1 27.2c-1.7-35.9-9.9-67.8-36.2-94.1-26.3-26.3-58.2-34.5-94.1-36.2-37.1-2.1-148.2-2.1-185.3 0-35.8 1.7-67.7 9.9-94 36.2S2.5 107.1.8 143c-2.1 37.1-2.1 148.2 0 185.3 1.7 35.9 9.9 67.8 36.2 94.1 26.3 26.3 58.2 34.5 94.1 36.2 37.1 2.1 148.2 2.1 185.3 0 35.9-1.7 67.8-9.9 94.1-36.2 26.3-26.3 34.5-58.2 36.2-94.1 2.1-37.1 2.1-148.2 0-185.3zM398.8 388c-7.8 19.6-23 34.8-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.6 2.6-132.1-9c-19.6-7.8-34.8-23-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.6 9-132.1c7.8-19.6 23-34.8 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.6-2.6 132.1 9c19.6 7.8 34.8 23 42.6 42.6 11.7 19.6 9 99.5 9 132.1s-2.6 102.6-9 132.1z"/></svg> Instagram ↗</a></div></div>';
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
	echo '<p>' . esc_html__( 'Despachos y retiros: consulta las opciones disponibles.', 'yasofish-storefront' ) . '</p></div><div><h3>' . esc_html__( 'Ubicación', 'yasofish-storefront' ) . '</h3>';
	$location = get_theme_mod( 'yasofish_store_location', '' );
	echo '<p class="yf-location">' . esc_html( $location ? $location : __( 'Dirección comercial pendiente de confirmar.', 'yasofish-storefront' ) ) . '</p></div><div><h3>' . esc_html__( 'Novedades de pesca', 'yasofish-storefront' ) . '</h3><p>' . esc_html__( 'Activa una lista de correo con consentimiento para compartir ofertas y avisos de reposición.', 'yasofish-storefront' ) . '</p><a href="https://www.instagram.com/yasofish.chile/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Ver novedades en Instagram ↗', 'yasofish-storefront' ) . '</a></div></div>';
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
	echo '<div class="yf-footer-payment"><div><h3>' . esc_html__( 'Métodos de pago habilitados', 'yasofish-storefront' ) . '</h3><small>' . esc_html__( 'Solo se muestran los medios activos en WooCommerce.', 'yasofish-storefront' ) . '</small></div><div class="yf-footer-gateways">';
	$available_gateways = array();
	if ( function_exists( 'WC' ) && WC() && WC()->payment_gateways() ) {
		$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
		foreach ( $available_gateways as $gateway ) {
			echo '<span class="yf-gateway"><strong>' . esc_html( $gateway->get_title() ) . '</strong>';
			if ( $gateway->get_icon() ) {
				echo wp_kses_post( $gateway->get_icon() );
			}
			echo '</span>';
		}
	}
	if ( empty( $available_gateways ) ) {
		echo '<span class="yf-no-gateways">' . esc_html__( 'Los medios aparecerán al configurarlos.', 'yasofish-storefront' ) . '</span>';
	}
	echo '</div></div><div class="yf-footer-bottom"><span>' . esc_html__( 'YASOFISH Chile · Equipamiento exclusivamente de pesca.', 'yasofish-storefront' ) . '</span><span>' . esc_html__( 'Revisa las políticas de compra antes de publicar la tienda.', 'yasofish-storefront' ) . '</span></div></div>';
}
