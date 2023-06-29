<?php
/*
Plugin Name: Comment Faire Une Réclamation
Description: Plugin qui affiche un popup flottante sur la version mobile du site (requiert le plugin myStickyMenu), corrige les liens externes et mail générant un 404 error, ajoute des balises alt sur tout les images et filtre les urls dans le sitemap
Version: 3.0.5
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

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
            }elseif(strpos($value->href, 'tel:') === 0){
                $value->href = 'tel:0890211805';
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

    if (is_array($img) && $img !== [] || is_object($img)){
        if (is_single()) {
            $img[0]->setAttribute('class', 'd-none d-sm-block');
        }

        foreach ($img as $value) {
            // echo '<pre>'; 
            // echo $value->src;
            // echo '</pre>';

            // check alt attr defined
            if(isset($value->alt) && empty($value->alt)){
                $value->alt = 'Image dans comment-faire-une-reclamation.fr';
            }

            $default = "comment-faire-une-reclamation.fr/wp-content/uploads/2022/03/bouton_APPELER_CFUR.png";
            $smcImg = "suivremacommande.fr/wp-content/uploads/2020/04/bouton_APPELER_SUIVRE-MA-COMMANE-0893033341.jpg";

            if(strpos($value->src, $default) !== false || strpos($value->src, $smcImg) !== false){
                $value->src = 'https://comment-faire-une-reclamation.fr/wp-content/uploads/2022/07/bouton-appelez-CFUR-remmedia0890211805.jpg';
                if(is_single()){
                    $value->setAttribute('class', 'd-none d-sm-block');
                }
            }

            if(isset($value->srcset) && strpos($value->srcset, $default) !== false){
                $value->src = 'https://comment-faire-une-reclamation.fr/wp-content/uploads/2022/07/bouton-appelez-CFUR-remmedia0890211805.jpg';
                $value->setAttribute('srcset', str_replace('/uploads/2022/03/bouton_APPELER_CFUR.png', '/uploads/2022/07/bouton-appelez-CFUR-remmedia0890211805.jpg', $value->srcset));
                if(is_single()){
                    $value->setAttribute('class', 'd-none d-sm-block');
                }
            }
        }
        
    }

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {

        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {
            
            $custom_content = '';


            if (metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta(get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'second_featured_image', true), 'full');
            } else {
                $second_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded" src="https://comment-faire-une-reclamation.fr/wp-content/uploads/2023/03/CFUR-pour-popup-mobile-HAUT.jpg" alt="call service" width="298" height="257" />';
            }
    
            if (metadata_exists('post', get_the_ID(), 'third_featured_image') && get_post_meta(get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'third_featured_image', true), 'full');
            } else {
                $third_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded" src="https://comment-faire-une-reclamation.fr/wp-content/uploads/2022/10/CFUR.png" alt="cartouche" width="425" height="71"/>';
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

