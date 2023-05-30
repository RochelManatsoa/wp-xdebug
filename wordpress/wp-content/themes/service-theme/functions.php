<?php 
function service_theme_enqueue_assets(){
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

  // Déclarer les JS

  wp_enqueue_script( 'service-theme-popup', get_stylesheet_directory_uri() . '/assets/js/service-theme-popup.js', array( 'jquery' ), time(), true );

  // Déclarer les css

  wp_enqueue_style('service-theme-style', get_stylesheet_directory_uri() . '/assets/css/service-theme-style.css', time(), true);

  wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/assets/css/theme-style.css', time(), true);
}

add_action( 'wp_enqueue_scripts', 'service_theme_enqueue_assets' );

/**
  * Font Awesome CDN Setup SVG
  * 
  * This will load Font Awesome 5 from the Font Awesome Free or Pro CDN.
  */
  if (! function_exists('fa_custom_setup_cdn_svg') ) {
	function fa_custom_setup_cdn_svg($cdn_url = '', $integrity = null) {
	  $matches = [];
	  $match_result = preg_match('|/([^/]+?)\.js$|', $cdn_url, $matches);
	  $resource_handle_uniqueness = ($match_result === 1) ? $matches[1] : md5($cdn_url);
	  $resource_handle = "font-awesome-cdn-svg-$resource_handle_uniqueness";
  
	  foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
		add_action(
		  $action,
		  function () use ( $cdn_url, $resource_handle ) {
			wp_enqueue_script( $resource_handle, $cdn_url, [], null );
		  }
		);
	  }
  
	  if($integrity) {
		add_filter(
		  'script_loader_tag',
		  function( $html, $handle ) use ( $resource_handle, $integrity ) {
			if ( in_array( $handle, [ $resource_handle ], true ) ) {
			  return preg_replace(
				'/^<script /',
				'<script integrity="' . $integrity .
				'" defer crossorigin="anonymous"',
				$html,
				1
			  );
			} else {
			  return $html;
			}
		  },
		  10,
		  2
		);
	  }
	}
  }

  fa_custom_setup_cdn_svg(
	'https://use.fontawesome.com/releases/v5.15.4/js/all.js',
	'sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc'
  );

if ( ! defined( 'SERVERNAME' ) ) {
  // if production
	define( 'SERVERNAME', 'https://services-client.be/' );

  // if developement
//   define( 'SERVERNAME', 'http://localhost:4001/' );
}

function popup_appel() {
	?>
	<div class="close-popup d-none" id="close-popup"></div>
	<a href="tel:090488503" class="width-100 button-appel unshow" id="lien-appel">
		<img src="<?= SERVERNAME ?>wp-content/themes/service-theme/assets/images/popup_flottante.jpg" alt="button-call" class="width-100">
	</a>
<?php }
  
  add_action('popupAppel', 'popup_appel');

// comment to commentaire

if (!function_exists('service_theme_comments_count')) :
  function service_theme_comments_count() {
      if (get_comments_number(get_the_ID()) == 0) {
          $comments_count = '<a href="' . esc_url(get_permalink()) . '" >' . get_comments_number(get_the_ID()) . " commentaires" . '</a>';
      }
      elseif (get_comments_number(get_the_ID()) > 1) {
          $comments_count = '<a href="' . esc_url(get_permalink()) . '" >' . get_comments_number(get_the_ID()) . " commentaires" . '</a>';
      } else {
          $comments_count = '<a href="' . esc_url(get_permalink()) . '#comments" >' . get_comments_number(get_the_ID()) . " commentaire" . '</a>';
      }
      echo sprintf(esc_html('%s'), $comments_count);
  }
endif;

// entrer commentaire

if ( ! function_exists( 'service_theme_entry_footer' ) ) :
	function service_theme_entry_footer() {
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'servicer' ) );
			if ( $categories_list ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'servicer' ) . '</span>', $categories_list );
			}
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'servicer' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'servicer' ) . '</span>', $tags_list );
			}
		}
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Laisser un commentaire<span class="screen-reader-text"> on %s</span>', 'servicer' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'servicer' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

