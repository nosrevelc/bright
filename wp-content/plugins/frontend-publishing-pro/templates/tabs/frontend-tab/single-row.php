<?php if (!defined('WPINC')) die; ?>

<div class="post-list-table-row">
	<div class="post-list-post-title-cell">
		<i class="fa fa-caret-down post-list-row-expand-icon"></i>
		<?php the_title(); ?>
	</div>
	<?php if($show_link_col){ ?>
		<div class="post-list-action-cell post-list-link-cell">
			<?php if($posts_not_live): ?>
				<a title="<?php echo __('Preview', 'frontend-publishing-pro'); ?>" target="_blank" href="<?php printf($preview_url_format, get_the_ID(), wp_create_nonce(sprintf($preview_nonce_action_format, get_the_ID()))); ?>"><i class="fa fa-eye"></i></a>
			<?php else: ?>
				<a title="<?php echo __('View', 'frontend-publishing-pro'); ?>" href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
			<?php endif; ?>
		</div>
	<?php } ?>
	<?php if($show_edit_col): ?>
		<div class="post-list-action-cell post-list-edit-cell">
			<a title="<?php echo __('Edit', 'frontend-publishing-pro'); ?>" href="<?php printf($edit_url_format, get_the_ID()); ?>"><i class="fa fa-pencil"></i></a>
		</div>
	<?php endif; ?>
	<?php if($show_delete_col): ?>
		<div class="post-list-action-cell post-list-delete-cell">
			<a title="<?php echo __('Delete', 'frontend-publishing-pro'); ?>" href="<?php printf($delete_url_format, get_the_ID(), wp_create_nonce(sprintf($deletion_nonce_action_format, get_the_ID()))); ?>"><i class="fa fa-trash"></i></a>
		</div>
	<?php endif; ?>
</div>