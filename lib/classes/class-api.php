<?php
/**
 * API
 *
 * @since 1.0.0
 * @author peshkov@UD
 */
namespace UsabilityDynamics\PA {

  if( !class_exists( 'UsabilityDynamics\PA\API' ) ) {

    class API {

      /**
       * Add Activity's Event
       *
       */
      static public function add( $args ) {

        $args = wp_parse_args( $args, array(
          'post_id' => false,
          'user_id' => false,
          'content' => '',
        ) );

        try {

          /* Try to get post id */
          if( empty( $args[ 'post_id' ] ) ) {
            global $post;
            if( !empty( $post ) && isset( $post->ID ) ) {
              $args['post_id'] = $post->ID;
            } else {
              throw new \Exception( 'Post ID is undefined.' );
            }
          }

          /* Determine if post exists */
          if( !get_post( $args[ 'post_id' ] ) ) {
            throw new \Exception( 'Invalid Post ID.' );
          }

          $args['content'] = trim( $args['content'] );
          if( empty( $args['content'] ) ) {
            throw new \Exception( 'Empty Content.' );
          }

          $data = array(
            'comment_post_ID' => $args[ 'post_id' ],
            'comment_author' => '',
            'comment_author_email' => '',
            'comment_author_url' => '',
            'comment_content' => $args[ 'content' ],
            'comment_type' => 'post_activity',
            'comment_parent' => 0,
            'user_id' => '',
            'comment_date' => current_time('mysql'),
          );

          if( !empty( $args[ 'user_id' ] ) ) {

            $user = get_user_by( 'id', $args[ 'user_id' ] );
            if( !$user ) {
              throw new \Exception( 'Invalid User ID.' );
            }

            $data[ 'user_id' ] = $args[ 'user_id' ];
            $data[ 'comment_author' ] = $user->display_name;
            $data[ 'comment_author_email' ] = $user->display_name;
            $data[ 'comment_author_url' ] = $user->display_name;
            $data[ 'comment_approved' ] = 'note';

          } else {

            $data[ 'comment_approved' ] = 'event';

          }

          $id = wp_insert_comment( $data );

          if( !$id ) {
            throw new \Exception( 'Something went wrong on trying to add activity event.' );
          }

        } catch ( \Exception $e ) {

          return new \WP_Error( 'post_activity', $e->getMessage() );

        }

        return $id;

      }

      /**
       * Returns Activity for passed post ID.
       *
       * @return array
       */
      static public function get( $post_id, $args = array() ) {

        $args = wp_parse_args( $args, array(
          'type__in' => array( 'post_activity' ),
          'status' => array( 'note', 'event' ),
        ) );

        $args[ 'post_id' ] = $post_id;

        return get_comments( $args );

      }

      /**
       * Delete Activity record
       *
       */
      static public function delete( $comment ) {

        if( !is_object( $comment ) ) {
          $comment = get_comment( $comment );
        }

        try {

          if( !$comment || !is_object( $comment ) ) {
            throw new \Exception( 'Invalid Activity ID' );
          }

          /** Determine if user has permissions to remove activity event */
          if( get_current_user_id() !== $comment->user_id && !current_user_can( 'manage_options' ) ) {
            return false;
          }

          if( !wp_delete_comment( $comment->comment_ID, true ) ) {
            throw new \Exception( 'Something went wrong on trying to remove activity event.' );
          }

        } catch ( \Exception $e ) {

          return new \WP_Error( 'post_activity', $e->getMessage() );

        }

        return true;

      }

    }

  }

}
