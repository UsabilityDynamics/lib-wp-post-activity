<?php
/**
 * Meta Box template
 */

global $comment;

if( !is_object( $comment ) ) {
  return;
}
?>
<div class="activity-item" data-id="<?php echo get_comment_ID(); ?>">
  <div class="header">
    <div class="details">
      <span class="datetime"><?php comment_date( get_option( 'date_format' ) ); ?> ( <?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ); ?> <?php _e( 'ago' ); ?> )</span>
      <?php if( get_comment_author() && !empty( $comment->user_id ) ) : ?>
        <span class="author"><?php _e( 'by' ); ?> <?php comment_author(); ?></span>
      <?php endif; ?>
      <span class="delimiter">|</span>
      <span class="author"><?php _e( 'Status' ); ?>: <?php echo ucfirst( $comment->comment_approved ); ?></span>
    </div>
    <div class="actions">
      <?php if( get_current_user_id() == $comment->user_id || current_user_can( 'manage_options' ) ) : ?>
        <a class="button delete-note" data-id="<?php echo get_comment_ID(); ?>" href="#delete"><?php _e( 'Delete' ); ?></a>
      <?php endif; ?>
    </div>
  </div>
  <div class="content"><?php comment_text(); ?></div>
</div>
