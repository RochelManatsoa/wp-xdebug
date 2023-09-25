<?php 

function register_script() {
    wp_register_script( 'pfm_js', plugins_url('../js/main.js', __FILE__), [], '4.0.1', true);
    wp_register_style( 'pfm_css', plugins_url('../css/style.css', __FILE__), [], '4.0.1', 'all');
    wp_register_style('font-awesome_css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', false, '6.4.2', 'all');
}

function enqueue_style(){
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,700&family=Montserrat:ital,wght@0,900;1,400&family=PT+Sans&family=Ubuntu&display=swap', false );
    wp_enqueue_script('pfm_js');
    wp_enqueue_style( 'pfm_css' );
    wp_enqueue_style('font-awesome_css');
}

/**
 * Add custom Meta Tag to header. 
 * For discover in google search console
 */
function custom_header_metadata()
{
  echo ' <meta name="robots" content="max-image-preview:large" />';
}

function popup_after_title_in_mobile($content)
{
    $html = new simple_html_dom();
    $html->load($content);

    processLinks($html);
    processImages($html);

    if (is_single() && $GLOBALS['post']->ID == get_the_ID()) {
        $custom_content = addCustomContent(get_the_ID(), $html);
        return $custom_content;
    }

    return $html;
}

function processLinks($html)
{
    $links = $html->find('a');
    if (is_iterable($links)) {
        foreach ($links as $link) {
            modify_single_link($link);
        }
    }
}

function modify_single_link($link)
{
    setAnchorTextFromDomain($link);
    if (strpos($link->href, '@')) {
        handleEmail($link);
    } elseif (strpos($link->href, '090488503')) {
        handlePhoneNumber($link, SITE_NUMBER);
    } else {
        handleHttps($link);
    }
}

function addCustomContent($post_id, $html)
{
    $custom_content = '';
    $activer_image_mobile_en_haut = 1;
    $activer_image_mobile_en_bas = 1;
    $number_click_to_call = SITE_NUMBER;
    $second_featured_image = wp_get_attachment_image(6081, "medium");
    $third_featured_image = wp_get_attachment_image(6080, "medium");
    // $second_featured_image = wp_get_attachment_image(57, "medium");
    // $third_featured_image = wp_get_attachment_image(54, "medium");
        
	$custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white shadow">';
	$custom_content .= '<div class="textwidget-slide">';
	$custom_content .= '<figure class="wp-block-image text-center icon-image-container">';
	$custom_content .= '<a href="tel:' . $number_click_to_call . '">';
	$custom_content .= '<i class="fa-solid fa-phone-volume faa-pulse animated mr-2"></i>';
	$custom_content .= $second_featured_image;
	$custom_content .= '</a>';
	$custom_content .= '</figure>';
	$custom_content .= '</div>';
	$custom_content .= '</div>';

	$custom_content .= '<div id="sticky-footer" class="container-fluid fixed-bottom d-block d-sm-none text-center align-center bg-white shadow">';
	$custom_content .= '<figure>';
	$custom_content .= '<a href="tel:' . $number_click_to_call . '">';
	$custom_content .= $third_featured_image;
	$custom_content .= '</a>';
	$custom_content .= '</figure>';
	$custom_content .= '</div>';

    return $custom_content .= $html;
}

function handleEmail(&$link)
{
    if (substr($link->href, 0, 7) !== "mailto:") {
        $link->href = 'mailto:' . $link->href;
    }
}

function handlePhoneNumber(&$link, $siteNumber)
{
    $link->href = 'tel:' . $siteNumber;
}

function handleBrokenLink(&$link, $unBroken)
{
    $link->href = $unBroken;
}

function handleHttps(&$link)
{
    if (substr($link->href, 0, 3) === "www") {
        $link->href = 'https://' . $link->href;
    } elseif (substr($link->href, 0, 5) === "http:") {
        $link->href = substr($link->href, 0, 4) . 's' . substr($link->href, 4);
    }
}

function setAnchorTextFromDomain(&$link)
{
    if (trim($link->innertext) === trim($link->href)) {
        $url = $link->href;
        $parsed_url = parse_url($url);
        $link->innertext = $parsed_url['host'] ?? 'Lien';
    }
}

