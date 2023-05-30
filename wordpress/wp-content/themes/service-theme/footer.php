<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package servicer
 */
	$footer_style   = servicer_get_options( 'footer_style' );
	$footer_copyright   = servicer_get_options( 'footer_copyright' );
	$footer_style_class = '';
	if( $footer_style == '2' ) :
		$footer_style_class = 'footer-style-two';
	endif;
?>
	
	 

    <footer class="footer-area <?php echo esc_attr( $footer_style_class ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="footer-content">
						<div class="footer-copyright">
							<p>
								
									&copy;
									<?php
									echo esc_html(
										date_i18n(
										/* translators: Copyright date format, see https://www.php.net/date */
											_x( 'Y', 'copyright date format', 'servicer' )
										)
									);
									?>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
									
								
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>	
	
	<?php 
		do_action('servicer_back_to_top_ready');
	?>
<?php wp_footer(); ?>
</body>
</html>
