<?php
/*
Plugin Name: Numéro Service Client Belgique AWM
Description: Plugin pour le site numero-serviceclient.be [Requiert le thème twentyseventeen]. Ajoute un popup sur mobile. Résultat de recherche personnalisé.
Version: 2.1.2
Author: Nirina Rochel
*/

/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
define('NRD_PATH', plugin_dir_path(__FILE__));

require_once(NRD_PATH . "/inc/functions.php");
require_once(NRD_PATH . "/inc/simple_html_dom.php");

function popup_after_title_in_mobile( $content ) {

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

add_filter('the_content', 'popup_after_title_in_mobile');

/**
 * 
 * Add custom Meta Tag to header. 
 * For discover in google search console
 */
function custom_header_metadata()
{
  echo ' <meta name="robots" content="max-image-preview:large" />';
}
add_action('wp_head', 'custom_header_metadata');
add_filter('the_content', 'popup_after_title_in_mobile');


//init the meta box
add_action( 'after_setup_theme', 'custom_postimage_setup' );
function custom_postimage_setup(){
    add_action( 'add_meta_boxes', 'custom_postimage_meta_box' );
    add_action( 'save_post', 'custom_postimage_meta_box_save' );
}

function custom_postimage_meta_box(){

    //on which post types should the box appear?
    $post_types = array('post','page');
    foreach($post_types as $pt){
        add_meta_box('custom_postimage_meta_box',__( 'Image popup modile', 'yourdomain'),'custom_postimage_meta_box_func',$pt,'side','low');
    }
}

function custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image','third_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="width:100%;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('Téléverser','yourdomain'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('remove image','yourdomain'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
    function custom_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image','yourdomain'); ?>',
            button: {
                text: '<?php _e('select image','yourdomain'); ?>'
            },
            multiple: false
        });
        custom_postimage_uploader.on('select', function() {

            var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        custom_postimage_uploader.on('open', function(){
            var selection = custom_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        custom_postimage_uploader.open();
        return false;
    }

    function custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}
