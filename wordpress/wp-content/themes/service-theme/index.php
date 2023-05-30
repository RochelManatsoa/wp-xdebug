<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package servicer
 */

get_header();
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
<?php
	$blog_post_style = get_query_var( 'blog_style');
	if(!$blog_post_style){
		$blog_post_style  = servicer_get_options('blog_post_style');
	}
	if( $blog_post_style == 2 ) :
		$blog_post_style_name = 'grid';
	else :
		$blog_post_style_name = 'standard';
	endif;
	get_template_part('template-parts/blog-layout/blog-'. $blog_post_style_name );
get_footer();