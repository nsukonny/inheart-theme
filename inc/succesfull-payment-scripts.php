<?php
/**
 * Payment scripts and styles
 *
 * @package WordPress
 * @subpackage inheart
 */


 function enqueue_succesfull_payment_scripts() {
    // wp_enqueue_style('payment-styles', get_template_directory_uri() . '/static/css/payment/payment.css');
}
add_action('wp_enqueue_scripts', 'enqueue_succesfull_payment_scripts'); 