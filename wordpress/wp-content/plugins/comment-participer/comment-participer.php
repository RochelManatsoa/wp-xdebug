<?php
/*
Plugin Name: Comment participer
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 2.2.8
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "comment-participer.fr");
define('SITE_NUMBER', "0890214950");

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

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
            }elseif(strpos($value->href, 'tel:0890211805') === 0){
                $value->href = 'tel:'.SITE_NUMBER;
            }else{
                // Check https
                if(substr($value->href, 0, 3) === "www"){
                    $value->href = 'https://'.$value->href;
                }elseif(substr($value->href, 0, 5) === "http:"){
                    $value->href = substr($value->href, 0, 4).'s'.substr($value->href, 4);
                }
            }
        }
        
    }
    
    if (is_array($img) || is_object($img)){
        foreach ($img as $value) {
            $position = '';
            if (
                strpos($value->src, '2023/03/visuel-') ||
                strpos($value->src, '2022/11/0890211805')
            ) {
                $value->src ='https://i0.wp.com/comment-participer.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950-1.jpg';
                if(isset($value->srcset)){
                    $position = strpos($value->srcset, '.jpg');
                    if($position){
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, $position), 'https://i0.wp.com/comment-participer.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950-1', $value->srcset));
                    }else{
                        $value->srcset = null;
                    }
                    
                }
            }

            // check alt attr defined
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
            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_bas') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true) !== "") {
                $activer_image_mobile_en_bas = get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true);
            }

            $activer_image_mobile_en_haut = "1";
            $activer_image_mobile_en_bas = "0";
            $second_featured_image = '<img decoding="async" loading="lazy" width="616" height="680" src="https://comment-participer.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950-1.jpg" alt="call service" class="wp-image-2074" srcset="https://comment-participer.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950-1.jpg 616w, https://comment-participer.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950-1-272x300.jpg 272w" sizes="(max-width: 616px) 100vw, 616px">';

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