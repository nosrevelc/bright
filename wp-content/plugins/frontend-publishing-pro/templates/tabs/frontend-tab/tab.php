<?php if (!defined('WPINC')) die; ?>
<?php
	if (!$posts->have_posts()) {
		echo __("Nothing found.", 'frontend-publishing-pro');
		return;
	}
?>
<div class="post-list-table">
	<?php while($posts->have_posts()): ?>
		<?php $posts->the_post(); ?>
		<?php $this->render_template( WPFEPP_TAB_TEMPLATES_DIR . 'frontend-tab/single-row.php', $args ); ?>
	<?php endwhile; ?>
</div>
<?php $this->render_template( WPFEPP_TAB_TEMPLATES_DIR . 'frontend-tab/nav.php', $args ); ?>