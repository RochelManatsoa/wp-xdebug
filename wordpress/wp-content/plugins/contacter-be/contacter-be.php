<?php
/*
Plugin Name: contacter.be
Description: Plugin qui affiche deux images flottante sur la version mobile du site (requiert les plugins : myStickyMenu, ACF). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 4.0.7
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

define('SITE_NAME', "contacter.be");
define('SITE_NUMBER', "090488503");
define('SITE_VISUEL_ITEM', 14077);

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

add_action('init', 'register_script');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('wp_head', 'custom_header_metadata');
add_filter('the_content', 'popup_after_title_in_mobile');
add_action('template_include', 'search_template', 99);



