<?php
/*
Plugin Name: suivre-mon-colis.fr
Description: Plugin qui ajoute des balises alt sur tout les images, affiche le popup flottant sur mobile, ajoute des police de caractère
Version: 2.2.5
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/


/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

define('PFM_PATH', plugin_dir_path(__FILE__));

require_once(PFM_PATH . "/inc/functions.php");
require_once(PFM_PATH . "/inc/simple_html_dom.php");

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
                $value->href = 'tel:0893033341';
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
            // check alt attr defined
            if ($value->alt === null || !isset($value->alt) || $value->alt == '') {
                $value->alt = 'suivre-mon-colis.fr';
            }
            if (isset($value->src) && (strpos($value->src, 'keyyo-2'))) {
                $value->src = 'https://suivre-mon-colis.fr/wp-content/uploads/2023/01/cartouche-suivre-mon-colis-FRANCE-Copie.jpg';
                $value->setAttribute('width', '500');
                $value->setAttribute('height', '238');
            }
            if (isset($value->srcset) && strpos($value->srcset, 'keyyo-2')) {
                $value->src = 'https://suivre-mon-colis.fr/wp-content/uploads/2023/01/cartouche-suivre-mon-colis-FRANCE-Copie.jpg';
                $value->setAttribute('srcset', str_replace('2021/03/bouton-appelez-suivremoncolisFR-keyyo-2.jpg', '2023/01/cartouche-suivre-mon-colis-FRANCE-Copie.jpg', $value->srcset));
                $value->setAttribute('width', '500');
                $value->setAttribute('height', '238');
            }
            // echo '<pre>';
            // echo $value->src;
            // echo '</pre>';
        }
    }

    if (is_single() && $GLOBALS['post']->ID == get_the_ID()) {
        $custom_content = '';
        // $custom_content = '
        //         <div class="container-fluid Mobile_W d-none d-block d-sm-none text-center align-center py-3 bg-white shadow">
        //             <div class="textwidget-slide">                    
        //                 <figure class="wp-block-image"><a href="tel:0893033341"><img src="https://suivre-mon-colis.fr/wp-content/uploads/2023/01/VM-suivremoncolisFRANCE-0893033341-.jpg" alt="call service" width="217" height="255"></a></figure>
        //             </div>        
        //         </div>';
        
        $custom_content .= '<div class="container-fluid fixed-bottom d-block d-sm-none text-center align-center py-3 bg-white shadow">';
        $custom_content .= '<figure>';
        $custom_content .= '<a href="tel:0893033341">';
        $custom_content .= '<img class="alignnone size-full ls-is-cached lazyloaded" src="https://suivremacommande.fr/wp-content/uploads/2023/01/0893033341-BOUTON-APPEL-pour-SUIVRE-MA-COMMANDE-FRANCE.jpg" alt="cartouche" width="425" height="202"/>';
        $custom_content .= '</a>';
        $custom_content .= '</figure>';
        $custom_content .= '</div>';
        $custom_content .= $html;

        return $custom_content;

    }

    return $html;
}

add_filter('the_content', 'popup_after_title_in_mobile');
