<?php 


function enqueue_style() {
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_script( 'pfm_js', plugins_url('../js/main.js', __FILE__), [], '2.5.1', true );
    wp_enqueue_style( 'pfm_css', plugins_url('../css/style.css', __FILE__), false, '1.0.0', 'all');
}


/**
 * Function to automatically update the focus keyword with the post title, if no focus keyword is set
 */
function update_focus_keywords() {
    $posts = get_posts(array(
    'posts_per_page'	=> -1,
    'post_type'		=> 'post' // Replace post with the name of your post type
    ));
    foreach($posts as $p){
        // Checks if Rank Math keyword already exists and only updates if it doesn't have it
        $rank_math_keyword = get_post_meta( $p->ID, '_yoast_wpseo_focuskw', true );

        if ( ! $rank_math_keyword ){ 
                $new_keywords = strtolower(sanitize_title(get_the_title($p->ID)));
                $keywords = str_replace("-", " ", $new_keywords);
                update_post_meta($p->ID,'_yoast_wpseo_focuskw', $keywords);
            }
        }
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

add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('template_include', 'search_template', 99);
