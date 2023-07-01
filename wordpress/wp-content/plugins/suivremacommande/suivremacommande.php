<?php
/*
Plugin Name: Suivre Ma Commande 
Description: Plugin qui ajoute des balises alt sur tout les images, corrige les liens rompus dans le contenu, affiche le popup flottant sur mobile, ajoute des police de caractère
Version: 3.2.8
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('SMC', plugin_dir_path(__FILE__));

require_once(SMC . "/inc/functions.php");
require_once(SMC . "/inc/simple_html_dom.php");
define('SITE_NAME', "suivremacommande.fr");
define('SITE_NUMBER', "0893033341");

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
            } elseif (strpos($value->href, '0891038148')) {
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
        if (is_single()) {
            if (isset($img[0]->src) && (strpos($img[0]->src, 'suivremacommande0890211833-remedia') || strpos($img[0]->src, 'popup-flottante'))) {
                $img[0]->setAttribute('class', 'd-none d-sm-block');
            }
        }

        foreach ($img as $value) {
            $position = '';
            if(isset($value->alt) && empty($value->alt)){
                $value->alt = 'Image dans '.SITE_NAME;
            }
            if (
                strpos($value->src, '/suivremacommande0890211833') || 
                strpos($value->src, '/2022/09/suivremacommandeFR') ||
                strpos($value->src, '/2023/06/SUIVRE-MA-COMMANDE-GENERIQUE') ||
                strpos($value->src, '/2023/01/0893033341') ||
                strpos($value->src, '/2023/01/cartouche')
            ) {
                $value->src ='https://i0.wp.com/suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg';
                if(isset($value->srcset)){
                    $value->srcset = null;
                    $position = strpos($value->srcset, '.jpg');
                    if($position){
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, $position), 'https://i0.wp.com/suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR', $value->srcset));
                    }else{
                        $value->srcset = null;
                    }
                }
            }
        }
    }

    // add popup if single
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
        $second_featured_image = '<img decoding="async" src="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_462,h_510/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg" data-src="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_462,h_510/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg" alt="call service" class="wp-image-17578 lazyloaded" width="462" height="510" data-srcset="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_616/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg 616w, https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_272/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR-272x300.jpg 272w" data-sizes="(max-width: 462px) 100vw, 462px" sizes="(max-width: 462px) 100vw, 462px" srcset="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_616/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg 616w, https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_272/https://suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR-272x300.jpg 272w">';

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


