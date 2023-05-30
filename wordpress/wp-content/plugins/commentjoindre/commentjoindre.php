<?php
/*
Plugin Name: commentjoindre.fr
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 3.5.8
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('CJG', plugin_dir_path(__FILE__));
define('CJG_SITENAME', "commentjoindre.fr");
define('CJG_NUMBER', "0890211805");

require_once(CJG . "/inc/functions.php");
require_once(CJG . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {

        $html = new simple_html_dom();
        $html->load($content);
        $img = $html->find('img');

        if (is_array($img) || is_object($img)){
            if(isset($img[0]->src) && strpos($img[0]->src, 'image')){
                $img[0]->setAttribute('class', 'd-none d-sm-block');
            }
            foreach ($img as $value) {
                // check alt attr defined
                if($value->alt === null || !isset($value->alt) || $value->alt == ''){
                    $value->alt = CJG_SITENAME;
                }
            }            
        }

        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {
            
            $custom_content = '';


            if (metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta(get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'second_featured_image', true), 'full');
            } else {
                $second_featured_image = '<img loading="lazy" width="425" height="240" src="'.plugins_url('img/VisuelCTblog.jpeg', __FILE__).'" alt="appeler service" class="wp-image-68" >';
            }
    
            if (metadata_exists('post', get_the_ID(), 'third_featured_image') && get_post_meta(get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'third_featured_image', true), 'full');
            } else {
                $third_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded" src="'.plugins_url('img/cartouche.png', __FILE__).'" alt="cartouche" width="425" height="112"/>';
            }
    
    
            if (metadata_exists('post', get_the_ID(), 'number_click_to_call') && get_post_meta(get_the_ID(), 'number_click_to_call', true) !== "") {
                $number_click_to_call = get_post_meta(get_the_ID(), 'number_click_to_call', true);
            } else {
                $number_click_to_call = CJG_NUMBER;
            }

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_haut') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true) !== "") {
                $activer_image_mobile_en_haut = get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true);
            }else{
                $activer_image_mobile_en_haut = "0";
            }

            if($activer_image_mobile_en_haut !== "1"){
                $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white shadow">';
                $custom_content .= '<div class="textwidget-slide">';
                $custom_content .= '<figure class="wp-block-image">';
                $custom_content .= '<a href="tel:'.$number_click_to_call.'">';
                $custom_content .= $second_featured_image;
                $custom_content .= '</a>';
                $custom_content .= '</figure>';
                $custom_content .= '</div>';
                $custom_content .= '</div>';
            }

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_bas') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true) !== "") {
                $activer_image_mobile_en_bas = get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true);
            }else{
                $activer_image_mobile_en_bas = "0";
            }

            if($activer_image_mobile_en_bas !== "1"){
                $custom_content .= '<div class="container-fluid fixed-bottom d-block d-sm-none text-center align-center ">';
                $custom_content .= '<figure>';
                $custom_content .= '<a href="tel:'.$number_click_to_call.'">';
                $custom_content .= $third_featured_image;
                $custom_content .= '</a>';
                $custom_content .= '</figure>';
                $custom_content .= '</div>';
            }

            $custom_content .= $html;

            return $custom_content;

        }

    }

    return $content;
}

add_filter('the_content', 'popup_after_title_in_mobile');