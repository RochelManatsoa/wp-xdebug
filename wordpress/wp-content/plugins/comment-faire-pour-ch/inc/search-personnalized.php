<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); 


/**
 * The template for displaying search results.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<main id="content" class="site-main">

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<h1 class="entry-title">
                RÉSULTATS DE LA RECHERCHE
				<span><?php echo get_search_query(); ?></span>
			</h1>
		</header>
	<?php endif; ?>

	<div class="page-content">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				$post_link = get_permalink();
				?>
				<article class="post">
					<?php
					printf( '<h2 class="%s"><a href="%s">%s</a></h2>', 'entry-title', esc_url( $post_link ), wp_kses_post( get_the_title() ) );
					if ( has_post_thumbnail() ) {
						printf( '<a href="%s">%s</a>', esc_url( $post_link ), get_the_post_thumbnail( $post, 'large' ) );
					}
					the_excerpt();
					?>
				</article>
				<?php
			endwhile;
			?>
		<?php else : ?>
			
            <p>
                Nous ne trouvons pas de résultat à votre recherche dans notre base. Vous pouvez faire appel à un conseiller qui fera votre recherche manuellement et pourra vous mettre en relation au besoin.
            </p>
            <figure class="wp-block-image size-full">
                <a href="tel:<?php echo SITE_NUMBER; ?>">
                    <?php echo wp_get_attachment_image(SITE_VISUEL_ITEM, "full"); ?>
                </a>
            </figure>
		<?php endif; ?>
	</div>

	<?php wp_link_pages(); ?>

	<?php
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) :
		?>
		<nav class="pagination">
			<?php /* Translators: HTML arrow */ ?>
			<div class="nav-previous"><?php next_posts_link( sprintf( __( '%s older', 'hello-elementor' ), '<span class="meta-nav">&larr;</span>' ) ); ?></div>
			<?php /* Translators: HTML arrow */ ?>
			<div class="nav-next"><?php previous_posts_link( sprintf( __( 'newer %s', 'hello-elementor' ), '<span class="meta-nav">&rarr;</span>' ) ); ?></div>
		</nav>
	<?php endif; ?>

</main>
<?php
get_footer();
