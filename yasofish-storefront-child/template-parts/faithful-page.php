<?php
/** Shared original layouts, populated from WooCommerce or isolated local references. */
defined('ABSPATH') || exit;
get_header();echo '<main id="primary" class="site-main yf-faithful-content">';wc_print_notices();
$pages=get_option('yasofish_information_pages',array());$refs=yasofish_reference_products();
if(yasofish_reference_mode()&&is_account_page()){
    echo yasofish_faithful_html(yasofish_reference_data()['account']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Original local account demonstration; live mode retains native authentication.
} elseif(is_account_page()){
    echo '<section class="section accountpage"><div class="breadcrumb"><a href="'.esc_url(home_url('/')).'">Inicio</a> / Mi cuenta</div><div class="eyebrow">TU ESPACIO YASOFISH</div><h1>Tu pesca, a tu ritmo.</h1><p class="accountintro">En la tienda podrás revisar tus pedidos y guardar tus datos.</p>';echo do_shortcode('[woocommerce_my_account]');echo '</section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native authenticated endpoints and account processing.
} elseif(yasofish_reference_mode()&&is_cart()&&$refs){
    require __DIR__.'/reference-cart.php';
} elseif(is_cart()){
    echo '<section class="section"><div class="breadcrumb"><a href="'.esc_url(home_url('/')).'">Inicio</a> / Mi carro</div><h1 class="cart-page-title">Tu próxima salida.</h1>';echo do_shortcode('[woocommerce_cart]');echo '</section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native classic cart with nonces and hooks.
} elseif(yasofish_reference_mode()&&(is_page((int)($pages['compra']??0))||is_page((int)($pages['terminos']??0)))){
    echo yasofish_faithful_html(yasofish_reference_data()[is_page((int)($pages['terminos']??0))?'terms':'information']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Original local-only content, never approved policy.
} elseif($refs&&isset($_GET['yf_reference_product'])&&is_scalar($_GET['yf_reference_product'])){
    $id=absint($_GET['yf_reference_product']);$data=yasofish_reference_data();
    if(isset($data['details'][$id])){echo yasofish_faithful_html(str_replace('data-add=','data-yf-reference-add=',$data['details'][$id]));}else{echo '<section class="section noresults">Producto de referencia no encontrado.</section>';} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Immutable reference; cannot resolve to WC inventory.
} elseif(is_product()) {
    while(have_posts()){the_post();$product=wc_get_product(get_the_ID());if(!$product)continue;
        if(post_password_required()){echo get_the_password_form();continue;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native password protection form.
        echo '<div ';wc_product_class('yf-native-product',$product);echo '>';
        echo '<section class="section"><div class="breadcrumb"><a href="'.esc_url(home_url('/')).'">Inicio</a> / <a href="'.esc_url(yasofish_visual_shop_url()).'">Catálogo</a> / '.esc_html($product->get_name()).'</div><div class="detail"><div><div class="productimage">'.$product->get_image('woocommerce_single').'</div></div><div><div class="eyebrow">YASOFISH CHILE</div><h1>'.esc_html($product->get_name()).'</h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce image escaped internally.
        echo apply_filters('woocommerce_short_description',$product->get_short_description()).'<div class="price">'.wp_kses_post($product->get_price_html()).'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native WooCommerce content filters for authorized product content.
        woocommerce_template_single_add_to_cart();
        $message=sprintf('Hola YASOFISH, quisiera consultar por %1$s (%2$s): %3$s',$product->get_name(),$product->get_sku()?$product->get_sku():'ID '.$product->get_id(),$product->get_permalink());$wa=yasofish_storefront_whatsapp_url($message);
        if($wa){printf('<a class="btn outline" href="%s" target="_blank" rel="noopener noreferrer">Consultar este producto por WhatsApp ↗</a>',esc_url($wa));}
        echo '<div class="specs"><div class="specs-heading"><strong>Detalles del producto</strong></div>';echo apply_filters('the_content',$product->get_description());wc_display_product_attributes($product);echo '</div></div></div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Same content pipeline as the native description tab, after password check.
        $related=wc_get_related_products($product->get_id(),7);if($related){echo '<section class="section yf-recommendations"><div class="yf-rec-head"><h2>Productos recomendados</h2></div><div class="products yf-rec-track">';foreach($related as $id){$related_product=wc_get_product($id);if($related_product)yasofish_faithful_card($related_product);}echo '</div></section>';}
        echo '</div>';
    }
} else {
    global $wp_query,$wpdb;
    $get=static function($key,$default=''){return isset($_GET[$key])&&is_scalar($_GET[$key])?sanitize_text_field(wp_unslash($_GET[$key])):$default;};
    $category=is_product_category()?get_queried_object()->slug:sanitize_title($get('product_cat'));$q=$get('s');$sort=$get('orderby','menu_order');$on_sale='1'===$get('yf_on_sale');
    $max_bound=$refs?70000:max(5000,(int)ceil((float)$wpdb->get_var("SELECT MAX(max_price) FROM {$wpdb->prefix}wc_product_meta_lookup")/1000)*1000); // phpcs:ignore WordPress.DB.DirectDatabaseQuery -- Native aggregate, no visitor interpolation.
    $max=isset($_GET['max_price'])?max(0,(float)$get('max_price')):$max_bound;
    if($refs){$slug_names=array('canas'=>'Cañas','carretes'=>'Carretes','senuelos'=>'Señuelos','accesorios'=>'Accesorios');$list=array_values(array_filter($refs,static function($p)use($category,$q,$max,$on_sale,$slug_names){return (!$category||$p['cat']===($slug_names[$category]??''))&&(!$q||false!==mb_stripos($p['name'],$q))&&$p['price']<=$max&&(!$on_sale||!empty($p['old']));}));if('price'===$sort)usort($list,static function($a,$b){return $a['price']<=>$b['price'];});elseif('price-desc'===$sort)usort($list,static function($a,$b){return $b['price']<=>$a['price'];});elseif('title'===$sort)usort($list,static function($a,$b){return strcasecmp(remove_accents($a['name']),remove_accents($b['name']));});$total=count($list);}
    else{$list=array();foreach($wp_query->posts as $post){$item=wc_get_product($post->ID);if($item)$list[]=$item;}$total=(int)$wp_query->found_posts;}
    echo '<div class="catalogtop"><div class="breadcrumb"><a href="'.esc_url(home_url('/')).'">Inicio</a> / Catálogo</div><div class="eyebrow">ENCUENTRA TU EQUIPO</div><h1>Vamos de pesca.</h1><p class="note">'.esc_html(($refs?count($refs):$total).' productos'.($refs?' de demostración · Precios en pesos chilenos (CLP), IVA incluido de forma referencial.':' · Precios en pesos chilenos (CLP).')).'</p></div>';
    echo '<section class="section cataloglayout"><form class="yf-catalog-form" method="get" action="'.esc_url(yasofish_visual_shop_url()).'"><input type="hidden" name="post_type" value="product">';if(isset($_GET['yf_live']))echo '<input type="hidden" name="yf_live" value="1">';
    echo '<aside class="filters"><div class="filtergroup"><h3>Buscar en el catálogo</h3><input id="filterq" type="search" name="s" aria-label="Buscar en catálogo" placeholder="Caña, carrete, señuelo…" value="'.esc_attr($q).'"></div><div class="filtergroup"><h3>Categoría</h3><select id="filtercat" name="product_cat" aria-label="Categoría"><option value="">Todas las categorías</option>';
    $terms=get_terms(array('taxonomy'=>'product_cat','hide_empty'=>false,'exclude'=>array((int)get_option('default_product_cat'))));if(!is_wp_error($terms)){$ordered=array();foreach(array('canas','carretes','senuelos','accesorios') as $slug){foreach($terms as $key=>$term){if($term->slug===$slug){$ordered[]=$term;unset($terms[$key]);}}}$terms=array_merge($ordered,array_values($terms));foreach($terms as $term){printf('<option value="%s" %s>%s</option>',esc_attr($term->slug),selected($category,$term->slug,false),esc_html($term->name));}}
    echo '</select><label><input id="filtersale" name="yf_on_sale" type="checkbox" value="1" '.checked($on_sale,true,false).'> Solo ofertas'.($refs?' demo':'').'</label></div><div class="filtergroup"><h3>Precio máximo</h3><input id="filtermax" name="max_price" type="range" min="'.($refs?5000:0).'" max="'.esc_attr($max_bound).'" step="1000" value="'.esc_attr($max).'" aria-label="Precio máximo"><span id="maxlabel">'.esc_html('$'.number_format($max,0,',','.')).'</span><br><button id="clearfilters" type="button" class="textlink clearfilters" data-yf-reset>Limpiar filtros</button><noscript><button type="submit">Aplicar filtros</button></noscript></div></aside>';
    echo '<div class="yf-catalog-results"><div class="sortbar"><span id="results">'.esc_html($total.' productos'.($refs?' de demostración':'')).'</span><select id="sort" name="orderby" aria-label="Ordenar productos">';foreach(array('menu_order'=>'Orden recomendado','price'=>'Precio: menor a mayor','price-desc'=>'Precio: mayor a menor','title'=>'Nombre: A a Z') as $key=>$label){printf('<option value="%s" %s>%s</option>',esc_attr($key),selected($sort,$key,false),esc_html($label));}echo '</select></div><div class="products" id="grid">';
    foreach($list as $item){yasofish_faithful_card($item);}if(!$list)echo '<p class="noresults">No se encontraron productos. Prueba otra búsqueda o categoría.</p>';echo '</div>';if(!$refs)woocommerce_pagination();echo '</div></form></section>';
}
echo '</main>';get_footer();
