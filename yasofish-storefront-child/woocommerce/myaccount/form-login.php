<?php
/** Original account cards with WooCommerce's native login/registration processing. @version 9.9.0 */
defined('ABSPATH') || exit;
do_action('woocommerce_before_customer_login_form');
$registration='yes'===get_option('woocommerce_enable_myaccount_registration');
?>
<div class="accountgrid">
<form class="accountcard woocommerce-form woocommerce-form-login login" method="post">
<div class="accountnumber">01 · YA SOY CLIENTE</div><h2>Iniciar sesión</h2><p>Entra a tus pedidos, direcciones y preferencias.</p>
<?php do_action('woocommerce_login_form_start'); ?>
<label for="username">Usuario o correo electrónico<input type="text" class="woocommerce-Input input-text" name="username" id="username" autocomplete="username" required value="<?php echo isset($_POST['username'])&&is_string($_POST['username'])?esc_attr(wp_unslash($_POST['username'])):''; ?>"></label>
<label for="password">Contraseña<input type="password" class="woocommerce-Input input-text" name="password" id="password" autocomplete="current-password" required></label>
<?php do_action('woocommerce_login_form');wp_nonce_field('woocommerce-login','woocommerce-login-nonce'); ?>
<button type="submit" class="btn woocommerce-button woocommerce-form-login__submit" name="login" value="1">Iniciar sesión</button>
<a class="accountsmall" href="<?php echo esc_url(wp_lostpassword_url()); ?>">¿Olvidaste tu contraseña?</a>
<?php do_action('woocommerce_login_form_end'); ?>
</form>
<article class="accountcard signup"><div class="accountnumber">02 · PRIMERA VEZ</div><h2>Crear cuenta</h2>
<?php if($registration): ?>
<form method="post" class="woocommerce-form woocommerce-form-register register">
<?php do_action('woocommerce_register_form_start');if('no'===get_option('woocommerce_registration_generate_username')): ?><label for="reg_username">Usuario<input type="text" name="username" id="reg_username" autocomplete="username" required></label><?php endif; ?>
<label for="reg_email">Correo electrónico<input type="email" name="email" id="reg_email" autocomplete="email" required></label>
<?php if('no'===get_option('woocommerce_registration_generate_password')): ?><label for="reg_password">Contraseña<input type="password" name="password" id="reg_password" autocomplete="new-password" required></label><?php endif; ?>
<?php do_action('woocommerce_register_form');wc_registration_privacy_policy_text();wp_nonce_field('woocommerce-register','woocommerce-register-nonce'); ?>
<button type="submit" class="btn lime" name="register" value="1">Crear cuenta gratis ↗</button><?php do_action('woocommerce_register_form_end'); ?>
</form>
<?php else: ?>
<p>Regístrate para tener tus compras en un solo lugar.</p><ul><li>Consulta el estado de tus pedidos</li><li>Guarda tus direcciones de despacho</li></ul>
<button type="button" class="btn lime" disabled aria-describedby="yf-registration-state">Crear cuenta gratis ↗</button><p id="yf-registration-state" class="accountsmall">El registro de clientes todavía no está habilitado.</p>
<?php endif; ?>
</article></div>
<?php do_action('woocommerce_after_customer_login_form'); ?>
