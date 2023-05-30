<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package servicer
 */
get_header();
	if (is_active_sidebar('sidebar-1')) :
		$servicer_blog_post_list_class = 'col-lg-8';
		$blog_post_sidebar = 'with-sidebar-blog';
	else :
		$servicer_blog_post_list_class = 'col-lg-12';
		$blog_post_sidebar = 'without-sidebar-blog';
	endif;
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="blog-listing blog-single-preview col-lg-8 col-md-8 col-sm-10 m-sm-0 m-2">
                <div class="home-page mt-lg-5 mt-md-4 mt-3">
                    <div class="home-page-content">
                        <h1 class="home-page-post-title">
                            <span class="text-red">SERVICES-CLIENT.BE</span> 
                            -
                            <span class="text-yellow">Assistance généraliste par téléphone disponible 24H/24H et 7J/7.</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<section  id="content-start" class="blog-listing-area-one blog-single-preview-one <?php echo esc_attr( $blog_post_sidebar ); ?>">
		<div class="container">
			<div class="row justify-content-center">
				<div class="<?php echo esc_attr($servicer_blog_post_list_class); ?> col-md-12 col-sm-12">
					<div class="blog-listing blog-single-preview">
						<?php
							if (have_posts()) :
								while (have_posts()) :
									the_post();
									get_template_part('template-parts/single/content', get_post_format());
									if (comments_open() || get_comments_number()) :
										comments_template();
									endif;
								endwhile;
							endif;
						?>
					</div>
				</div>
				<?php if (is_active_sidebar('sidebar-1')) { ?>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<aside class="blog-sidebar-one">
							<?php get_sidebar(); ?>
						</aside>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php
get_footer();