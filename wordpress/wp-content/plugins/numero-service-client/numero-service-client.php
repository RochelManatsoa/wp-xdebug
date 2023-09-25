<?php
/*
Plugin Name: Numéro Service Client Belgique AWM
Description: Plugin pour le site numero-serviceclient.be [Requiert le thème twentyseventeen]. Ajoute un popup sur mobile. Résultat de recherche personnalisé.
Version: 3.0.2
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));
define('SITE_NAME', "numero-serviceclient.be");
define('SITE_NUMBER', "090488503");

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

add_action('init', 'register_script');
add_action('wp_head', 'custom_header_metadata');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_filter('the_content', 'popup_after_title_in_mobile');
add_action('template_include', 'search_template', 99);