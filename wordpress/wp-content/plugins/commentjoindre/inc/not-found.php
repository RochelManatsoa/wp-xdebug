<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e('Oops! That page can&rsquo;t be found.', 'twentyseventeen'); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php _e('It looks like nothing was found at this location. Maybe try a search?', 'twentyseventeen'); ?></p>

					<?php get_search_form(); ?>
					<p>Vous pouvez faire appel à un conseiller qui fera votre recherche manuellement et pourra vous mettre en relation au besoin.</p>
					<figure class="wp-block-image size-full">
						<a href="tel:<?= CJG_NUMBER ?>">
							<img width="768" height="372" src="<?= plugins_url('../img/VisuelCTblog.jpeg', __FILE__) ?>" alt="Appeler le service" class="wp-image-68" sizes="(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px">
						</a>
					</figure>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
