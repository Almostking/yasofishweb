<?php
/** Original views, with isolated in-memory reference data and native commerce. */
defined( 'ABSPATH' ) || exit;
function yasofish_reference_mode() {
    return 'local' === wp_get_environment_type() && 'yasofish.local' === wp_parse_url( get_option( 'home' ), PHP_URL_HOST ) && (bool) get_theme_mod( 'yasofish_reference_mode', false ) && ! isset( $_GET['yf_live'] );
}
function yasofish_reference_data() { static $data; if ( null === $data ) { $data = require __DIR__ . '/reference-data.php'; } return $data; }
function yasofish_reference_products() {
    if ( ! yasofish_reference_mode() || wc_get_products( array( 'limit' => 1, 'status' => 'publish', 'return' => 'ids' ) ) || ( WC()->cart && ! WC()->cart->is_empty() ) ) { return array(); }
    return yasofish_reference_data()['products'];
}
function yasofish_faithful_url( $route ) {
    if ( preg_match( '~^#/producto/(\d+)$~', $route, $m ) ) { return add_query_arg( 'yf_reference_product', (int) $m[1], yasofish_visual_shop_url() ); }
    if ( 0 === strpos( $route, '#/catalogo?' ) ) {
        parse_str( substr( $route, strpos( $route, '?' ) + 1 ), $args );
        if ( isset( $args['cat'] ) ) { $slugs = array( 'Cañas' => 'canas', 'Carretes' => 'carretes', 'Señuelos' => 'senuelos', 'Accesorios' => 'accesorios' ); return yasofish_visual_category_url( $slugs[$args['cat']] ?? '', $args['cat'] ); }
        return add_query_arg( 'yf_on_sale', 1, yasofish_visual_shop_url() );
    }
    $map = array( '#/' => home_url('/'), '#/catalogo' => yasofish_visual_shop_url(), '#/carrito' => wc_get_cart_url(), '#/mi-cuenta' => wc_get_page_permalink('myaccount'), '#/informacion' => yasofish_commerce_page_url('compra'), '#/terminos' => yasofish_commerce_page_url('terminos'), '#/pago' => add_query_arg('yf_reference_quote',1,wc_get_cart_url()) );
    return $map[$route] ?? $route;
}
function yasofish_faithful_html( $html ) {
    $html = preg_replace_callback( '/href="(#[^"]*)"/', static function( $m ) { return 'href="' . esc_url( yasofish_faithful_url( html_entity_decode( $m[1], ENT_QUOTES, 'UTF-8' ) ) ) . '"'; }, $html );
    return str_replace( 'src="yasofish-logo.png"', 'src="' . esc_url(get_theme_file_uri('assets/images/yasofish-logo.png')) . '"', $html );
}
function yasofish_faithful_card( $item ) {
    $demo = is_array($item);
    if ( $demo ) { $p=$item; $url=add_query_arg('yf_reference_product',$p['id'],yasofish_visual_shop_url()); }
    else { $terms=wc_get_product_terms($item->get_id(),'product_cat',array('fields'=>'names'));$p=array('id'=>$item->get_id(),'name'=>$item->get_name(),'cat'=>implode(', ',$terms),'price'=>$item->get_price(),'old'=>$item->is_on_sale()?$item->get_regular_price():null);$url=$item->get_permalink(); }
    echo '<article class="product"><a class="productimage" href="'.esc_url($url).'">';
    if($demo){echo $p['art'];}else{echo $item->get_image('woocommerce_thumbnail');} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Immutable source SVG or escaped WooCommerce image.
    echo '<span class="badge '.(!empty($p['old'])?'sale':'').'">'.esc_html($demo?(!empty($p['old'])?'PRECIO DEMO · OFERTA':'ILUSTRACIÓN DEMO'):($item->is_on_sale()?'OFERTA':($item->is_in_stock()?'DISPONIBLE':'AGOTADO'))).'</span></a><small>'.esc_html(mb_strtoupper($p['cat']).' / YASOFISH'.($demo?' DEMO':'')).'</small><a href="'.esc_url($url).'"><h3>'.esc_html($p['name']).'</h3></a><div class="price">';
    if($demo){echo esc_html('$'.number_format($p['price'],0,',','.'));if(!empty($p['old']))echo '<del>'.esc_html('$'.number_format($p['old'],0,',','.')).'</del>';}else{echo wp_kses_post($item->get_price_html());}echo '</div>';
    if($demo){printf('<button type="button" data-yf-reference-add="%d">Agregar al carro <span>＋</span></button>',(int)$p['id']);}
    else {printf('<a class="yf-native-add button %s" href="%s" data-product_id="%d" data-quantity="1" rel="nofollow">%s <span>＋</span></a>',esc_attr($item->is_purchasable()&&$item->is_in_stock()?'add_to_cart_button '.($item->supports('ajax_add_to_cart')?'ajax_add_to_cart':''):''),esc_url($item->add_to_cart_url()),$item->get_id(),esc_html($item->add_to_cart_text()));}
    echo '</article>';
}
add_filter('body_class',static function($classes){$classes[]='yf-faithful';if(yasofish_reference_mode())$classes[]='yf-reference';return $classes;});
add_filter('show_admin_bar',static function($show){return yasofish_reference_mode()?false:$show;});
add_filter('document_title_parts',static function($parts){if(yasofish_reference_mode()){$parts['title'].=' · LocalWP · referencia sin ventas';}return $parts;});
add_action('wp',static function(){if(yasofish_reference_mode()){remove_action('wp_head','print_emoji_detection_script',7);remove_action('wp_enqueue_scripts','wp_enqueue_emoji_styles');remove_action('wp_print_styles','print_emoji_styles');}});
add_filter('wp_robots',static function($robots){if(yasofish_reference_mode()){$robots['noindex']=true;$robots['nofollow']=true;}return $robots;});
add_filter('woocommerce_product_single_add_to_cart_text',static function(){return 'Agregar al carro ＋';});
add_filter('template_include',static function($template){
    $pages=get_option('yasofish_information_pages',array());
    if(is_account_page()||is_cart()||is_shop()||is_product_taxonomy()||is_product()||(is_search()&&get_query_var('post_type')==='product')||(yasofish_reference_mode()&&(is_page((int)($pages['compra']??0))||is_page((int)($pages['terminos']??0))))){return __DIR__.'/faithful-page.php';}
    return $template;
},99);
add_action('wp_enqueue_scripts',static function(){
    wp_dequeue_style('yasofish-visual');wp_dequeue_style('yasofish-commerce');
    wp_enqueue_style('yasofish-faithful',get_theme_file_uri('assets/css/faithful.css'),array('yasofish-storefront-style'),(string)filemtime(__DIR__.'/../assets/css/faithful.css'));
    wp_enqueue_script('yasofish-faithful',get_theme_file_uri('assets/js/faithful.js'),array(),(string)filemtime(__DIR__.'/../assets/js/faithful.js'),true);
    wp_localize_script('yasofish-faithful','yfFaithful',array('reference'=>yasofish_reference_mode(),'products'=>yasofish_reference_products(),'jigColors'=>yasofish_reference_mode()?yasofish_reference_data()['jigColors']:array(),'cart'=>wc_get_cart_url(),'shop'=>yasofish_visual_shop_url(),'whatsapp'=>yasofish_storefront_whatsapp_url('Hola YASOFISH, necesito asesoría.')));
    if(yasofish_reference_mode()&&is_cart()&&WC()->cart->is_empty()){wp_enqueue_script('yasofish-reference-cart',get_theme_file_uri('assets/js/reference-cart.js'),array('yasofish-faithful'),(string)filemtime(__DIR__.'/../assets/js/reference-cart.js'),true);}
},120);
add_filter('wc_get_template',static function($template,$name){if('cart/cart-empty.php'===$name&&yasofish_reference_mode()&&yasofish_reference_products()){return __DIR__.'/reference-cart.php';}return $template;},20,2);
add_action('customize_register',static function($manager){$manager->add_setting('yasofish_reference_mode',array('default'=>false,'sanitize_callback'=>static function($value){return (bool)$value;},'capability'=>'edit_theme_options'));$manager->add_control('yasofish_reference_mode',array('type'=>'checkbox','section'=>'yasofish_storefront','label'=>'Referencia visual completa (solo entorno local y dominio yasofish.local)','description'=>'Ejemplos solo en memoria, nunca inventario ni pagos. Fuera de LocalWP este modo queda deshabilitado.'));});
