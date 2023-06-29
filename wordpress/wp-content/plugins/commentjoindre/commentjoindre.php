<?php
/*
Plugin Name: commentjoindre.fr
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 3.5.9
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('CJG', plugin_dir_path(__FILE__));
define('SITE_NAME', "commentjoindre.fr");
define('SITE_NUMBER', "0890211070");

require_once(CJG . "/inc/functions.php");
require_once(CJG . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {

        $html = new simple_html_dom();
        $html->load($content);
        $img = $html->find('img');
        $link = $html->find('a');

        if (is_array($link) || is_object($link)) {
            foreach ($link as $value) {
                if (strpos($value->href, 'tel:0890211805') === 0) {
                    // remove spaces in phone number
                    $value->href = 'tel:'.SITE_NUMBER;
                }
            }
        }

        if (is_array($img) || is_object($img)){
            if(isset($img[0]->src) && strpos($img[0]->src, 'image')){
                $img[0]->setAttribute('class', 'd-none d-sm-block');
            }
            foreach ($img as $value) {
				$position = '';
                if (
					strpos($value->src, '2023/05/image') || 
					strpos($value->src, '2021/11/image') || 
					strpos($value->src, '2023/04/VISUEL-') || 
					strpos($value->src, '2023/03/VISUEL-') || 
					strpos($value->src, '2023/05/image') || 
					strpos($value->src, '2022/07/assistance-') ||
					strpos($value->src, '2023/05/assistance-par-telephone-service-client')
				) {
                    $value->src ='https://i0.wp.com/commentjoindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENTJOINDRE-V2.jpg';
                    if(isset($value->srcset)){
						$position = strpos($value->srcset, '.jpg');
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, $position), 'https://i0.wp.com/commentjoindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENTJOINDRE-V2', $value->srcset));
                    }
                }
                // check alt attr defined
                if($value->alt === null || !isset($value->alt) || $value->alt == ''){
                    $value->alt = SITE_NAME;
                }
            }            
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
            $second_featured_image = '<img loading="lazy" width="616" height="680" src="https://commentjoindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENTJOINDRE-V2.jpg" alt="call service" class="wp-image-46417" srcset="https://commentjoindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENTJOINDRE-V2.jpg 616w, https://commentjoindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENTJOINDRE-V2-272x300.jpg 272w" sizes="(max-width: 616px) 100vw, 616px">';

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

    return $content;
}

add_filter('the_content', 'popup_after_title_in_mobile');