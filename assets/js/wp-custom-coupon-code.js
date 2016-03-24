(function($) {
    var tpd_form = $('form.tpd_checkout_coupon');
    var tpd_coupon_code = "Discount 10%";
    $('input[name="tpd_apply_coupon"]').click(function() {
        if (tpd_form.is(".processing")) return !1;
        tpd_form.addClass("processing").block({
            message: null,
            overlayCSS: {
                background: "#fff",
                opacity: 0.6
            }
        });
        var tpd_data = {
            security: wc_checkout_params.apply_coupon_nonce,
            coupon_code: tpd_coupon_code
        };
        return $.ajax({
            type: "POST",
            url: wc_checkout_params.wc_ajax_url.toString().replace("%%endpoint%%", "apply_coupon"),
            data: tpd_data,
            success: function(c) {
                $(".woocommerce-error, .woocommerce-message").remove(), tpd_form.removeClass("processing").unblock(), c && (tpd_form.before(c), tpd_form.slideUp(), $(document.body).trigger("update_checkout", {
                    update_shipping_method: !1
                }));
            },
            dataType: "html"
        }), !1;
    });

    $('.tpd_showcoupon').click(function() {

        return $(".tpd_checkout_coupon").slideToggle(400, function() {
            $(".tpd_checkout_coupon").find(":input:eq(0)").focus();
        }), !1;

    });

    $('a[name="tpd_apply_coupon_cart"]').click(function() {

        if ($('input.input-text-cart').val()) {
            $('#coupon_code_cart').val(tpd_coupon_code);
            $('#coupon_code_button').click();
        }

    });


})(jQuery);