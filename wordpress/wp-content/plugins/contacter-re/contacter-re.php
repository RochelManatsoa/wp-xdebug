<?php
/*
Plugin Name: contact-telephone.re
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 2.0.5
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "contacter.re");
define('SITE_NUMBER', "0890211833");

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

    $html = new simple_html_dom();
    $html->load($content);
    $link = $html->find('a');
    $img = $html->find('img');

    if (is_array($link) || is_object($link)) {
        foreach ($link as $value) {
            // check if email
            if (strpos($value->href, '@')) {
                if (substr($value->href, 0, 7) !== 'mailto:') {
                    $value->href = 'mailto:' . $value->href;
                }
            } elseif (strpos($value->href, 'tel:') === 0) {
                // remove spaces in phone number
                $value->href = str_replace(' ', '', $value->href);
            } else {
                // Check https
                if (substr($value->href, 0, 3) === 'www') {
                    $value->href = 'https://' . $value->href;
                } elseif (substr($value->href, 0, 5) === 'http:') {
                    $value->href = substr($value->href, 0, 4) .'s'.substr($value->href, 4);
                }
            }
        }
    }

    if (is_array($img) && $img !== [] || is_object($img)) {
        if (is_single()) {
            $img[0]->setAttribute('class', 'd-none d-sm-block');
        }
        foreach ($img as $value) {
            // check alt attr defined
            if ($value->alt === null || !isset($value->alt) || $value->alt == '') {
                $value->alt = SITE_NAME;
            }
        }
    }
    

    if (is_single() && !empty($GLOBALS['post'])) {

        // add popup if single
        if ($GLOBALS['post']->ID == get_the_ID()) {


            $custom_content = '';
            $second_featured_image = '';
            $third_featured_image = '';
            $activer_image_mobile_en_haut = '';
            $activer_image_mobile_en_bas = '';
            $number_click_to_call = SITE_NUMBER;
    
            if (metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta(get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'second_featured_image', true), 'full');
            } 
            if (metadata_exists('post', get_the_ID(), 'third_featured_image') && get_post_meta(get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'third_featured_image', true), 'full');
            }
            if (metadata_exists('post', get_the_ID(), 'number_click_to_call') && get_post_meta(get_the_ID(), 'number_click_to_call', true) !== "") {
                $number_click_to_call = get_post_meta(get_the_ID(), 'number_click_to_call', true);
            } 
            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_haut') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true) !== "") {
                $activer_image_mobile_en_haut = get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true);
            }
            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_bas') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true) !== "") {
                $activer_image_mobile_en_bas = get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true);
            }
            
            $activer_image_mobile_en_haut = "1";
            $activer_image_mobile_en_bas = "0";
            $second_featured_image = '<img decoding="async" loading="lazy" src="https://contacter.re/wp-content/uploads/2023/07/VISUEL-0890211833-POUR-REUNION.jpg" alt="call service" class="wp-image-485" width="398" height="586" srcset="https://contacter.re/wp-content/uploads/2023/07/VISUEL-0890211833-POUR-REUNION.jpg 531w, https://contacter.re/wp-content/uploads/2023/07/VISUEL-0890211833-POUR-REUNION-204x300.jpg 204w" sizes="(max-width: 398px) 100vw, 398px">';
    
            if($second_featured_image !== "" && $activer_image_mobile_en_haut === "1"){
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
    
            if($third_featured_image !== "" && $activer_image_mobile_en_bas === "1"){
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


    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');