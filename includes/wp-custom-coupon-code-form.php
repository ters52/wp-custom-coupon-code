<?php
   /**
    * Checkout TPD coupon form
    *
    *
    * 
    * @author  Stepan Trofimov
    * @package TPD Coupon/Includes
    * @version 1.0
    */
   
   if ( ! defined( 'ABSPATH' ) ) {
   	exit; // Exit if accessed directly
   }
   if ( ! wc_coupons_enabled() ) {
   	return;
   }
   if ( ! WC()->cart->applied_coupons ) {
       $info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Do you want to get a 10% discount ?', 'woocommerce' ) . ' <a href="#" class="tpd_showcoupon">' . __( 'Click here to enter any numbers', 'woocommerce' ) . '</a>' );
       wc_print_notice( $info_message, 'notice' );
   }

if( is_checkout() ){ 
  echo  do_action('woocommerce_settings_get_option');


  ?>

<form class="tpd_checkout_coupon" method="post" style="display:none">
   <p class="form-row form-row-first">
      <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Any code', 'woocommerce' ); ?>" id="tpd_coupon_code" value="" />
   </p>
   <p class="form-row form-row-last">
      <input type="submit" class="button" name="tpd_apply_coupon" value="<?php esc_attr_e( 'Apply Discount', 'woocommerce' ); ?>" />
      <?php do_action( 'woocommerce_cart_coupon' ); ?>
   </p>
   <div class="clear"></div>
</form>

<?php }elseif (is_cart()) { ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" style="display:none">
   <div class="coupon">
      <input type="text" name="coupon_code" class="input-text" id="coupon_code_cart" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" id="coupon_code_button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" />
      <?php do_action( 'woocommerce_cart_coupon' ); ?>
   </div>
</form>
<form class="tpd_checkout_coupon" method="post" style="display:none">
   <p class="form-row form-row-first">
      <input type="text" name="coupon_code" class="input-text-cart" placeholder="<?php esc_attr_e( 'Any code', 'woocommerce' ); ?>" id="tpd_coupon_code" value="" />
   </p>
   <p class="form-row form-row-last">
      <a class="button" name="tpd_apply_coupon_cart" value="<?php esc_attr_e( 'Apply Discount', 'woocommerce' ); ?>">Apply Coupon</a>
   </p>
   <div class="clear"></div>
</form>

<?php } ?>