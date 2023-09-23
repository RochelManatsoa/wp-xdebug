<?php
/*
Plugin Name: Annuaire Téléphonique Français - comment-joindre.fr
Description: Plugin qui ajoute deux images flottante sur la version mobile du site (requiert les plugins: myStickyMenu, Advanced Custom Fiels). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 4.0.1
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "comment-joindre.fr");
define('SITE_NUMBER', "0890211805");

require_once NRD_PATH . '/inc/functions.php';
require_once NRD_PATH . '/inc/simple_html_dom.php';

add_action('init', 'register_script');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('wp_head', 'add_custom_canonical');
add_filter('the_content', 'popup_after_title_in_mobile');
add_filter('wpseo_metadesc', 'yoast_edit_metadesc', 10);
add_filter('wpseo_title', 'yoast_edit_title_items' , 49);
add_action('template_include', 'search_template', 99);

