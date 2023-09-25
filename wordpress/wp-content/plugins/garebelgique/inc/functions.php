<?php 

function register_script() {
    wp_register_style('pfm_css', plugins_url('../css/style.css', __FILE__), false, '2.1.1', 'all');
    wp_register_style('bootstrap_css', plugins_url('../assets/bootstrap.min.css', __FILE__), false, '4.6.1', 'all');
    wp_register_style('font-awesome_css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', false, '6.4.2', 'all');
    wp_register_script('pfm_js', plugins_url('../js/main.js', __FILE__), ['bootstrap_js'], '2.1.1', true );
    wp_register_script('bootstrap_jquery_js', plugins_url('../assets/jquery.min.js', __FILE__), [], '3.5.1', true);
    wp_register_script('bootstrap_js', plugins_url('../assets/bootstrap.bundle.min.js', __FILE__), ['bootstrap_jquery_js'], '4.6.1', true);
}

function enqueue_style(){
    wp_enqueue_script('pfm_js');
    wp_enqueue_style('pfm_css');
    wp_enqueue_style('bootstrap_css');
    wp_enqueue_style('font-awesome_css');
}

function popup_after_title_in_mobile($content) 
{
    $content = add_async_defer($content);
    if (!is_single() && !is_archive()) {
        return $content;
    }

    $html = new simple_html_dom();
    $html->load($content);

    processLinks($html);
    processImages($html);
    // Traitement du contenu pour les articles individuels
    if (is_single() && !empty($GLOBALS['post'])) {$custom_content = '';
        $number_click_to_call = SITE_NUMBER;
        $activer_image_mobile_en_haut = true;
        $activer_image_mobile_en_bas = true;
        $second_featured_image = wp_get_attachment_image(3054, "medium");
        $third_featured_image = wp_get_attachment_image(3048, "medium");
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
    
        // echo '<pre>';
        // echo var_dump($GLOBALS['post']);
        // echo '</pre>';
    
        $custom_content .= $html;
    
        return $custom_content;
    }

    return $html;
}

function add_async_defer($content) {
    $content = str_replace("src=\"https://www.youtube.com", "async src=\"https://www.youtube.com", $content);
    $content = str_replace("src=\"https://platform.twitter.com", "defer src=\"https://platform.twitter.com", $content);
    $content = str_replace("src=\"https://www.googletagmanager.com", "async src=\"https://www.googletagmanager.com", $content);
    return $content;
}

function add_defer_attribute($tag, $handle) {
    // Les handles de vos scripts
    $scripts_to_defer = array('pfm_js', 'bootstrap_jquery_js', 'bootstrap_js');
    
    foreach($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' async="async" src', $tag);
        }
    }
    return $tag;
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
    if (strpos($link->href, '@')) {
        handleEmail($link);
    } elseif (strpos($link->href, '090488503')) {
        handlePhoneNumber($link, SITE_NUMBER);
    } else {
        handleHttps($link);
    }
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

function processImages($html)
{  
    if(isset($html->find('img')[0])){
        setClassIfMatching($html->find('img')[0], 'nouveau-visuel-belgique', 'd-none d-sm-block');
    }

    foreach ($html->find('img') as $value) {
        if (empty($value->alt)) {
            $value->alt = SITE_NAME;
        }
    }
}

function setClassIfMatching(&$value, $string, $class) 
{
    if (strpos($value->src, $string) !== false) {
        $value->setAttribute('class', $class);
    }
}