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
 * New search result template
 * Add number result if nothing matched search terms
 */
function search_template($template)
{
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


function popup_after_title_in_mobile($content) 
{
    if (!is_single() && !is_archive()) {
        return $content;
    }

    $html = new simple_html_dom();
    $html->load($content);

    processLinks($html);
    processImages($html);
    
    // Traitement du contenu pour les articles individuels
    if (is_single() && !empty($GLOBALS['post'])) {
        $categories = get_the_category(get_the_ID());
        $custom_content = '';
        $backlinks = '';            
        $second_featured_image = '';
        $third_featured_image = '';
        $activer_image_mobile_en_bas = '';
        $number_click_to_call = SITE_NUMBER;

        $activer_image_mobile_en_haut = "1";
        $activer_image_mobile_en_bas = "0";
        $second_featured_image = wp_get_attachment_image(SITE_VISUEL_ITEM, "medium");
        if( get_the_ID() == 53292){
            $second_featured_image = wp_get_attachment_image(137492, "medium");
            $number_click_to_call = "0895690365";
        }

        if($activer_image_mobile_en_haut === "1"){
            $custom_content .= '<div class="container-fluid Mobile_W d-block d-sm-none text-center align-center py-3 bg-white shadow">';
            $custom_content .= '<div class="textwidget-slide">';
            $custom_content .= '<figure class="wp-block-image">';
            $custom_content .= '<a href="tel:'.$number_click_to_call.'">';
            $custom_content .= $second_featured_image;
            $custom_content .= '</a>';
            $custom_content .= '</figure>';
            $custom_content .= '</div>';
            $custom_content .= '</div>';
        }

        if($activer_image_mobile_en_bas === "1"){
            $custom_content .= '<div class="container-fluid fixed-bottom d-block d-sm-none text-center align-center ">';
            $custom_content .= '<figure>';
            $custom_content .= '<a href="tel:'.$number_click_to_call.'">';
            $custom_content .= $third_featured_image;
            $custom_content .= '</a>';
            $custom_content .= '</figure>';
            $custom_content .= '</div>';
        }

        foreach ($categories as $cd) {
            if ($cd->category_nicename == 'gare') {
                $activer_image_mobile_en_haut = "0";
                $activer_image_mobile_en_bas = "0";
                $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                $backlinks .= '<div class="good-deal">';
                    $backlinks .= 'Liens utiles:';
                    $backlinks .= ' <a href="https://www.thetrainline.com/fr" title="Tous vos billets de train et de bus, au même endroit" target=_blank>Trainline</a>';
                    $backlinks .= ' | <a href="https://garedefrance.fr" title="Annuaire des GARES SNCF en France" target=_blank>Gare de France</a>';
                    $backlinks .= ' | <a href="https://garebelgique.be" title="Annuaire des GARES en Belgique" target=_blank>Gare de Belgique</a>';
                $backlinks .= '</div>';
                $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="47"/>';
            }
            if ($cd->category_nicename == 'gare-sncf') {
                $activer_image_mobile_en_haut = "0";
                $activer_image_mobile_en_bas = "0";
                $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="47"/>';
            }

        // echo '<pre>';
        // echo var_dump($cd->category_nicename);
        // echo '</pre>';
        }

        $custom_content .= $html;

        // links for seo
        $custom_content .= $backlinks;

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
    if (strpos($link->href, '@')) {
        handleEmail($link);
    } elseif (strpos($link->href, '0890211833')) {
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

function handleHttps(&$link)
{
    if (substr($link->href, 0, 3) === "www") {
        $link->href = 'https://' . $link->href;
    } elseif (substr($link->href, 0, 5) === "http:") {
        $link->href = substr($link->href, 0, 4) . 's' . substr($link->href, 4);
    }
}
function replaceSrc(&$value, $newSrc) 
{
    $value->src = $newSrc;
    if (isset($value->srcset)) {
        // Suppression de '.jpg' de $newSrc
        $newSrc = str_replace('.jpg', '', $newSrc);
        // Remplacement de l'URL originale dans srcset par $newSrc
        $value->setAttribute('srcset', preg_replace('/https?:\/\/[^ ]+/', $newSrc . '.jpg', $value->srcset));
    }
}

function setClassIfMatching(&$value, $string, $class) 
{
    if (strpos($value->src, $string) !== false) {
        $value->setAttribute('class', $class);
    }
}

function processImages($html)
{
    $newSrc = 'https://i0.wp.com/comment-joindre.fr/wp-content/uploads/2023/06/NOUVEAU-VISUEL-COMMENT-JOINDRE.jpg';

    $patterns = [
        '2021/11/COMMENT-CONTACTER', '2023/06/COMMENT-CONTACTER-',
        '2023/02/le-numero', '2023/01/visuel', '2023/03/VISUEL-', 
        '2023/02/0890211805-BOUTON-APPEL', '2021/10/visuel-gare-CJ', 
        '2023/02/information', '2022/12/0890211805', 
        '2023/02/assistance-generaliste', '2023/05/assistance-par-telephone-service-client'
    ];

    if (isset($html->find('img')[0]->src)) {
        $html->find('img')[0]->setAttribute('class', 'd-none d-sm-block');
    }

    foreach ($html->find('img') as $value) {
        foreach ($patterns as $pattern) {
            if (strpos($value->src, $pattern) !== false) {
                replaceSrc($value, $newSrc);
                break;
            }
        }
    
        if (strpos($value->src, 'bouton_APPELER_COMMENT-JOINDRE') !== false) {
            replaceSrc($value, $newSrc);
            setClassIfMatching($value, 'bouton_APPELER_COMMENT-JOINDRE', 'd-none d-sm-block');
        }
    
        setClassIfMatching($value, 'image', 'd-none d-sm-block');
    
        if (empty($value->alt)) {
            $value->alt = SITE_NAME;
        }
    }
}



