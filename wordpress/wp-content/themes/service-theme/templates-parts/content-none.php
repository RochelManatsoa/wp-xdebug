<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package servicer
 */
?>
<section class="no-results-found-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="no-results-found">
					<?php
						if ( is_home() && current_user_can( 'publish_posts' ) ) :
							printf(
								'<p>' . wp_kses(
									__( 'Prêt à poster votre première publication? <a href="%1$s">Commencez ici</a>.', 'servicer' ),
									array(
										'a' => array(
											'href' => array(),
										),
									)
								) . '</p>',
								esc_url( admin_url( 'post-new.php' ) )
							);
						elseif ( is_search() ) :
							?>
							<p class="no-found-text"><?php esc_html_e( 'Désolé, mais rien ne correspond à vos termes de recherche. Veuillez réessayer avec d\'autres mots-clés.', 'servicer' ); ?></p>
								<div class="nothing-found-search">
									<?php
										get_search_form();
									?>
								</div>
							<?php
						else :
							?>
							<p class="no-found-text"><?php esc_html_e( 'Il semble que nous ne puissions pas trouver ce que vous cherchez. Peut-être que la recherche peut aider.', 'servicer' ); ?></p>
								<div class="nothing-found-search">
								<?php
									get_search_form();
								?>
								</div>
							<?php
						endif;
					?>
				</div>
			</div>
		</div>
	</div>
</section>
