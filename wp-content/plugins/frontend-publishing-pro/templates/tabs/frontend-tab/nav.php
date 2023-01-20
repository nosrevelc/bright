<?php if (!defined('WPINC')) die; ?>
<div class="post-list-pagination">
	<?php if($newer_exist): ?>
		<a class="post-list-pagination-link post-list-pagination-link-newer" href="<?php printf($page_url_format, $page - 1); ?>" ><i class="fa fa-arrow-left"></i> <?php echo __('Newer Posts', 'frontend-publishing-pro') ?></a>
	<?php endif; ?>

	<?php if($older_exist): ?>
		<a class="post-list-pagination-link post-list-pagination-link-older" href="<?php printf($page_url_format, $page + 1); ?>" ><?php echo __('Older Posts', 'frontend-publishing-pro') ?> <i class="fa fa-arrow-right"></i></a>
	<?php endif; ?>
	<div style="clear: both;"></div>
</div>
