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

                <p>
                    Nous ne trouvons pas de résultat à votre recherche dans notre base. Vous pouvez faire appel à un conseiller qui fera votre recherche manuellement et pourra vous mettre en relation au besoin.
                </p>
                <figure class="wp-block-image size-full">
                    <a href="tel:<?= SITE_NUMBER ?>">
                    <img decoding="async" width="525" height="580" src="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=525%2C580&amp;ssl=1" data-src="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=525%2C580&amp;ssl=1" alt="commentjoindre.fr" class="d-none d-sm-block lazyloaded" data-srcset="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?w=616&amp;ssl=1 616w, https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=272%2C300&amp;ssl=1 272w" data-sizes="(max-width: 525px) 100vw, 525px" data-recalc-dims="1" sizes="(max-width: 525px) 100vw, 525px" srcset="https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?w=616&amp;ssl=1 616w, https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg?resize=272%2C300&amp;ssl=1 272w">
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
