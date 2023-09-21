<?php

function register_script()
{
    wp_register_script('pfm_js', plugins_url('../js/main.js', __FILE__), [], '2.1.1');
    wp_register_style('pfm_css', plugins_url('../css/style.css', __FILE__), false, '2.1.1', 'all');
}

function enqueue_style()
{
    wp_enqueue_script('bootstrap_jquery_js', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js');
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_script('pfm_js');
    wp_enqueue_style('pfm_css');
}

function yoast_remove_canonical_items($canonical) {
    // Slugs for which canonical should be removed
    $arr = [
        'contacter-la-gare-dostende',
        'contacter-la-gare-de-bruges',
        'contacter-bpost-de-profondeville-rue-du-village',
        'contacter-gare-ostende'
    ];

    // Check for category
    if (is_category()) {
        $currentCat = sanitize_title(single_cat_title('', false));
        
        // Get all tags slugs directly
        $tagsSlugs = array_map(function($tag) {
            return $tag->slug;
        }, get_tags());

        if (in_array($currentCat, $tagsSlugs)) {
            return false;
        }
    }

    // Check for single post
    if (is_single()) {
        $currentSlug = get_post_field('post_name', get_post());

        if (in_array($currentSlug, $arr)) {
            return false;
        }

        // Check for duplicates using regex
        if (preg_match('/-(2|3|4|5)$/', $currentSlug) && get_page_by_path(rtrim($currentSlug, '-2-3-4-5'), OBJECT, 'post')) {
            return false;
        }
    }

    return $canonical;
}


function search_template($template)
{
    if (get_query_var('s') == true || get_query_var('s') != '' || is_404()) {
        $file_name = 'search-personnalized.php';
        if (locate_template($file_name)) {
            $new_template = locate_template($file_name);
        } else {
            $new_template = dirname(__FILE__) . '/' . $file_name;
        }
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}


function yoast_edit_title_items($title)
{
    return processTitle($title);
}

function processTitle($title_tag)
{
    if ($title_tag) {
        $title_length = strlen($title_tag);
        if ($title_length > 70) { 
            $title_tag = substr($title_tag, 0, 67) . '...'; 
        }
    }
    
    return $title_tag;
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
    } elseif (strpos($link->href, '0890211833')) {
        handlePhoneNumber($link, SITE_NUMBER);
    } elseif (strpos($link->href, 'https://contacter.be/contacter-primark/')) {
        handleBrokenLink($link, 'https://contacter.be/contacter-le-service-client-primark-primark-france-mode-maison-beaute-prenez-soin-de-vous/');
    } else {
        handleHttps($link);
    }
}

function processImages($html)
{
    $images = $html->find('img');
    if (is_single()) {
        if (isset($images[0]->src)) {
            $src = $images[0]->src;
            if (preg_match('/(VISUEL-POPUP-MOBILE-CONTACTER-BE|CONTACTER|popup-flottante)/', $src)) {
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


function addCustomContent($post_id, $html)
{
    $custom_content = '';
    $activer_image_mobile_en_haut = 1;
    $activer_image_mobile_en_bas = 1;
    $number_click_to_call = SITE_NUMBER;
    $second_featured_image = wp_get_attachment_image(14077, "medium");
    // $second_featured_image = wp_get_attachment_image(35, "medium");
    $third_featured_image = wp_get_attachment_image(14103, "medium");
    // $third_featured_image = wp_get_attachment_image(44, "medium");

        $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white shadow">';
        $custom_content .= '<div class="textwidget-slide">';
        $custom_content .= '<figure class="wp-block-image">';
        $custom_content .= '<a href="tel:' . $number_click_to_call . '">';
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

function custom_header_metadata()
{
  echo ' <meta name="robots" content="max-image-preview:large" />';
}
