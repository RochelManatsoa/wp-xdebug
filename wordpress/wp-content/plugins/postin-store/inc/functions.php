<?php

function register_script()
{
    wp_register_script('pfm_js', plugins_url('../js/main.js', __FILE__), [], '2.1.1');
    wp_register_style('pfm_css', plugins_url('../css/style.css', __FILE__), false, '2.1.1', 'all');
}

function enqueue_style()
{
    wp_enqueue_script('bootstrap_jquery_js', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js');
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_script('pfm_js');
    wp_enqueue_style('pfm_css');
}

function custom_content_before_product_summary()
{
    // Ajoutez votre contenu personnalisé ici
    global $product;

    $link = $product->get_product_url();
    $text = $product->get_name();

    $html = '';

    $html .= '<form class="cart" action="' . $link . '" method="get">';
    $html .= '<button type="submit" class="single_add_to_cart_button button alt wp-element-button et_pb_custom_button_icon et_pb_button" data-icon="" data-icon-tablet="" data-icon-phone="">Visit ' . $text . '</button>';
    $html .= '</form>';

    echo $html;
}

function woocommerce_after_shop_loop_item_title()
{
    $html = '';

    if (metadata_exists('post', get_the_ID(), 'slogan') && get_post_meta(get_the_ID(), 'slogan', true) !== "0") {
        $slogan = get_post_meta(get_the_ID(), 'slogan', true);
    } else {
        $slogan = '';
    }

    $html .= $slogan;

    echo $html;
}

add_action('init', 'register_script');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title');
add_action('woocommerce_after_shop_loop_item', 'custom_content_before_product_summary');
