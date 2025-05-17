<?php
/**
 * Payment scripts and styles
 *
 * @package WordPress
 * @subpackage inheart
 */

function enqueue_payment_scripts() {
    wp_enqueue_style('payment-styles', get_template_directory_uri() . '/static/css/pages/payment.min.css');
    wp_enqueue_style('succesfull-payment', get_template_directory_uri() . '/static/css/pages/succesfull-payment.min.css');
    wp_enqueue_script('nova-poshta-script', get_template_directory_uri() . '/static/js/nova-poshta/nova-poshta.min.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'enqueue_payment_scripts'); 