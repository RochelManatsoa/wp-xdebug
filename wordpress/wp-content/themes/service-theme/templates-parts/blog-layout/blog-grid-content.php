<?php
	$servicer_post_thumb_class = 'no-post-thumb';
	if (has_post_thumbnail()) :
		$servicer_post_thumb_class = '';
	endif;

	$is_sticky_class = '';
	if (is_sticky()) :
		$is_sticky_class = 'kitbug-sticky-post-area';
	endif;
?>
<div class="col-md-6 col-lg-4">
	<div class="blog-item <?php echo esc_attr( $servicer_post_thumb_class . ' ' . $is_sticky_class ); ?>">
		<div class="img-blog">
			<?php servicer_post_thumbnail(); ?>
		</div>
		<div class="text-blog">
			<div class="time-and-tag">
				<span class="time"><?php echo get_the_date(); ?></span>
				<?php servicer_category_list(); ?>
			</div>
			<h5 class="title-blog"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h5>
			<?php the_excerpt(); ?>
			<a href="<?php echo esc_url(get_permalink()); ?>" class="blog-open"><?php esc_html_e('Lire plus','servicer');?> <i class="fas fa-arrow-right"></i></a>
		</div>
	</div>
</div>