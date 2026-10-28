<?php
/** Classic WooCommerce cart actions and hooks in the original row composition. @version 11.2.0 */
defined('ABSPATH') || exit;
do_action('woocommerce_before_cart');
?>
<div class="cartlayout">
<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
<?php do_action('woocommerce_before_cart_table');do_action('woocommerce_before_cart_contents'); ?>
<?php foreach(WC()->cart->get_cart() as $cart_item_key=>$cart_item):
    $_product=apply_filters('woocommerce_cart_item_product',$cart_item['data'],$cart_item,$cart_item_key);
    $product_id=apply_filters('woocommerce_cart_item_product_id',$cart_item['product_id'],$cart_item,$cart_item_key);
    if(!$_product||!$_product->exists()||$cart_item['quantity']<=0||!apply_filters('woocommerce_cart_item_visible',true,$cart_item,$cart_item_key))continue;
    $permalink=apply_filters('woocommerce_cart_item_permalink',$_product->is_visible()?$_product->get_permalink($cart_item):'',$cart_item,$cart_item_key);
?>
<div class="cartrow woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class','cart_item',$cart_item,$cart_item_key)); ?>">
    <div><?php echo apply_filters('woocommerce_cart_item_thumbnail',$_product->get_image('woocommerce_thumbnail'),$cart_item,$cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native WooCommerce image/filter. ?></div>
    <div><a href="<?php echo esc_url($permalink); ?>"><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name',$_product->get_name(),$cart_item,$cart_item_key)); ?></a><?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native formatted variations. ?><p class="note"><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_price',WC()->cart->get_product_price($_product),$cart_item,$cart_item_key)); ?> c/u</p><?php do_action('woocommerce_after_cart_item_name',$cart_item,$cart_item_key);if($_product->backorders_require_notification()&&$_product->is_on_backorder($cart_item['quantity']))echo '<p class="backorder_notification">'.esc_html__('Available on backorder','woocommerce').'</p>'; ?></div>
    <div><?php $quantity=$_product->is_sold_individually()?'<input type="hidden" name="cart['.esc_attr($cart_item_key).'][qty]" value="1">1':woocommerce_quantity_input(array('input_name'=>'cart['.$cart_item_key.'][qty]','input_value'=>$cart_item['quantity'],'max_value'=>$_product->get_max_purchase_quantity(),'min_value'=>0,'product_name'=>$_product->get_name()),$_product,false);echo apply_filters('woocommerce_cart_item_quantity',$quantity,$cart_item_key,$cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native quantity control. ?></div>
    <strong><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_subtotal',WC()->cart->get_product_subtotal($_product,$cart_item['quantity']),$cart_item,$cart_item_key)); ?></strong>
    <?php echo apply_filters('woocommerce_cart_item_remove_link',sprintf('<a role="button" class="remove" href="%s" aria-label="%s" data-product_id="%d" data-product_sku="%s">×</a>',esc_url(wc_get_cart_remove_url($cart_item_key)),esc_attr(sprintf(__('Remove %s from cart','woocommerce'),$_product->get_name())),absint($product_id),esc_attr($_product->get_sku())),$cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Native filter and escaped link. ?>
</div>
<?php endforeach;do_action('woocommerce_cart_contents'); ?>
<div class="yf-native-cart-actions">
<?php if(wc_coupons_enabled()): ?><label for="coupon_code"><?php esc_html_e('Coupon:','woocommerce'); ?></label><input type="text" name="coupon_code" id="coupon_code" value=""><button type="submit" class="btn outline" name="apply_coupon" value="<?php esc_attr_e('Apply coupon','woocommerce'); ?>"><?php esc_html_e('Apply coupon','woocommerce'); ?></button><?php do_action('woocommerce_cart_coupon');endif; ?>
<button type="submit" class="btn" name="update_cart" value="<?php esc_attr_e('Update cart','woocommerce'); ?>"><?php esc_html_e('Update cart','woocommerce'); ?></button>
<?php do_action('woocommerce_cart_actions');wp_nonce_field('woocommerce-cart','woocommerce-cart-nonce'); ?>
</div>
<?php do_action('woocommerce_after_cart_contents');do_action('woocommerce_after_cart_table'); ?>
</form>
<?php do_action('woocommerce_before_cart_collaterals'); ?>
<div class="cart-collaterals summary"><?php do_action('woocommerce_cart_collaterals'); ?></div>
</div>
<?php do_action('woocommerce_after_cart'); ?>
