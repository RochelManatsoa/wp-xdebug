<?php
/*
Plugin Name: ANNUAIRE TELEPHONIQUE FRANÇAIS ET DE MISE EN RELATION - AWM Océan Indien
Description: Plugin qui ajoute deux images flottante sur la version mobile du site (requiert les plugins: myStickyMenu, Advanced Custom Fiels). Ajoute le framework CSS twitter Bootstrap. Corrige les liens externes et mail générant un 404 error et ajoute des balises alt sur tout les image. Ce plugin affiche egalement des polices de google fonts. Ajoute l'attribut rel="canonical" pour les contenus dupliqués.
Version: 3.7.0
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

require_once NRD_PATH . '/inc/functions.php';
require_once NRD_PATH . '/inc/simple_html_dom.php';

function popup_after_title_in_mobile($content)
{
    if (is_single() || is_archive()) {
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

        if (is_array($img) || is_object($img)) {
            // hide featured image in mobile view
            if (isset($img[0]->src) && (strpos($img[0]->src, 'image') || strpos($img[0]->src, '0890211805'))) {
                $img[0]->setAttribute('class', 'd-none d-sm-block');
            }

            foreach ($img as $value) {
                if (strpos($value->src, 'bouton_APPELER_COMMENT-JOINDRE')) {
                    $value->src =
                        'https://comment-joindre.fr/wp-content/uploads/2022/12/0890211805-par-REMMEDIA-pour-COMMENT-JOINDRE.jpg';
                    $value->setAttribute('class', 'd-none d-sm-block');
                }
                if (
                    strpos($value->src, 'bouton_APPELER_COMMENT-JOINDRE') ||
                    strpos($value->src, 'image')
                ) {
                    $value->setAttribute('class', 'd-none d-sm-block');
                }
                // check alt attr defined
                if (
                    $value->alt === null ||
                    !isset($value->alt) ||
                    $value->alt == ''
                ) {
                    $value->alt = 'comment-joindre.fr';
                }
            }
        }

        if (is_single() && !empty($GLOBALS['post'])) {

            $categories = get_the_category(get_the_ID());

            $custom_content = '';
            $backlinks = '';

            if (metadata_exists('post', get_the_ID(), 'second_featured_image') && (get_post_meta(get_the_ID(), 'second_featured_image', true) !== "0" && get_post_meta(get_the_ID(), 'second_featured_image', true) !== "")) {
                $second_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'second_featured_image', true), 'full');
            } else {
                $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/default.jpg', __FILE__).'" alt="call service" width="275" height="245" />';
            }
    
            if (metadata_exists('post', get_the_ID(), 'third_featured_image') && (get_post_meta(get_the_ID(), 'third_featured_image', true) !== "" && get_post_meta(get_the_ID(), 'third_featured_image', true) !== "0")) {
                $third_featured_image = wp_get_attachment_image(get_post_meta(get_the_ID(), 'third_featured_image', true), 'full');
            } else {
                $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche.png', __FILE__).'" alt="cartouche" width="350" height="96"/>';
            }
    
    
            if (metadata_exists('post', get_the_ID(), 'number_click_to_call') && get_post_meta(get_the_ID(), 'number_click_to_call', true) !== "") {
                $number_click_to_call = get_post_meta(get_the_ID(), 'number_click_to_call', true);
            } else {
                $number_click_to_call = '0890211805';
            }

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_haut') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true) !== "") {
                $activer_image_mobile_en_haut = get_post_meta(get_the_ID(), 'activer_image_mobile_en_haut', true);
            }else{
                $activer_image_mobile_en_haut = "0";
            }

            if (metadata_exists('post', get_the_ID(), 'activer_image_mobile_en_bas') && get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true) !== "") {
                $activer_image_mobile_en_bas = get_post_meta(get_the_ID(), 'activer_image_mobile_en_bas', true);
            }else{
                $activer_image_mobile_en_bas = "0";
            }

            foreach ($categories as $cd) {
                if ($cd->cat_name == 'gare') {
                    $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                    $backlinks .= '<div class="good-deal">';
                        $backlinks .= 'Liens utiles:';
                        $backlinks .= ' <a href="https://www.thetrainline.com/fr" title="Tous vos billets de train et de bus, au même endroit" target=_blank>Trainline</a>';
                        $backlinks .= ' | <a href="https://garedefrance.fr" title="Annuaire des GARES SNCF en France" target=_blank>Gare de France</a>';
                        $backlinks .= ' | <a href="https://garebelgique.be" title="Annuaire des GARES en Belgique" target=_blank>Gare de Belgique</a>';
                    $backlinks .= '</div>';
                    $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="96"/>';
                }
                if ($cd->cat_name == 'gare-sncf') {
                    $second_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/sncf.jpg', __FILE__).'" alt="call service" width="275" height="277" />';
                    $third_featured_image = '<img class="alignnone size-full lazyloaded" src="'.plugins_url('img/cartouche-sncf.png', __FILE__).'" alt="cartouche" width="350" height="96"/>';
                }
            }

            if($activer_image_mobile_en_haut !== "1"){
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

            if($activer_image_mobile_en_bas !== "1"){
                $custom_content .= '<div class="container-fluid fixed-bottom d-block d-sm-none text-center align-center ">';
                $custom_content .= '<figure>';
                $custom_content .= '<a href="tel:'.$number_click_to_call.'">';
                $custom_content .= $third_featured_image;
                $custom_content .= '</a>';
                $custom_content .= '</figure>';
                $custom_content .= '</div>';
            }
    
            $custom_content .= $html;

            // links for seo
            $custom_content .= $backlinks;
    
            return $custom_content;

        }

        return $html;
    }

    return $content;
}

add_filter('the_content', 'popup_after_title_in_mobile');
//init the meta box
add_action('after_setup_theme', 'custom_postimage_setup');


function custom_postimage_setup()
{
    add_action('add_meta_boxes', 'custom_postimage_meta_box');
    add_action('save_post', 'custom_postimage_meta_box_save');
}

function custom_postimage_meta_box()
{

    //on which post types should the box appear?
    $post_types = array('post', 'page');
    foreach ($post_types as $pt) {
        add_meta_box('custom_postimage_meta_box', __('Image popup mobile', 'yourdomain'), 'custom_postimage_meta_box_func', $pt, 'side', 'low');
    }
}

function custom_postimage_meta_box_func($post)
{

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image', 'third_featured_image');

    foreach ($meta_keys as $meta_key) {
        $image_meta_val = get_post_meta($post->ID, $meta_key, true);
?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val != '' ? wp_get_attachment_image_src($image_meta_val)[0] : ''); ?>" style="width:100%;display: <?php echo ($image_meta_val != '' ? 'block' : 'none'); ?>" alt="">
            <a class="addimage button" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('Téléverser', 'yourdomain'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val != '' ? 'block' : 'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('Effacer image', 'yourdomain'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php
    }
    ?>
    <script>
        function custom_postimage_add_image(key) {

            var $wrapper = jQuery('#' + key + '_wrapper');

            custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('select image', 'yourdomain'); ?>',
                button: {
                    text: '<?php _e('select image', 'yourdomain'); ?>'
                },
                multiple: false
            });
            custom_postimage_uploader.on('select', function() {

                var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
                var img_url = attachment['url'];
                var img_id = attachment['id'];
                $wrapper.find('input#' + key).val(img_id);
                $wrapper.find('img').attr('src', img_url);
                $wrapper.find('img').show();
                $wrapper.find('a.removeimage').show();
            });
            custom_postimage_uploader.on('open', function() {
                var selection = custom_postimage_uploader.state().get('selection');
                var selected = $wrapper.find('input#' + key).val();
                if (selected) {
                    selection.add(wp.media.attachment(selected));
                }
            });
            custom_postimage_uploader.open();
            return false;
        }

        function custom_postimage_remove_image(key) {
            var $wrapper = jQuery('#' + key + '_wrapper');
            $wrapper.find('input#' + key).val('');
            $wrapper.find('img').hide();
            $wrapper.find('a.removeimage').hide();
            return false;
        }
    </script>
<?php
    wp_nonce_field('custom_postimage_meta_box', 'custom_postimage_meta_box_nonce');
}

function custom_postimage_meta_box_save($post_id)
{

    if (!current_user_can('edit_posts', $post_id)) {
        return 'not permitted';
    }

    if (isset($_POST['custom_postimage_meta_box_nonce']) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'], 'custom_postimage_meta_box')) {

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image', 'third_featured_image');
        foreach ($meta_keys as $meta_key) {
            if (isset($_POST[$meta_key]) && intval($_POST[$meta_key]) != '') {
                update_post_meta($post_id, $meta_key, intval($_POST[$meta_key]));
            } else {
                update_post_meta($post_id, $meta_key, '');
            }
        }
    }
}