function service_theme_search_form(){ ?>
	<form class="service-theme-search-from" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
		<div class="service-theme-form-group">
			<input type="search" id="<?php echo esc_attr(uniqid('search-form-')); ?>" class="form-control form-control-lg"
				placeholder="Taper ICI - la marque que vous recherchez" value="<?php echo get_search_query(); ?>" name="s" required="required"/>
			<button type="submit" class="form-submit">
				<svg version="1.1" id="Magnifying_glass" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
					y="0px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
				<path d="M17.545,15.467l-3.779-3.779c0.57-0.935,0.898-2.035,0.898-3.21c0-3.417-2.961-6.377-6.378-6.377
					C4.869,2.1,2.1,4.87,2.1,8.287c0,3.416,2.961,6.377,6.377,6.377c1.137,0,2.2-0.309,3.115-0.844l3.799,3.801
					c0.372,0.371,0.975,0.371,1.346,0l0.943-0.943C18.051,16.307,17.916,15.838,17.545,15.467z M4.004,8.287
					c0-2.366,1.917-4.283,4.282-4.283c2.366,0,4.474,2.107,4.474,4.474c0,2.365-1.918,4.283-4.283,4.283
					C6.111,12.76,4.004,10.652,4.004,8.287z"/>
				</svg>
			</button>
		</div>
	</form>
<?php }

add_action('service_theme_search_form','service_theme_search_form');

