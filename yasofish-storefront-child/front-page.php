<?php
/** Original mockup composition backed exclusively by live WooCommerce data. */
defined( 'ABSPATH' ) || exit;
get_header();
$shop = yasofish_visual_shop_url();
$title = get_theme_mod( 'yasofish_hero_title', '¡Que pique la aventura!' );
$standard_title = '¡Que pique la aventura!' === $title;
$categories = array( 'canas' => array( 'Cañas', 'rod' ), 'carretes' => array( 'Carretes', 'reel' ), 'senuelos' => array( 'Señuelos', 'lure' ), 'accesorios' => array( 'Accesorios', 'bag' ) );
?>
<main id="primary" class="site-main yf-home-content">
<section class="hero" aria-labelledby="yf-hero-title">
    <div class="herocopy">
        <div class="eyebrow">EQUÍPATE CON YASOFISH CHILE</div>
        <h1 id="yf-hero-title"><?php if ( $standard_title ) : ?>¡Que pique<br><em>la aventura!</em><?php else : echo esc_html( $title ); endif; ?></h1>
        <p><?php echo esc_html( get_theme_mod( 'yasofish_hero_copy', 'Cañas, carretes y señuelos para tu próxima salida. Explora tu equipo y conversemos de pesca.' ) ); ?></p>
        <p class="sme-note">Somos una pyme chilena que está comenzando. Queremos crecer junto a la comunidad pescadora, con atención directa y cercana.</p>
        <div class="heroactions"><a class="btn lime" href="<?php echo esc_url( $shop ); ?>"><?php echo esc_html( get_theme_mod( 'yasofish_hero_button', 'VER CATÁLOGO' ) ); ?> &nbsp; ↗</a><?php yasofish_visual_whatsapp( 'NECESITO ASESORÍA' ); ?></div>
        <div class="herotrust"><span>PRECIOS EN CLP</span><span>COMPRA O CONSULTA</span><span>PESCA EN CHILE</span></div>
    </div>
    <div class="promo-art" aria-label="Ilustración de equipo de pesca">
        <div class="promotitle">¡ARMA TU<br><span>PRÓXIMA SALIDA!</span></div>
        <div class="promoitems"><div class="promoequipment"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/reel.svg' ) ); ?>" width="360" height="240" alt=""></div><div class="promolure"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/lure.svg' ) ); ?>" width="360" height="240" alt=""></div></div>
        <a class="promoribbon" href="<?php echo esc_url( $shop ); ?>">EXPLORA TU EQUIPO <small>PARA TU PRÓXIMA SALIDA ↗</small></a>
    </div>
</section>
<div class="benefits"><div><b aria-hidden="true">↗</b><span>De norte a sur<small>Coordinemos tu despacho</small></span></div><div><b aria-hidden="true">◉</b><span>Conversemos de pesca<small>Asesoría por WhatsApp</small></span></div><div><b aria-hidden="true">▤</b><span>Tú eliges tu equipo<small>Confirma el total antes de pagar</small></span></div></div>
<section class="section" aria-labelledby="yf-categories-title"><div class="heading"><div><div class="eyebrow">ARMA TU PRÓXIMA SALIDA</div><h2 id="yf-categories-title">Tu equipo. Tu próxima captura.</h2></div><a class="textlink" href="<?php echo esc_url( $shop ); ?>">Ver todo ↗</a></div><div class="categories">
<?php foreach ( $categories as $slug => $data ) : ?>
    <a class="category" href="<?php echo esc_url( yasofish_visual_category_url( $slug, $data[0] ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $data[1] . '.svg' ) ); ?>" width="360" height="240" alt="" loading="lazy"><strong><?php echo esc_html( $data[0] ); ?></strong><span aria-hidden="true">↗</span></a>
<?php endforeach; ?>
</div></section>
<section class="section home-featured" aria-labelledby="yf-featured-title"><div class="heading"><div><div class="eyebrow">SELECCIÓN YASOFISH</div><h2 id="yf-featured-title">Destacados para tu próxima pesca.</h2></div><a class="textlink" href="<?php echo esc_url( $shop ); ?>">Explorar productos ↗</a></div>
<?php if ( function_exists( 'wc_get_products' ) && wc_get_products( array( 'featured' => true, 'status' => 'publish', 'limit' => 1, 'return' => 'ids' ) ) ) : ?>
    <?php echo do_shortcode( '[products limit="4" columns="4" visibility="featured" orderby="menu_order" order="ASC"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce escapes its own product templates. ?>
<?php else : ?>
    <p class="yf-catalog-note">Estamos preparando nuestro catálogo. Consulta disponibilidad con nosotros.</p>
    <div class="products yf-empty-products">
    <?php foreach ( $categories as $slug => $data ) : ?>
        <article class="product yf-empty-product"><a class="productimage" href="<?php echo esc_url( yasofish_visual_category_url( $slug, $data[0] ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $data[1] . '.svg' ) ); ?>" width="360" height="240" alt="" loading="lazy"><span class="badge">CATÁLOGO EN PREPARACIÓN</span></a><small>YASOFISH CHILE</small><h3><?php echo esc_html( $data[0] ); ?></h3><p class="yf-empty-copy">Conversemos sobre tu equipo.</p><a class="yf-card-action" href="<?php echo esc_url( yasofish_visual_category_url( $slug, $data[0] ) ); ?>">Explorar categoría ↗</a></article>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
</section>
<section class="editorial"><div class="art" role="img" aria-label="Ilustración de naturaleza y pesca"></div><div class="copy"><div class="eyebrow">DE PESCADOR A PESCADOR</div><h2>Una buena salida<br>empieza bien equipado.</h2><p>¿Río, lago o costa? Cuéntanos dónde quieres ir y te ayudamos a armar tu equipo.</p><?php yasofish_visual_whatsapp( 'Hablemos de tu próxima salida ↗', 'btn lime' ); ?></div></section>
</main>
<?php get_footer(); ?>
