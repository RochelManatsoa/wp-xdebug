<?php
/*
Plugin Name: suivre-mon-colis.fr
Description: Plugin qui affiche un popup flottante sur la version mobile du site (requiert le plugin myStickyMenu), corrige les liens externes et mail générant un 404 error, ajoute des balises alt sur tout les images et filtre les urls dans le sitemap
Version: 2.2.9
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/


/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

define('PFM_PATH', plugin_dir_path(__FILE__));

require_once(PFM_PATH . "/inc/functions.php");
require_once(PFM_PATH . "/inc/simple_html_dom.php");
define('SITE_NAME', "suivre-mon-colis.fr");
define('SITE_NUMBER', "0890214950");

function popup_after_title_in_mobile($content)
{
    $html = new simple_html_dom();
    $html->load($content);
    $link = $html->find('a');
    $img = $html->find('img');

    if (is_array($link) || is_object($link)) {

        foreach ($link as $value) {
            // check if email
            if (strpos($value->href, '@')) {
                if (substr($value->href, 0, 7) !== "mailto:") {
                    $value->href = 'mailto:' . $value->href;
                }
            } elseif (strpos($value->href, '0891038148') || strpos($value->href, '0893033341')) {
                $value->href = 'tel:'.SITE_NUMBER;
            } else {
                // Check https
                if (substr($value->href, 0, 3) === "www") {
                    $value->href = 'https://' . $value->href;
                } elseif (substr($value->href, 0, 5) === "http:") {
                    $value->href = substr($value->href, 0, 4) . 's' . substr($value->href, 4);
                }
            }
        }
    }

    if (is_array($img) || is_object($img)) {
        if (isset($img[0]->src) && (strpos($img[0]->src, 'image') || strpos($img[0]->src, '0890211805'))) {
            $img[0]->setAttribute('class', 'd-none d-sm-block');
        }
        foreach ($img as $value) {
            $position = '';
            if(isset($value->alt) && empty($value->alt)){
                $value->alt = 'Image dans '.SITE_NAME;
            }
            if (
                strpos($value->src, '/2023/01/cartouche') || 
                strpos($value->src, '2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR') || 
                strpos($value->src, '/2020/04/bouton') ||
                strpos($value->src, '/2022/03/bouton') ||
                strpos($value->src, '/2023/04/faire') ||
                strpos($value->src, '/2023/03/CFUR')
            ) {
                //$value->src ='https://i0.wp.com/suivre-mon-colis.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg';
                $value->src ='https://i0.wp.com/suivre-mon-colis.fr/wp-content/uploads/2023/10/NOUVEAU-VISUEL-0890214950-pourSMC.webp';
                if(isset($value->srcset)){
                    $value->srcset = null;
                    // $position = strpos($value->srcset, '.jpg');
                    // if($position){
                    //     $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, $position), 'https://i0.wp.com/suivre-mon-colis.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR', $value->srcset));
                    // }else{
                    //     $value->srcset = null;
                    // }
                }
            }
        }
    }

    if (is_single() && $GLOBALS['post']->ID == get_the_ID()) {

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
    	$second_featured_image = wp_get_attachment_image(2404, "medium");

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

    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');
