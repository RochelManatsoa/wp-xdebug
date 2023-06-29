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

get_header(); ?>

<div class="wrap">

    <header class="page-header">
        <?php if (have_posts()) : ?>
            <h1 class="page-title">
                <?php
                /* translators: Search query. */
                printf(__('Search Results for: %s', 'twentyseventeen'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        <?php else : ?>
            <h1 class="page-title">RÉSULTATS DE LA RECHERCHE</h1>
        <?php endif; ?>
    </header><!-- .page-header -->

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (have_posts()) :
                // Start the Loop.
                while (have_posts()) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('template-parts/post/content', 'excerpt');

                endwhile; // End the loop.

                the_posts_pagination(
                    array(
                        'prev_text'          => twentyseventeen_get_svg(array('icon' => 'arrow-left')) . '<span class="screen-reader-text">' . __('Previous page', 'twentyseventeen') . '</span>',
                        'next_text'          => '<span class="screen-reader-text">' . __('Next page', 'twentyseventeen') . '</span>' . twentyseventeen_get_svg(array('icon' => 'arrow-right')),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'twentyseventeen') . ' </span>',
                    )
                );

            else :
            ?>

                <p class="">
                    Nous ne trouvons pas de résultat à votre recherche dans notre base. Vous pouvez faire appel à un conseiller qui fera votre recherche manuellement et pourra vous mettre en relation au besoin.
                </p>
                <figure class="wp-block-image size-full">
                    <a href="tel:<?= SITE_NUMBER ?>">
                        <img width="768" height="372" src="https://garedefrance.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-0890214950.jpg" alt="Appeler le service" class="wp-image-68" srcset="https://garedefrance.fr/wp-content/uploads/2022/11/0890211805-par-REMMEDIA-pour-GDF.jpg 768w, https://garedefrance.fr/wp-content/uploads/2022/11/0890211805-par-REMMEDIA-pour-GDF-300x145.jpg 300w" sizes="(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px">
                    </a>
                </figure>
            <?php
            //    get_search_form();

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();
