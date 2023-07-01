<?php 

function enqueue_style(){
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Rubik|PT+Sans|Merriweather|Poppins:wght@800&display=swap', false );
    wp_enqueue_style( 'pfm_css', plugins_url('../css/style.css', __FILE__), false, '1.0.0', 'all');
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
add_shortcode('icegram', function(){
    return "";
});