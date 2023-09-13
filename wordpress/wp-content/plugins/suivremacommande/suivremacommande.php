<?php
/*
Plugin Name: Suivre Ma Commande 
Description: Plugin qui ajoute des balises alt sur tout les images, corrige les liens rompus dans le contenu, affiche le popup flottant sur mobile, ajoute des police de caractère
Version: 4.0.3
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

function yoast_edit_title_items($title)
{
    return processTitle($title);
}

function popup_after_title_in_mobile($content)
{
    $html = new simple_html_dom();
    $html->load($content);

    // Traitement des liens et des images
    processLinks($html);
    processImages($html);

    // Ajout de contenu pour les posts uniques
    if (is_single() && $GLOBALS['post']->ID == get_the_ID()) {
        $custom_content = addCustomContent(get_the_ID(), $html);
        return $custom_content;
    }

    return $html;
}

function processLinks($html)
{
    $links = $html->find('a');
    if (is_iterable($links)) {
        foreach ($links as $link) {
            modify_single_link($link);
        }
    }
}

function modify_single_link($link)
{
    setAnchorTextFromDomain($link);
    if (strpos($link->href, '@')) {
        handleEmail($link);
    } elseif (strpos($link->href, '0890211833')) {
        handlePhoneNumber($link, SITE_NUMBER);
    } else {
        handleHttps($link);
    }
    // echo '<pre>';
    // echo var_dump($link->innertext);
    // echo '</pre>';
}

function processImages($html)
{
    $images = $html->find('img');
    if (is_single()) {
        if (isset($images[0]->src)) {
            $src = $images[0]->src;
            if (preg_match('/(suivremacommande0890211833-remedia|NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR|popup-flottante)/', $src)) {
                $images[0]->setAttribute('class', 'd-none d-sm-block');
            }
        }
    }

    if (is_iterable($images)) {
        foreach ($images as $image) {
            modify_single_image($image);
        }
    }
}

// Fonction séparée pour la logique de modification d'une seule image
function modify_single_image($value)
{
    $replacementSrc = 'https://i0.wp.com/suivremacommande.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-SUIVRE-MA-COMMANDE-FR-et-SUIVRE-MON-COLIS-FR.jpg';

    // Vérifier les motifs dans l'attribut src
    if (preg_match('~suivremacommande0890211833|2022/09/suivremacommandeFR|2023/06/SUIVRE-MA-COMMANDE-GENERIQUE|2023/01/0893033341|2023/01/cartouche~', $value->src)) {
        $value->src = $replacementSrc;
        $srcset_value = $value->srcset;
        $srcset_parts = explode(', ', $srcset_value);
        $new_srcset_parts = [];

        foreach ($srcset_parts as $part) {
            $url_and_width = explode(' ', $part);
            if (count($url_and_width) == 2) {
                $url = $url_and_width[0];
                $width = $url_and_width[1];
                if (preg_match('~suivremacommande0890211833|2022/09/suivremacommandeFR|2023/06/SUIVRE-MA-COMMANDE-GENERIQUE|2023/01/0893033341|2023/01/cartouche~', $url)) {
                    $url = $replacementSrc;
                }
                $new_srcset_parts[] = $url . ' ' . $width;
            }
        }

        $new_srcset_value = implode(', ', $new_srcset_parts);
        $value->setAttribute('srcset', $new_srcset_value);
    }

    if (isset($value->alt) && empty($value->alt)) {
        $value->alt = 'Image dans ' . SITE_NAME;
    }
}

function processTitle($title_tag)
{
    if ($title_tag) {
        // Vérifiez la longueur du titre
        $title_length = strlen($title_tag);
        if ($title_length > 70) { // Supposons que la longueur maximale du titre soit 60 caractères
            // Tronquer ou remplacer le texte du titre
            $title_tag = substr($title_tag, 0, 67) . '...'; // Tronquer à 57 caractères et ajouter "..."
        }
    }
    
    return $title_tag;
}

function addCustomContent($post_id, $html)
{
    $custom_content = '';

    // Featured Images
    $second_featured_image_meta = get_post_meta($post_id, 'second_featured_image', true);
    if (metadata_exists('post', $post_id, 'second_featured_image') && $second_featured_image_meta !== "") {
        $second_featured_image = wp_get_attachment_image($second_featured_image_meta, 'full');
    }

    $third_featured_image_meta = get_post_meta($post_id, 'third_featured_image', true);
    if (metadata_exists('post', $post_id, 'third_featured_image') && $third_featured_image_meta !== "") {
        $third_featured_image = wp_get_attachment_image($third_featured_image_meta, 'full');
    }

    // Number Click to Call
    $number_click_to_call_meta = get_post_meta($post_id, 'number_click_to_call', true);
    if (metadata_exists('post', $post_id, 'number_click_to_call') && $number_click_to_call_meta === "1") {
        $number_click_to_call = $number_click_to_call_meta;
    }

    // Mobile Image Top
    $activer_image_mobile_en_haut_meta = get_post_meta($post_id, 'activer_image_mobile_en_haut', true);
    if (metadata_exists('post', $post_id, 'activer_image_mobile_en_haut') && $activer_image_mobile_en_haut_meta === "1") {
        $activer_image_mobile_en_haut = $activer_image_mobile_en_haut_meta;
    }

    // Mobile Image Bottom
    $activer_image_mobile_en_bas_meta = get_post_meta($post_id, 'activer_image_mobile_en_bas', true);
    if (metadata_exists('post', $post_id, 'activer_image_mobile_en_bas') && $activer_image_mobile_en_bas_meta !== "") {
        $activer_image_mobile_en_bas = $activer_image_mobile_en_bas_meta;
    }

    // Figer le popup mobile
    $activer_image_mobile_en_haut = "1";
    $number_click_to_call = SITE_NUMBER;
    $activer_image_mobile_en_bas = "0";
    $second_featured_image = wp_get_attachment_image(17578, "medium");

    if ($second_featured_image !== "" && $activer_image_mobile_en_haut === "1") {
        $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white shadow">';
        $custom_content .= '<div class="textwidget-slide">';
        $custom_content .= '<figure class="wp-block-image">';
        $custom_content .= '<a href="tel:' . $number_click_to_call . '">';
        $custom_content .= $second_featured_image;
        $custom_content .= '</a>';
        $custom_content .= '</figure>';
        $custom_content .= '</div>';
        $custom_content .= '</div>';
    }

    if ($third_featured_image !== "" && $activer_image_mobile_en_bas === "1") {
        $custom_content .= '<div class="container-fluid fixed-bottom d-block d-sm-none text-center align-center ">';
        $custom_content .= '<figure>';
        $custom_content .= '<a href="tel:' . $number_click_to_call . '">';
        $custom_content .= $third_featured_image;
        $custom_content .= '</a>';
        $custom_content .= '</figure>';
        $custom_content .= '</div>';
    }

    return $custom_content .= $html;
}

// Fonctions d'aide pour gérer les liens
function handleEmail(&$link)
{
    if (substr($link->href, 0, 7) !== "mailto:") {
        $link->href = 'mailto:' . $link->href;
    }
}

function handlePhoneNumber(&$link, $siteNumber)
{
    $link->href = 'tel:' . $siteNumber;
}

function handleHttps(&$link)
{
    if (substr($link->href, 0, 3) === "www") {
        $link->href = 'https://' . $link->href;
    } elseif (substr($link->href, 0, 5) === "http:") {
        $link->href = substr($link->href, 0, 4) . 's' . substr($link->href, 4);
    }
}

function setAnchorTextFromDomain(&$link)
{
    if (trim($link->innertext) === trim($link->href)) {
        $url = $link->href;
        $parsed_url = parse_url($url);
        $link->innertext = $parsed_url['host'] ?? 'Lien';
    }
}
