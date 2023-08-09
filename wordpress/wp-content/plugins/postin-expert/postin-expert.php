<?php
/*
Plugin Name: PostIn Expert
Description: Plugin qui affiche les experts dans Olona PostIn Experts
Version: 1.0.3
Author: Nirina Rochel
Author Uri: https://rochel-nirina.welovedevs.com/
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('OPE', plugin_dir_path(__FILE__));
define('OPE_ENV', 'http://postin-expert.com/');

require_once(OPE . "/inc/functions.php");


/**
 * Shortcode to display default jquery steps
 *
 * @param [type] $attrs
 * @return void
 */
function validExperts($attrs)
{
	extract(shortcode_atts( array(
		'filePath'  => 'templates/experts.php',
		'index'     => 'templates/index.php',
		'single'    => 'templates/single.php',
	), $attrs ));
	
	ob_start();
    if (!isset($_GET['username'])){
        $experts = getAllExperts();
        include(OPE.$filePath);
    } else {
        if(!isset($_GET['slug'])){
            $expert = getAiExpert($_GET['username']);
            include(OPE.$single);
        }else{
            include(OPE.$index);
        }
    }

	$content = ob_get_clean();
	
	return $content;
}

add_shortcode('valid_experts', 'validExperts');