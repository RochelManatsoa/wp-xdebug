<?php
/*
Plugin Name: Annuaire Téléphonique Français - comment-joindre.fr
Description: Plugin qui ajoute deux images flottante sur la version mobile du site (requiert les plugins: myStickyMenu, Advanced Custom Fiels). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 3.7.2
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "commentjoindre.fr");
define('SITE_NUMBER', "0890211805");

require_once NRD_PATH . '/inc/functions.php';
require_once NRD_PATH . '/inc/simple_html_dom.php';

function popup_after_title_in_mobile($content)
{
    if (is_single() || is_archive()) {
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
                    if (
                        filter_var($value->href, FILTER_VALIDATE_URL) === false
                    ) {
                        $value->href = '#';
                    } elseif (substr($value->href, 0, 3) === 'www') {
                        $value->href = 'https://' . $value->href;
                    } elseif (substr($value->href, 0, 5) === 'http:') {
                        $value->href = substr($value->href, 0, 4) .'s'.substr($value->href, 4);
                    }
                }
            }
        }
        if (is_array($img) || is_object($img)) {
            // hide featured image in mobile view
            if (isset($img[0]->src)) {
                $img[0]->setAttribute('class', 'd-none d-sm-block');
            }

            foreach ($img as $value) {
                if (strpos($value->src, '2021/11/COMMENT-CONTACTER')) {
                    $value->src ='https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg';
                    if(isset($value->srcset)){
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, -4), 'https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE', $value->srcset));
                    }
                }

                if (strpos($value->src, 'bouton_APPELER_COMMENT-JOINDRE')) {
                    $value->src = 'https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg';
                    $value->setAttribute('class', 'd-none d-sm-block');
                    if(isset($value->srcset)){
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, -4), 'https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE', $value->srcset));
                    }
                }

                if (
                    strpos($value->src, 'bouton_APPELER_COMMENT-JOINDRE') ||
                    strpos($value->src, 'image')
                ) {
                    $value->setAttribute('class', 'd-none d-sm-block');
                }
                // check alt attr defined
                if (
                    $value->alt === null ||
                    !isset($value->alt) ||
                    $value->alt == ''
                ) {
                    $value->alt = SITE_NAME;
                }
            }
        }

        if (is_single() && !empty($GLOBALS['post'])) {

            $categories = get_the_category(get_the_ID());

            $custom_content = '';
            $backlinks = '';            
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
            $second_featured_image = '<img decoding="async" width="616" height="680" src="https://sp-ao.shortpixel.ai/client/to_auto,q_glossy,ret_img,w_616,h_680/https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg" data-src="https://sp-ao.shortpixel.ai/client/to_auto,q_glossy,ret_img,w_616,h_680/https://comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg" alt="call service" class="wp-image-134047 lazyloaded" data-srcset="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?w=616&amp;ssl=1 616w, https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=272%2C300&amp;ssl=1 272w" data-sizes="(max-width: 616px) 100vw, 616px" sizes="(max-width: 616px) 100vw, 616px" srcset="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?w=616&amp;ssl=1 616w, https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=272%2C300&amp;ssl=1 272w">';

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

            foreach ($categories as $cd) {
                if ($cd->category_nicename == 'gare') {
                    $activer_image_mobile_en_haut = "0";
                    $activer_image_mobile_en_bas = "0";
                    $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                    $backlinks .= '<div class="good-deal">';
                        $backlinks .= 'Liens utiles:';
                        $backlinks .= ' <a href="https://www.thetrainline.com/fr" title="Tous vos billets de train et de bus, au même endroit" target=_blank>Trainline</a>';
                        $backlinks .= ' | <a href="https://garedefrance.fr" title="Annuaire des GARES SNCF en France" target=_blank>Gare de France</a>';
                        $backlinks .= ' | <a href="https://garebelgique.be" title="Annuaire des GARES en Belgique" target=_blank>Gare de Belgique</a>';
                    $backlinks .= '</div>';
                    $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="47"/>';
                }
                if ($cd->category_nicename == 'gare-sncf') {
                    $activer_image_mobile_en_haut = "0";
                    $activer_image_mobile_en_bas = "0";
                    $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                    $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="47"/>';
                }

            // echo '<pre>';
            // echo var_dump($cd->category_nicename);
            // echo '</pre>';
            }
    
            $custom_content .= $html;

            // links for seo
            $custom_content .= $backlinks;
    
            return $custom_content;

        }

        return $html;
    }

    return $content;
}

add_filter('the_content', 'popup_after_title_in_mobile');

