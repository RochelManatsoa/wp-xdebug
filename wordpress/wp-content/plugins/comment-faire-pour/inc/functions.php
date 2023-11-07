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

    $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center shadow">';
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

function nrd_latest_posts_carousel() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $latest_posts = new WP_Query($args);

    $output = '';

    if ($latest_posts->have_posts()) {
        while ($latest_posts->have_posts()) {
            $latest_posts->the_post();

            // Check for featured image
            if (has_post_thumbnail()) {
                $img_src = get_the_post_thumbnail_url(null, 'full');
            } else {
                // Get first image from the_content if no featured image
                $content = get_the_content();
                $doc = new DOMDocument();
                @$doc->loadHTML($content);
                $xpath = new DOMXPath($doc);
                $img_src = $xpath->evaluate("string(//img/@src)"); // Get src attribute of the first image
            }

            $output .= '<div class="card mb-3" style="max-width: 540px;">';
            $output .= '<div class="row no-gutters">';
            $output .= '<div class="col-md-4  d-flex align-items-stretch">';
            $output .= '<img src="' . $img_src . '" alt="' . get_the_title() . '" style="height: 100%; width: 100%; object-fit: cover;">';
            $output .= '</div>';
            $output .= '<div class="col-md-8">';
            $output .= '<div class="card-body">';
            $output .= '<h5 class="card-title">' . get_the_title() . '</h5>';
            $output .= '<p class="card-text">' . get_the_excerpt() . '</p>';
            $output .= '<p class="card-text"><small class="text-muted">Publié le ' . get_the_date() . '</small></p>';
            $output .= '</div></div></div></div>';
        }

        wp_reset_postdata();
        return $output;
    } else {
        return '<p>Aucun article récent trouvé.</p>';
    }
}