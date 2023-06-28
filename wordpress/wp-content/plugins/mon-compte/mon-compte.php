<?php
/*
Plugin Name: Mon Compte - AWM
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 2.2.2
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "www.moncompte.org");
define('SITE_NUMBER', "0890211833");

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

    $html = new simple_html_dom();
    $html->load($content);
    $link = $html->find('a');
    $img = $html->find('img');

    if (is_array($link) || is_object($link)){
        foreach ($link as $value) {
            if(substr($value->href, 0, 3) === "www"){
                $value->href = 'https://'.$value->href;
            }elseif(substr($value->href, 0, 5) === "http:"){
                $value->href = substr($value->href, 0, 4).'s'.substr($value->href, 4);
            }
            // echo '<pre>'; 
            // echo $value->href;
            // echo '</pre>';
        }        
    }

    if (is_array($img) || is_object($img)){
        foreach ($img as $value) {
            if(empty($value->alt)){
                $value->alt = 'Image dans '.SITE_NAME;
            }
        }        
    }

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {

        // hide first figure in mobile view
        $default = $html->find('img', 0);
        if(isset($default)){
            $default->setAttribute('class', 'd-none d-sm-block');
        }

        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {  
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

            if (metadata_exists('post', get_the_ID(), 'number_click_to_call') && get_post_meta(get_the_ID(), 'number_click_to_call', true) === "1") {
                $number_click_to_call = get_post_meta(get_the_ID(), 'number_click_to_call', true);
            } 

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_haut') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true) === "1") {
                $activer_image_mobile_en_haut = get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true);
            }

            $activer_image_mobile_en_haut = "1";
            $activer_image_mobile_en_bas = "0";
            $second_featured_image = '<img width="290" height="300" src="https://moncompte.org/wp-content/uploads/2023/06/VISUEL-POPUP-MOBILE-ET-BUREAU-DU-SITE-MON-COMPTE-290x300.jpg" class="image wp-image-2016  attachment-medium size-medium" alt="call service" decoding="async" loading="lazy" style="max-width: 100%; height: auto;" srcset="https://moncompte.org/wp-content/uploads/2023/06/VISUEL-POPUP-MOBILE-ET-BUREAU-DU-SITE-MON-COMPTE-290x300.jpg 290w, https://moncompte.org/wp-content/uploads/2023/06/VISUEL-POPUP-MOBILE-ET-BUREAU-DU-SITE-MON-COMPTE.jpg 616w" sizes="100vw">';

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

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_bas') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true) !== "") {
                $activer_image_mobile_en_bas = get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true);
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

            $custom_content .= $content;

            return $custom_content;

        }

    }

    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');