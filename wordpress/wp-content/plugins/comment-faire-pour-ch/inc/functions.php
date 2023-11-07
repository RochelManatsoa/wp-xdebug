<?php

function register_script()
{
    wp_register_script('pfm_js', plugins_url('../js/main.js', __FILE__), [], '2.1.1');
    wp_register_style('pfm_css', plugins_url('../css/style.css', __FILE__), false, '2.1.1', 'all');
}

function enqueue_style()
{
    wp_enqueue_script('pfm_js');
    wp_enqueue_style('pfm_css');
    wp_enqueue_script('bootstrap_jquery_js', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js');
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
}

function custom_header_metadata()
{
  echo ' <meta name="robots" content="max-image-preview:large" />';
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

function popup_after_title_in_mobile($content)
{
    $html = new simple_html_dom();
    $html->load($content);
    processImages($html);

    if (is_single() && $GLOBALS['post']->ID == get_the_ID()) {
        $custom_content = addCustomContent(get_the_ID(), $html);
        return $custom_content;
    }

    return $html;
}

function addCustomContent($post_id, $html)
{
    $custom_content = '';
    $number_click_to_call = SITE_NUMBER;
    $second_featured_image = wp_get_attachment_image(SITE_VISUEL_ITEM, "medium");

    $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center">';
    $custom_content .= '<div class="textwidget-slide">';
    $custom_content .= '<figure class="wp-block-image">';
    $custom_content .= '<a href="tel:' . $number_click_to_call . '">';
    $custom_content .= $second_featured_image;
    $custom_content .= '</a>';
    $custom_content .= '</figure>';
    $custom_content .= '</div>';
    $custom_content .= '</div>';

    return $custom_content .= $html;
}

function processImages($html)
{
    if (isset($html->find('img')[0]->src)) {
        $html->find('img')[0]->setAttribute('class', 'd-none d-sm-block');
    }

    foreach ($html->find('img') as $value) {
        if (empty($value->alt)) {
            $value->alt = SITE_NAME;
        }
    }
}