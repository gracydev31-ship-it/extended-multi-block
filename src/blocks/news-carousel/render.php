<?php
if (!defined('ABSPATH')) {
	exit;
}

$posts = get_posts([
	'post_type'      => 'post',
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
]);

if (empty($posts)) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'news-carousel']); ?>
	data-wp-interactive="runpartner/news-carousel"
	data-wp-init="callbacks.initCarousel"
	data-wp-on--mouseenter="actions.pauseCarousel"
	data-wp-on--mouseleave="actions.resumeCarousel">
	<button class="news-carousel-arrow prev"
		data-wp-on--click="actions.carouselPrev"
		aria-label="<?php esc_attr_e('Previous slide', 'extended-multi-block'); ?>">‹</button>
	<div class="news-carousel-track">
		<?php foreach ($posts as $post) :
			setup_postdata($post);
			$permalink  = get_permalink($post);
			$title      = get_the_title($post);
			$thumb_id   = get_post_thumbnail_id($post);
			$thumb_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium_large') : '';
			$categories = get_the_category($post);
		?>
		<a href="<?php echo esc_url($permalink); ?>" class="news-carousel-card">
			<?php if (!empty($thumb_url)) : ?>
			<div class="news-carousel-card-image">
				<img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
			</div>
			<?php endif; ?>
			<div class="news-carousel-card-body">
				<?php if (!empty($categories)) : ?>
				<div class="news-carousel-card-terms">
					<?php foreach ($categories as $i => $cat) :
						if ($i >= 2) break; ?>
						<span class="news-carousel-card-pill"><?php echo esc_html($cat->name); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<h3 class="news-carousel-card-title"><?php echo esc_html($title); ?></h3>
				<span class="news-carousel-card-date"><?php echo esc_html(get_the_date('', $post)); ?></span>
			</div>
		</a>
		<?php endforeach;
		wp_reset_postdata(); ?>
	</div>
	<button class="news-carousel-arrow next"
		data-wp-on--click="actions.carouselNext"
		aria-label="<?php esc_attr_e('Next slide', 'extended-multi-block'); ?>">›</button>
</div>