function wpb_custom_new_menu() {
	register_nav_menu('service-theme-custom-menu',__( 'Service-theme Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );


// header menu service-theme
 
function service_theme_header_menu() {
?>
	<div class="header-one-menu-area">
		<div class="header-one-menu" id="site-navigation" >
			<button class="header-one-focus"><?php esc_html_e( 'Primary Menu', 'servicer' ); ?></button>
			
			<?php
				wp_nav_menu( array(
					'theme_location' => 'service-theme-custom-menu',
					'container' => false,
					'menu_class' => 'service-theme-menu',
					'link_before' => '<i class="fas fa-angle-double-right"></i>'
				) );
			?>
		</div>
	</div>
<?php
}

add_action('service_theme_header_menu_ready','service_theme_header_menu');

if (!function_exists('service_theme_comments')) {
    function service_theme_comments($comment, $args, $depth) {
        extract($args, EXTR_SKIP);
        $args['reply_text'] = esc_html__('Répondre', 'servicer');
        $class = '';
        if ($depth > 1) {
            $class = '';
        }
        if ($depth == 1) {
            $child_html_el = '<ul><li>';
            $child_html_end_el = '</li></ul>';
        }

        if ($depth >= 2) {
            $child_html_el = '<li>';
            $child_html_end_el = '</li>';
        }
        ?>
        <div class="comment-box" id="comment-<?php comment_ID(); ?>">
            <?php if ($comment->comment_type != 'trackback' && $comment->comment_type != 'pingback') { ?>
			<div class="comment ">
			<?php } else { ?>
				<div class="comment yes-ping">
				<?php } ?>
				<?php if ($comment->comment_type != 'trackback' && $comment->comment_type != 'pingback') { ?>
					<div class="author-thumb">
						<figure class="thumb">
							<?php print get_avatar($comment, 80, null, null, array('class' => array())); ?>
						</figure>
					</div>
				<?php } ?>
				<h4 class="name"><?php echo get_comment_author_link(); ?></h4>
				<div class="date"><?php echo get_the_date(); ?></div>
				<div class="text">
					<?php comment_text(); ?>
				</div>
					<?php 
						$replyBtn = 'reply-btn';
						echo preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $replyBtn, 
							get_comment_reply_link(array_merge( $args, array(
								'reply_text' => esc_html__('Répondre', 'servicer'),
								'depth' => $depth,
								'max_depth' => $args['max_depth']))), 1 
						); 
					?>
			</div>
			
		<?php
	}
}

if ( ! function_exists( 'service_theme_breadcrumb' ) ) :
	function service_theme_breadcrumb() {
		$breadcrumb_title = 'servicer';
		$breadcrumb_class = 'breadcrumb_no_bg';
		if ( is_front_page() && is_home() ) :
			$breadcrumb_title = ''; // deafult blog
			$breadcrumb_class = 'deafult-home-breadcrumb';
		elseif ( is_front_page() && !is_home() ) :
			$breadcrumb_title = ''; // custom home or deafult
			$breadcrumb_class = 'custom-home-breadcrumb';
		elseif ( is_home() ) :
			$blog_breadcrumb_switch = servicer_get_options('blog_breadcrumb_switch');
			if ( $blog_breadcrumb_switch == '1' ) :
				$blog_breadcrumb_content = servicer_get_options('blog_breadcrumb_content');
				$breadcrumb_title = $blog_breadcrumb_content;
			else :
				$breadcrumb_title = '';
			endif;
			$breadcrumb_class = 'blog-breadcrumb';
		elseif ( is_archive() ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'blog-breadcrumb';
		elseif ( is_single() ) :
			$blog_single_breadcrumb_switch = servicer_get_options('blog_single_breadcrumb_switch');
			if ( $blog_single_breadcrumb_switch == '1' ) :
				if ( get_post_type( get_the_ID() ) && !is_single() ) :
					$breadcrumb_title = get_post_type() . esc_html__(' Details', 'servicer');
				else :
					$blog_single_breadcrumb_content = servicer_get_options('blog_single_breadcrumb_content');
					$breadcrumb_title = $blog_single_breadcrumb_content;
				endif;
			else :
				$breadcrumb_title = '';
			endif;
			$breadcrumb_class = 'blog-single-breadcrumb';
		elseif ( is_404() ) :
			$breadcrumb_title = esc_html__('Error Page', 'servicer');
			$breadcrumb_class = 'blog-breadcrumb';
		elseif ( is_search() ) :
			if ( have_posts() ) :
				$breadcrumb_title = esc_html__('Résultats de la recherche pour: ', 'servicer') . get_search_query();
				$breadcrumb_class = 'blog-breadcrumb';
			else :
				$breadcrumb_title = esc_html__('Rien trouvé', 'servicer');
				$breadcrumb_class = 'blog-breadcrumb';
			endif;
		elseif ( !is_home() && !is_front_page() && !is_search() && !is_404() ) :
			$breadcrumb_title = get_the_title();
			$breadcrumb_class = 'page-breadcrumb';
		endif;
		$breadcrumb_active_class = 'breadcrumb-not-active py-lg-5 py-md-4 py-sm-4 py-3';
		if (function_exists('bcn_display')) : 
			$breadcrumb_active_class = '';
		endif;
		?>
		<?php
			$servicer_show_breadcrumb = get_post_meta(get_the_ID(), 'servicer_theme_metabox_show_breadcrumb', true);
			$blog_breadcrumb_style = servicer_get_options('blog_breadcrumb_style');
			//  py-lg-5 py-md-4 py-sm-4 py-3 
		?>
		<?php if ($servicer_show_breadcrumb != 'off') : ?>
			<?php if(isset($breadcrumb_title) && !empty($breadcrumb_title)) : ?>
				<div class="container">
					<div class="row justify-content-center">
						<div class="blog-listing blog-single-preview col-lg-8 col-md-8 col-sm-10 m-sm-0 m-2">
							<div class="home-page my-lg-5 my-md-4 my-3">
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
				<?php if ($blog_breadcrumb_style == '2') : ?>
					<section class="page-header-area breadcrumb-header <?php echo esc_attr($breadcrumb_class . ' ' . $breadcrumb_active_class); ?>">
						<div class="overlay"></div>
						<div class="container">
							<div class="banner">
								<div class="head-info page-header text-center">
									<h1><?php echo wp_kses($breadcrumb_title,'code_contxt'); ?></h1>
									<?php if (function_exists('bcn_display')) : ?>
										<?php bcn_display(); ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</section>
				<?php else : ?>
					<section class="page-header-area <?php echo esc_attr($breadcrumb_class . ' ' . $breadcrumb_active_class); ?>">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<div class="page-header">
										<h6><?php echo wp_kses($breadcrumb_title,'code_contxt'); ?></h6>
										<?php if (function_exists('bcn_display')) : ?>
											<?php bcn_display(); ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php
	}
endif;

add_action('service_theme_breadcrumb_ready', 'service_theme_breadcrumb');

if (!function_exists('service_theme_tag_list')) :

    function service_theme_tag_list() {
        if ('post' === get_post_type()) {
            $tags_list = get_the_tag_list('',esc_html__(', ', 'servicer'));
            if ($tags_list) {
                printf($tags_list);
            }
        }
    }

endif;