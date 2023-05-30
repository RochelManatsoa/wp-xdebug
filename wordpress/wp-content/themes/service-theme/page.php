<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package servicer
 */
get_header();
    $no_comment_area_page = '';
    if (!have_comments()) {
        $no_comment_area_page = 'no-comment-area';
    }
?>

    <section  id="content-start" class="without-sidebar-blog blog-listing-area-one blog-single-preview-one page-single-preview-one  <?php echo esc_attr( $no_comment_area_page ); ?>">
        <div class="container">
            <div class="row justify-content-center">
                
                <div class="blog-listing blog-single-preview col-lg-6 col-md-8 col-sm-10 m-sm-0 m-2">
                    <?php do_action('service_theme_search_form', false) ?>
                    <?php 
                        $post = get_post(74);
                    ?>
                    <div class="home-page mt-lg-5 mt-md-4 mt-3">
                        <div class="home-page-content">
                            <h1 class="home-page-post-title">
								<span class="text-red">SERVICES-CLIENT.BE</span> 
								-
								<span class="text-yellow">Assistance généraliste par téléphone disponible 24H/24H et 7J/7.</span>
							</h1>
                                
                            <?= get_the_content(null, false, $post) ?>
                        </div>
                    </div>
                </div>
            </div>
			<div class="row justify-content-center">
                <?php
                    $recents = get_posts(array(
                            'numberposts' => 3
                        ));
                    foreach ($recents as $recent) :
                ?>
                    <div class="blog-listing blog-single-preview col-lg-4">
                        <div class="home-page mt-lg-5 mt-md-4 mt-3">
                            <div class="card">
                                <h3 class="text-yellow"><?= get_the_title( $recent ) ?></h3> 

                                    
                                <div>
                                    
									<?php 
                                        $excerpt = get_the_excerpt( $recent );
                                        $tab = explode('?', $excerpt);
                                        foreach ($tab as $tense) {
											if (strlen($tense)>1){
												echo($tense.'?<br>');
											}
										}
                                     ?>
                                </div>
                                <div class="blog-listing-single-item-read-more">
                                    <a href="<?= get_the_permalink($recent) ?>" class="text-red">
                                        <span>
                                            Lire plus
                                        </span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
get_footer();



