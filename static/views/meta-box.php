<?php
/**
 * Meta Box template
 */
global $post;

/**
 * Load WP core classes.
 */
if( !class_exists( '\WP_Posts_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );
}

wp_enqueue_script( 'wppa-edit-activity', plugin_dir_url( __DIR__ ) . 'js/edit-activity.js' );
wp_localize_script( 'wppa-edit-activity', '__wp_pa_vars', array(
  'admin_ajax' => admin_url( 'admin-ajax.php' ),
) );

wp_enqueue_style( 'wppa-edit-activity', plugin_dir_url( __DIR__ ) . 'styles/edit-activity.css' );

$comments = \UsabilityDynamics\PA\API::get( $post->ID );

?>
<div id="wp_pa_box">
  <a href="#add_note" class="add-note button button-primary button-large"><?php _e( 'Add Note' ); ?></a>
  <div class="activity-form" style="display:none;">
    <input type="hidden" id="pa_user_id" value="<?php echo get_current_user_id(); ?>" />
    <input type="hidden" id="pa_post_id" value="<?php echo $post->ID; ?>" />
    <div class="textarea-container">
      <?php
      $quicktags_settings = array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' );
      wp_editor( '', 'pa_content', array( 'media_buttons' => false, 'tinymce' => false, 'quicktags' => $quicktags_settings ) );
      ?>
    </div>
    <div class="actions">
      <ul>
        <li class="submit"><a href="#submit" class="submit-note button button-primary button-large"><?php _e( 'Submit' ); ?></a></li>
        <li class="custom_meta">
          <?php foreach( $fields as $key => $field ) : ?>
            <label class="meta-<?php $key ?>">
              <span><?php echo $field['name']; ?><span class="delimiter">:</span></span>
              <?php switch( $field[ 'type' ] ) {
                case 'text':
                  ?><input type="text" value="" name="<?php echo $key; ?>" /><?php
                  break;
                case 'select':
                  ?><select name="<?php echo $key; ?>">
                    <?php if( !empty( $field['options'] ) && is_array( $field['options'] ) ) foreach( $field['options'] as $i => $v ) : ?>
                      <option value="<?php echo $i ?>"><?php echo $v ?></option>
                    <?php endforeach; ?>
                  </select><?php
                  break;
              } ?>
            </label>
          <?php endforeach; ?>
        </li>
        <li class="cancel"><a href="#cancel" class="cancel button button-primary button-large"><?php _e( 'Cancel' ); ?></a></li>
      </ul>
    </div>
  </div>
  <ul class="activity-list">
    <?php if( !empty( $comments ) ) foreach( $comments as $_comment ) : global $comment;  $comment = $_comment; ?>
      <li class="activity-item"><?php \UsabilityDynamics\PA\Utility::get_template( 'activity', array(), true ); ?></li>
    <?php endforeach; ?>
  </ul>
</div>