<?php
/*
Plugin Name: Contacter aéroport AWM
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 2.2.3
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile($content)
{

    $html = new simple_html_dom();
    $html->load($content);
    $link = $html->find('a');
    $img = $html->find('img');
    
    if (is_array($link) || is_object($link)){

        foreach ($link as $value) {

            // check if email
            if(strpos($value->href, '@')){
                if(substr($value->href, 0, 7) !== "mailto:"){
                    $value->href = 'mailto:'.$value->href;
                }
            }else{

                // Check https
                if(filter_var($value->href, FILTER_VALIDATE_URL) === FALSE){
                    $value->href = '#';
                }elseif(substr($value->href, 0, 3) === "www"){
                    $value->href = 'https://'.$value->href;
                }elseif(substr($value->href, 0, 5) === "http:"){
                    $value->href = substr($value->href, 0, 4).'s'.substr($value->href, 4);
                }
            }
            // echo '<pre>'; 
            // echo $value->href;
            // echo '</pre>';
        }
        
    }
    
    if (is_array($img) || is_object($img)){
        
        // check alt attr defined
        foreach ($img as $value) {
            // check alt attr defined
            if($value->alt === null || !isset($value->alt) || $value->alt == ''){
                $value->alt = 'Image dans contacter-aeroport.fr';
            }
        }   
        
    }

    if (is_single() && !empty($GLOBALS['post'])) {

        // hide first figure in mobile view
        $default = $html->find('img', 0);
        if(isset($default)){
            $default->setAttribute('class', 'd-none d-sm-block');
        }

        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {
            
            $custom_content = '';


            if (metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta(get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'second_featured_image', true), 'full');
            } else {
                $second_featured_image = '<img loading="lazy" width="425" height="240" src="https://contacter-aeroport.com/wp-content/uploads/2022/11/0890211805-par-REMMEDIA-pour-CONTACTER-AEROPORT.jpg" alt="appeler service" class="wp-image-68" >';
            }
    
            if (metadata_exists('post', get_the_ID(), 'third_featured_image') && get_post_meta(get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'third_featured_image', true), 'full');
            } else {
                $third_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded" src="https://contacter-aeroport.com/wp-content/uploads/2022/11/contacteraeroport.png" alt="cartouche" width="425" height="112"/>';
            }
    
    
            if (metadata_exists('post', get_the_ID(), 'number_click_to_call') && get_post_meta(get_the_ID(), 'number_click_to_call', true) !== "") {
                $number_click_to_call = get_post_meta(get_the_ID(), 'number_click_to_call', true);
            } else {
                $number_click_to_call = '0890211805';
            }

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_haut') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true) !== "") {
                $activer_image_mobile_en_haut = get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true);
            }else{
                $activer_image_mobile_en_haut = "0";
            }

            // echo '<pre>'; 
            // echo $second_featured_image;
            // echo '</pre>';

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

    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');
