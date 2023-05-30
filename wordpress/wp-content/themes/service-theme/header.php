<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package servicer
 */
	$preloader_on_off = servicer_get_options('preloader_on_off');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php 
		wp_body_open();
		do_action('servicer_preloader_ready');
		do_action('servicer_skip_link_ready');
		get_template_part('template-parts/components/header/header-top-bar');
		get_template_part('template-parts/components/header/header');
		do_action('service_theme_breadcrumb_ready');
		the_header_image_tag();
	?>