function processImages($html)
{
    $images = $html->find('img');
    if (is_single()) {
        if (isset($images[0]->src)) {
            $src = $images[0]->src;
            if (preg_match('/(nouveau-visuel-belgique|contacter_service_client_belgique)/', $src)) {
                $images[0]->setAttribute('class', 'd-none d-sm-block');
            }
        }
    }

    if (is_iterable($images)) {
        foreach ($images as $image) {
            modify_single_image($image);
        }
    }
}

function modify_single_image($value)
{
    if (isset($value->alt) && empty($value->alt)) {
        $value->alt = 'Image dans ' . SITE_NAME;
    }
}

function popup_after_title_in_mobile_last( $content ) {

    $html = new simple_html_dom();
    $html->load($content);
    $link = $html->find('a');
    $img = $html->find('img');

    if (is_array($link) || is_object($link)) {
        foreach ($link as $value) {
            // check if email
            if (strpos($value->href, '@')) {
                if (substr($value->href, 0, 7) !== 'mailto:') {
                    $value->href = 'mailto:' . $value->href;
                }
            } elseif (strpos($value->href, 'tel:') === 0) {
                // remove spaces in phone number
                $value->href = str_replace(' ', '', $value->href);
            } else {
                // Check https
                if (
                    filter_var($value->href, FILTER_VALIDATE_URL) === false
                ) {
                    $value->href = '#';
                } elseif (substr($value->href, 0, 3) === 'www') {
                    $value->href = 'https://' . $value->href;
                } elseif (substr($value->href, 0, 5) === 'http:') {
                    $value->href = substr($value->href, 0, 4) .'s'.substr($value->href, 4);
                }
            }
        }
    }

    if (is_array($img) && $img !== [] || is_object($img)) {
        if (is_single()) {
            $img[0]->setAttribute('class', 'd-none d-sm-block');
        }
        foreach ($img as $value) {

            // check alt attr defined
            if ($value->alt === null || !isset($value->alt) || $value->alt == '') {
                $value->alt = 'numero service client belgique';
            }
        }
    }

    if( is_single() && ! empty( $GLOBALS['post'] ) ) {
        
        // add popup if single
        if ( $GLOBALS['post']->ID == get_the_ID() ) {

            $custom_content = '';
    
            if(metadata_exists('post', get_the_ID(), 'second_featured_image') && get_post_meta( get_the_ID(), 'second_featured_image', true) !== "") {
                $second_featured_image = wp_get_attachment_image(get_post_meta( get_the_ID(), 'second_featured_image', true),'full');
            }else{
                $second_featured_image = '<img loading="lazy" alt="click to call" width="300" height="248" src="https://numero-serviceclient.be/wp-content/uploads/2023/02/NSC-090488503.jpg">';
            }
            if(metadata_exists('post', get_the_ID(), 'third_featured_image') &&get_post_meta( get_the_ID(), 'third_featured_image', true) !== "") {
                $third_featured_image = wp_get_attachment_image(get_post_meta( get_the_ID(), 'third_featured_image', true),'full');
            }else{
                $third_featured_image = '<img class="alignnone size-full ls-is-cached lazyloaded mx-auto d-block" src="https://numero-serviceclient.be/wp-content/uploads/2022/10/nouveau-visuel-belgique.jpg" alt="cartouche" width="300" height="71"/>';
            }

            $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white">';
                $custom_content .= '<div class="textwidget-slide">';
                    $custom_content .= '<figure class="wp-block-image">';
                        $custom_content .= '<a href="tel:090488503">';
                            $custom_content .= $second_featured_image;
                        $custom_content .= '</a>';
                    $custom_content .= '</figure>';
                $custom_content .= '</div>';     
            $custom_content .= '</div>';
            $custom_content .= '<figure class="fixed-bottom d-block d-sm-none">';
                $custom_content .= '<a href="tel:090488503">';
                    $custom_content .= $third_featured_image;
                $custom_content .= '</a>';
            $custom_content .= '</figure>';
            $custom_content .= $html;

            return $custom_content;

        }

    }

    return $html;
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


