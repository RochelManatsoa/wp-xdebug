<?php
/*
Plugin Name: Comment Faire Une Réclamation
Description: Plugin qui affiche un popup flottante sur la version mobile du site (requiert le plugin myStickyMenu), corrige les liens externes et mail générant un 404 error, ajoute des balises alt sur tout les images et filtre les urls dans le sitemap
Version: 3.0.7
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "comment-faire-une-reclamation.fr");
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
            if(strpos($value->href, 'tel:0890211805') === 0){
                $value->href = 'tel:'.SITE_NUMBER;
            }else{
                if(substr($value->href, 0, 3) === "www"){
                    $value->href = 'https://'.$value->href;
                }elseif(substr($value->href, 0, 5) === "http:"){
                    $value->href = substr($value->href, 0, 4).'s'.substr($value->href, 4);
                }
            }
        }        
    }

    if (is_array($img) && $img !== [] || is_object($img)){
        if (is_single()) {
            $img[0]->setAttribute('class', 'd-none d-sm-block');
        }
        foreach ($img as $value) {
            $position = '';
            if(isset($value->alt) && empty($value->alt)){
                $value->alt = 'Image dans '.SITE_NAME;
            }
            if (
                strpos($value->src, '/2023/03/VISUEL-') || 
                strpos($value->src, '/2020/04/bouton') ||
                strpos($value->src, '/2022/03/bouton') ||
                strpos($value->src, '/2023/04/faire') ||
                strpos($value->src, '/2023/03/CFUR')
            ) {
                $value->src ='https://i0.wp.com/comment-faire-une-reclamation.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-CFUR.jpg';
                if(isset($value->srcset)){
                    $position = strpos($value->srcset, '.jpg');
                    if($position){
                        $value->setAttribute('srcset', str_replace(substr($value->srcset, 0, $position), 'https://i0.wp.com/comment-faire-une-reclamation.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-CFUR', $value->srcset));
                    }else{
                        $value->srcset = null;
                    }
                }
            }
        }
        
    }

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {

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
            $second_featured_image = '<img decoding="async" loading="lazy" width="616" height="680" src="https://comment-faire-une-reclamation.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-CFUR.jpg" alt="call service" class="wp-image-5170" srcset="https://i0.wp.com/comment-faire-une-reclamation.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-CFUR.jpg?w=616&amp;ssl=1 616w, https://i0.wp.com/comment-faire-une-reclamation.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-CFUR.jpg?resize=272%2C300&amp;ssl=1 272w" sizes="(max-width: 616px) 100vw, 616px">';

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

/**
 * Alters the URL structure for post_status
 *
 * @param string  $url  The URL to modify.
 * @param WP_Post $post The post object.
 *
 * @return string The modified URL.
 */

function sitemap_post_url( $url, $post ) {
    if ( $post->post_status === 'page' ) {
        return \str_replace( 'page', 'guests', $url );
    }

    return $url;
}

add_filter( 'wpseo_xml_sitemap_post_url', 'sitemap_post_url', 10, 2 );

