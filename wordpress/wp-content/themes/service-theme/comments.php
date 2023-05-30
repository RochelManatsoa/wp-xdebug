<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package servicer
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
	<?php
		if ( have_comments() ) :
			$comment_close = '';
			if ( ! comments_open() ) :
				$comment_close = 'comment-close';
			endif;
			?>
			<div id="comments" class="comments-area-me <?php echo esc_attr($comment_close); ?>">
				<div class="group-title">
					<h4>
						<?php
							$servicer_comment_count = get_comments_number();
							if ('1' === $servicer_comment_count) {
								printf(
										esc_html__('One Comment', 'servicer')
								);
							} else {
								printf(
										esc_html(_nx('%1$s Comment', '%1$s Comments ', $servicer_comment_count, 'comments title', 'servicer'), 'servicer'), number_format_i18n($servicer_comment_count)
								);
							}
						?>
					</h4>
				</div>
				<?php
					if (have_comments()) :
						the_comments_navigation();
				?>
						<?php
							wp_list_comments( array(
								'style'      => 'div',
								'callback' => 'service_theme_comments',
								'short_ping' => true,
							) );
						?>
					<?php
						the_comments_navigation();
						if ( ! comments_open() ) :
							?>
							<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'servicer' ); ?></p>
							<?php
						endif;
					endif;
				?>
			</div>
	<?php
		endif; // Check for have_comments().
	$is_no_post_thumb = '';
	if (!have_comments()) {
		$is_no_post_thumb = 'no-comment';
	}
	if ( comments_open() ) :
	?>
<div class="comment-form-area default-form <?php echo esc_attr($is_no_post_thumb); ?>">
	<?php
		$user = wp_get_current_user();
		$servicer_user_identity = $user->display_name;
		$req = get_option('require_name_email');
		$aria_req = $req ? " aria-required='true'" : '';
		$formargs = array(
			'id_submit' => 'submit',
			'title_reply' => esc_html__('Laisser un commentaire', 'servicer'),
			'title_reply_to' => esc_html__('Laisser un commentaire à %s', 'servicer'),
			'cancel_reply_link' => esc_html__('Annuler', 'servicer'),
			'label_submit' => esc_html__('Envoyer', 'servicer'),
			'comment_notes_before' => '<p class="email-not-publish">' .
			esc_html__('Votre adresse mail ne sera pas publiée.', 'servicer') . ( $req ? '<span class="required">*</span>' : '' ) .
			'</p>',
			'submit_button' => '<button type="submit" name="%1$s" id="%2$s" class="%3$s comment-submit-btn">%4$s</button>',
			'comment_field' => '<textarea id="comment" name="comment" placeholder="' . esc_attr__('Ecrivez votre commentaire', 'servicer') . '"  >' .
			'</textarea>',
			'must_log_in' => '<div>' .
			sprintf(
					wp_kses(__('Vous devez vous <a href="%s">connécter</a> pour poster un commentaire.', 'servicer'), array('a' => array('href' => array()))), wp_login_url(apply_filters('the_permalink', get_permalink()))
			) . '</div>',
			'Connécteé(e) en tant que' => '<div class="logged-in-as">' .
			sprintf(
					wp_kses(__('Connecté(e) en tant que <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">Se déconnecter?</a>', 'servicer'), array('a' => array('href' => array()))), esc_url(admin_url('profile.php')), $servicer_user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink())), esc_attr__('Log out of this account', 'servicer')
			) . '</div>',
			
			'comment_notes_after' => '',
			'fields' => apply_filters('comment_form_default_fields', array(
				'author' =>
				'<div class="row clearfix"><div class="col-lg-6 col-md-6 col-sm-12  form-group">'
				. '<input id="author" class="input-authenticate" name="author" placeholder="' . esc_attr__('Votre nom *', 'servicer') . '" type="text" value="' . esc_attr($commenter['comment_author']) .
				'" size="30"' . $aria_req . ' /></div>',
				'email' =>
				'<div class="col-lg-6 col-md-6 col-sm-12 form-group">'
				. '<input id="email" class="input-authenticate" name="email" type="text"  placeholder="' . esc_attr__('Adresse éléctronique', 'servicer') . '" value="' . esc_attr($commenter['comment_author_email']) .
				'" size="30"' . $aria_req . ' /></div></div>'
					)
			),
		);
	?>
	<?php
		comment_form($formargs);
	?>
</div>
<?php endif; ?>