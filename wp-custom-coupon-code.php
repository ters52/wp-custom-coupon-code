<?php
/*
Plugin Name: Ten Percent Discount Coupon
Description: Ten Percent Discount Coupon field on checkout page
Version: 1.0
Author: Stepan Trofimov
Author URI: https://github.com/ters52
Plugin URI: https://github.com/ters52/wp-custom-coupon-code
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('TPD_COUPON_DIR', plugin_dir_path(__FILE__));
define('TPD_COUPON_URL', plugin_dir_url(__FILE__));
register_activation_hook(__FILE__, 'tpd_coupon_activation');
register_deactivation_hook(__FILE__, 'tpd_coupon_deactivation');

/**
 *
 * Activation function. Init creating coupon.
 *
 * @since 1.0
 *
 *
 */

function tpd_coupon_activation()
{
	tpd_create_coupon();
}

/**
 *
 * Deactivation function. Init removing coupon.
 *
 * @since 1.0
 *
 *
 */

function tpd_coupon_deactivation()
{
	tpd_remove_coupon();
}

/**
 *
 * Create special Woocommerce coupon for 10% discount
 *
 * @since 1.0
 *
 *
 */

function tpd_create_coupon()
{
	$coupon_code = tpd_return_coupon_code(); // Code
	if (check_existing_coupon($coupon_code)) {
		return;
	}

	$amount = '10'; 
	$discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product
	$coupon = array(
		'post_title' => $coupon_code,
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type' => 'shop_coupon'
	);
	$new_coupon_id = wp_insert_post($coupon);

	// Add meta

	update_post_meta($new_coupon_id, 'discount_type', $discount_type);
	update_post_meta($new_coupon_id, 'coupon_amount', $amount);
	update_post_meta($new_coupon_id, 'individual_use', 'no');
	update_post_meta($new_coupon_id, 'product_ids', '');
	update_post_meta($new_coupon_id, 'exclude_product_ids', '');
	update_post_meta($new_coupon_id, 'usage_limit', '');
	update_post_meta($new_coupon_id, 'expiry_date', '');
	update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
	update_post_meta($new_coupon_id, 'free_shipping', 'no');
}

/**
 *
 * Remove special Woocommerce coupon for 10% discount
 *
 * @since 1.0
 *
 *
 */

function tpd_remove_coupon()
{
	$coupone_code = tpd_return_coupon_code();
	$coupon_data = new WC_Coupon($coupone_code);
	if (!empty($coupon_data->id)) {
		wp_delete_post($coupon_data->id);
	}
}

/**
 *
 * Set code for special coupon Woocommerce. With changing it's value, should change javascript variable.
 *
 * @since 1.0
 * @return string
 *
 *
 */

function tpd_return_coupon_code()
{
	$code = 'Discount 10%';
	return $code;
}

/**
 *
 * Check does it exist.
 *
 * @since 1.0
 * @return BOOL
 * @param string $code
 *
 */

function check_existing_coupon($code)
{
	$args = array(
		'posts_per_page' => - 1,
		'orderby' => 'title',
		'order' => 'asc',
		'post_type' => 'shop_coupon',
		'post_status' => 'publish',
	);
	$coupons = get_posts($args);
	foreach($coupons as $coupon) {

		// Get the name for each coupon post

		$coupon_name = $coupon->post_title;
		if ($coupon_name === $code) {
			return true;
		}
	}

	return false;
}

add_action('woocommerce_before_checkout_form', 'tpd_checkout_coupon_form', 100);
add_action('woocommerce_before_cart', 'tpd_checkout_coupon_form', 1);
/**
 *
 * Init registration script and style, get template coupon form.
 *
 * @since 1.0
 *
 *
 */

function tpd_checkout_coupon_form()
{
	add_tpd_coupon_checkout_script();
	add_tpd_coupon_checkout_style();
	require_once (TPD_COUPON_DIR . 'includes/wp-custom-coupon-code-form.php');

}

/**
 *
 * Register and enqueue plugin scripts
 *
 * @since 1.0
 *
 *
 */

function add_tpd_coupon_checkout_script()
{
	$tpdScriptUrl = TPD_COUPON_URL . 'assets/js/wp-custom-coupon-code.min.js';
	$tpdScriptFile = TPD_COUPON_DIR . 'assets/js/wp-custom-coupon-code.min.js';
	if (file_exists($tpdScriptFile)) {
		wp_enqueue_script('wp-custom-coupon-code', $tpdScriptUrl, array() , 1.1, true);
	}
}

/**
 *
 * Register and enqueue plugin styles
 *
 * @since 1.0
 *
 *
 */

function add_tpd_coupon_checkout_style()
{
	$tpdStyleUrl = TPD_COUPON_URL . 'assets/css/wp-custom-coupon-code.min.css';
	$tpdStyleFile = TPD_COUPON_DIR . 'assets/css/wp-custom-coupon-code.min.css';
	if (file_exists($tpdStyleFile)) {
		wp_register_style('myStyleSheets', $tpdStyleUrl);
		wp_enqueue_style('myStyleSheets');
	}
}

?>