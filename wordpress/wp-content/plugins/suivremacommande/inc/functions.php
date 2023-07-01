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