<?php 

function register_script() {
    wp_register_script( 'pfm_js', plugins_url('../js/main.js', __FILE__), [], '2.1.1' );
    wp_register_style( 'pfm_css', plugins_url('../css/style.css', __FILE__), false, '2.1.1', 'all');
}

function enqueue_style(){
    wp_enqueue_script('bootstrap_jquery_js', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js');
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_script('pfm_js');
    wp_enqueue_style( 'pfm_css' );
}

/**
 * Remove canonical items
 *
 */

function yoast_remove_canonical_items( $canonical ) {
    if(is_single()){
		$duplicate = substr(get_slug(), -3);
		$duplicate = substr($duplicate, strpos($duplicate, "-"));
        if(get_page_by_path(substr(get_slug(), 0, - strlen($duplicate)), OBJECT, 'post')){
            if ( 
				$duplicate == "-2" || 
				$duplicate == "-3" || 
				$duplicate == "-4" || 
				$duplicate == "-5" || 
				$duplicate == "-6" || 
				$duplicate == "-7" || 
				$duplicate == "-8" || 
				$duplicate == "-9" || 
				$duplicate == "-10" || 
				$duplicate == "-11" || 
				$duplicate == "-12" || 
				$duplicate == "-13" || 
				$duplicate == "-14" || 
				$duplicate == "-15"
			) {
                return false;
            }
        }
    }
    return $canonical; 
}

/**
 * Get the URL slug
 *
 */

function get_slug(){
    return get_post_field( 'post_name', get_post() );
}

/**
 * Edit seo meta tilte
 *
 */

function yoast_edit_title_items( $title ) {
    if(is_single()){
		$duplicate = substr(get_slug(), -3);
		$duplicate = substr($duplicate, strpos($duplicate, "-"));
		if ( 
			$duplicate == "-2" || 
			$duplicate == "-3" || 
			$duplicate == "-4" || 
			$duplicate == "-5" || 
			$duplicate == "-6" || 
			$duplicate == "-7" || 
			$duplicate == "-8" || 
			$duplicate == "-9" || 
			$duplicate == "-10" || 
			$duplicate == "-11" || 
			$duplicate == "-12" || 
			$duplicate == "-13" || 
			$duplicate == "-14" || 
			$duplicate == "-15"
		   ) {
			return $title .=  ' (' . substr($duplicate, 1) . ')';
		}
	}
	if(is_archive() && get_queried_object()){
		$duplicate = substr(get_queried_object()->slug, -2);
		if ( 
			$duplicate == "-2" || 
			$duplicate == "-3" || 
			$duplicate == "-4" || 
			$duplicate == "-5" || 
			$duplicate == "-6" || 
			$duplicate == "-7" || 
			$duplicate == "-8" || 
			$duplicate == "-9" 
		   ) {
			return $title .=  ' (' . substr($duplicate, 1) . ')';
		}
	}

    return $title; 
}


/**
 * Edit seo meta description
 *
 */

 function yoast_edit_desc_items( $desc ) {
    if(is_single()){
		$duplicate = substr(get_slug(), -3);
		$duplicate = substr($duplicate, strpos($duplicate, "-"));
		if ( 
			$duplicate == "-2" || 
			$duplicate == "-3" || 
			$duplicate == "-4" || 
			$duplicate == "-5" || 
			$duplicate == "-6" || 
			$duplicate == "-7" || 
			$duplicate == "-8" || 
			$duplicate == "-9" || 
			$duplicate == "-10" || 
			$duplicate == "-11" || 
			$duplicate == "-12" || 
			$duplicate == "-13" || 
			$duplicate == "-14" || 
			$duplicate == "-15"
		   ) {
			return $desc .=  ' (' . substr($duplicate, 1) . ')';
		}
	}
	if(is_archive() && get_queried_object()){
		$duplicate = substr(get_queried_object()->slug, -2);
		if ( 
			$duplicate == "-2" || 
			$duplicate == "-3" || 
			$duplicate == "-4" || 
			$duplicate == "-5" || 
			$duplicate == "-6" || 
			$duplicate == "-7" || 
			$duplicate == "-8" || 
			$duplicate == "-9" 
		   ) {
			return $desc .=  ' (' . substr($duplicate, 1) . ')';
		}
	}

    return $desc; 
}

/**
 * New search result template
 * Add number result if nothing matched search terms
 */
function search_template($template){
    if ( get_query_var('s') == true || get_query_var('s') != '' || is_404()) {
        $file_name = 'search-personnalized.php';
        if ( locate_template( $file_name ) ) {
            $new_template = locate_template( $file_name );
        } else {
            // Template not found in theme's folder, use plugin's template as a fallback
            $new_template = dirname( __FILE__ ) . '/' . $file_name;
        }
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }
    return $template;
}

add_action('template_include', 'search_template', 99);
add_action('init', 'register_script');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_filter('wpseo_canonical', 'yoast_remove_canonical_items' , 47);
add_filter('wpseo_metadesc', 'yoast_edit_desc_items', 59);
add_filter('wpseo_title', 'yoast_edit_title_items' , 49);