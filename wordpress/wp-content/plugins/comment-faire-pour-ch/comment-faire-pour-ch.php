<?php
/*
Plugin Name: Comment Faire Pour CH
Description: Plugin qui affiche une image flottante sur la version mobile du site (requiert les plugins : myStickyMenu). Ajoute le framework CSS twitter Bootstrap. Ce plugin affiche egalement des polices de google fonts.
Version: 1.0.1
Author: Nirina Rochel
Author URI: https://welovedevs.com/app/fr/developer/rochel-la-ou-se-trouve-une-volonte-il-existe-un-chemin
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

define('SITE_NAME', "comment-faire-pour.ch");
define('SITE_NUMBER', "0901113300");
// define('SITE_VISUEL_ITEM', 48);
define('SITE_VISUEL_ITEM', 82);

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

add_action('init', 'register_script');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('wp_head', 'custom_header_metadata');
add_filter('the_content', 'popup_after_title_in_mobile');
add_action('template_include', 'search_template', 99);  