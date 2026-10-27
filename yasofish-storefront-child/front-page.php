<?php
/** YASOFISH homepage powered by WooCommerce catalog data. */
defined( 'ABSPATH' ) || exit;
get_header();
$hero_title = get_theme_mod( 'yasofish_hero_title', __( '¡Que pique la aventura!', 'yasofish-storefront' ) );
$hero_copy = get_theme_mod( 'yasofish_hero_copy', __( 'Cañas, carretes y señuelos para tu próxima salida. Explora tu equipo y conversemos de pesca.', 'yasofish-storefront' ) );
$hero_button = get_theme_mod( 'yasofish_hero_button', __( 'Ver catálogo', 'yasofish-storefront' ) );
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$logo_id = get_theme_mod( 'custom_logo' );
$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : get_theme_file_uri( 'assets/images/yasofish-logo.png' );
?>
<main id="primary" class="site-main yf-home-content">
	<section class="yf-hero" aria-labelledby="yf-hero-title">
		<div class="yf-hero__copy">
			<span class="yf-hero__eyebrow"><?php esc_html_e( 'Equípate con YASOFISH Chile', 'yasofish-storefront' ); ?></span>
			<h1 id="yf-hero-title"><?php echo wp_kses( nl2br( esc_html( $hero_title ) ), array( 'br' => array() ) ); ?></h1>
			<p><?php echo esc_html( $hero_copy ); ?></p>
			<p class="yf-sme-note"><?php esc_html_e( 'Somos una pyme chilena que está comenzando. Queremos crecer junto a la comunidad pescadora, con atención directa y cercana.', 'yasofish-storefront' ); ?></p>
			<div class="yf-hero__actions">
				<a class="button" href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $hero_button ); ?> <span aria-hidden="true">↗</span></a>
				<?php yasofish_storefront_whatsapp_button( __( 'Hola YASOFISH, necesito asesoría para mi próxima salida de pesca.', 'yasofish-storefront' ), __( 'Necesito asesoría', 'yasofish-storefront' ) ); ?>
			</div>
		</div>
		<div class="yf-hero__promo">
			<?php if ( $logo_url ) : ?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) . ' · ' . __( 'marca de pesca chilena', 'yasofish-storefront' ) ); ?>" width="230" height="230" fetchpriority="high"><?php endif; ?>
			<p><?php esc_html_e( 'Tu próxima salida empieza aquí', 'yasofish-storefront' ); ?></p>
		</div>
	</section>

	<?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
		<p class="yf-config-note"><?php esc_html_e( 'Estamos preparando nuestro catálogo de pesca. Escríbenos para consultar disponibilidad.', 'yasofish-storefront' ); ?></p>
	<?php else : ?>
		<section aria-labelledby="yf-categories-title">
			<h2 class="yf-section-title" id="yf-categories-title"><?php esc_html_e( 'Encuentra tu equipo', 'yasofish-storefront' ); ?></h2>
			<div class="yf-category-links">
				<?php
				$categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'number' => 4, 'parent' => 0, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
				if ( ! is_wp_error( $categories ) && $categories ) :
					foreach ( $categories as $category ) :
						$category_link = get_term_link( $category );
						if ( ! is_wp_error( $category_link ) ) :
							printf( '<a href="%1$s">%2$s <span aria-hidden="true">↗</span></a>', esc_url( $category_link ), esc_html( $category->name ) );
						endif;
					endforeach;
				else :
					printf( '<a href="%1$s">%2$s ↗</a>', esc_url( $shop_url ), esc_html__( 'Ver todos los productos', 'yasofish-storefront' ) );
				endif;
				?>
			</div>
		</section>
		<section aria-labelledby="yf-featured-title">
			<h2 class="yf-section-title" id="yf-featured-title"><?php esc_html_e( 'Destacados para tu próxima pesca', 'yasofish-storefront' ); ?></h2>
			<?php if ( wc_get_products( array( 'featured' => true, 'status' => 'publish', 'limit' => 1, 'return' => 'ids' ) ) ) : ?>
			<?php echo do_shortcode( '[products limit="4" columns="4" visibility="featured" orderby="menu_order" order="ASC"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce escapes product data. ?>
            <?php else : ?>
                <p class="yf-empty-catalog"><?php esc_html_e( 'Estamos preparando nuestro catálogo. Pronto encontrarás aquí equipo para tu próxima salida.', 'yasofish-storefront' ); ?></p>
            <?php endif; ?>
		</section>
	<?php endif; ?>

	<section class="yf-contact-cta" aria-labelledby="yf-contact-title">
		<h2 class="yf-section-title" id="yf-contact-title"><?php esc_html_e( '¿Vas al río, lago o costa?', 'yasofish-storefront' ); ?></h2>
		<p><?php esc_html_e( 'Cuéntanos dónde quieres pescar y te ayudamos a elegir tu equipo.', 'yasofish-storefront' ); ?></p>
		<?php yasofish_storefront_whatsapp_button( __( 'Hola YASOFISH, cuéntame qué equipo recomiendan para mi próxima salida.', 'yasofish-storefront' ) ); ?>
	</section>
</main>
<?php get_footer(); ?>
