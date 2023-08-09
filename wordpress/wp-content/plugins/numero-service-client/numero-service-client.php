<?php
/*
Plugin Name: Numéro Service Client Belgique AWM
Description: Plugin pour le site numero-serviceclient.be [Requiert le thème twentyseventeen]. Ajoute un popup sur mobile. Résultat de recherche personnalisé.
Version: 2.1.3
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "https://numero-serviceclient.be/");
define('SITE_NUMBER', "090488503");

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

            if(is_link_broken($value->href)){
                $value->href = SITE_NAME;
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
                $value->alt = 'numero service client belgique';
            }
        }
    }

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {
        
        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {

            $custom_content = '';
    
            if(metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta( get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta( get_the_ID(), 'second_featured_image', true),'full');
            }else{
                $second_featured_image = '<img loading="lazy" alt="click to call" width="300" height="248" alt="call service" src="https://numero-serviceclient.be/wp-content/uploads/2023/02/NSC-090488503.jpg">';
            }
            if(metadata_exists('post', get_the_ID(), 'third_featured_image') &&get_post_meta( get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta( get_the_ID(), 'third_featured_image', true),'full');
            }else{
                $third_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded mx-auto d-block" src="https://numero-serviceclient.be/wp-content/uploads/2022/10/nouveau-visuel-belgique.jpg" alt="cartouche" width="300" height="71"/>';
            }

            $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white">';
                $custom_content .= '<div class="textwidget-slide">';
                    $custom_content .= '<figure class="wp-block-image">';
                        $custom_content .= '<a href="tel:'.SITE_NUMBER.'">';
                            $custom_content .= $second_featured_image;
                        $custom_content .= '</a>';
                    $custom_content .= '</figure>';
                $custom_content .= '</div>';     
            $custom_content .= '</div>';
            $custom_content .= '<figure class="fixed-bottom d-block d-sm-none">';
                $custom_content .= '<a href="tel:'.SITE_NUMBER.'">';
                    $custom_content .= $third_featured_image;
                $custom_content .= '</a>';
            $custom_content .= '</figure>';
            $custom_content .= $html;

            return $custom_content;

        }

    }

    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');

/**
 * 
 * Add custom Meta Tag to header. 
 * For discover in google search console
 */
function custom_header_metadata()
{
  echo ' <meta name="robots" content="max-image-preview:large" />';
}
add_action('wp_head', 'custom_header_metadata');
add_filter('the_content', 'popup_after_title_in_mobile');

function is_link_broken($url) {
    $headers = @get_headers($url);

    if(!$headers) return true; // Si on n'obtient pas de headers, considérez que le lien est rompu

    // Liste des codes d'état HTTP à vérifier
    $errorStatuses = array('HTTP/1.1 404 Not Found', 'HTTP/1.1 405 Method Not Allowed', 'HTTP/1.1 503 Service Unavailable', 'HTTP/1.1 500 Internal Server Error');

    if (in_array($headers[0], $errorStatuses)) {
        return true; // Lien rompu
    }
    
    return false; // Lien fonctionne
}

