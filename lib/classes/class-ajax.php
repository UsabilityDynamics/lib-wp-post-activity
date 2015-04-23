<?php
/**
 * Wordpress Ajax Handler
 *
 * @since 1.0.0
 * @author peshkov@UD
 */
namespace UsabilityDynamics\PA {

  if( !class_exists( 'UsabilityDynamics\PA\Ajax' ) ) {

    final class Ajax {

      /**
       * The list of wp_ajax_{name} actions
       *
       * @var array
       */
      var $actions = array(
        'pa_add_note',
        'pa_delete_note',
      );

      /**
       * The list of wp_ajax_nopriv_{name} actions
       *
       * @var array
       */
      var $nopriv = array();

      /**
       * Init AJAX actions
       *
       * @author peshkov@UD
       */
      public function __construct(){

        /**
         * Maybe extend the list by external modules.
         */
        $this->actions = apply_filters( 'wp_pa_ajax_actions', $this->actions );
        $this->nopriv = apply_filters( 'wp_pa_ajax_nopriv', $this->nopriv );

        foreach( $this->actions as $action ) {
          add_action( 'wp_ajax_' . $action, array( $this, 'request' ) );
        }

        foreach( $this->nopriv as $action ) {
          add_action( 'wp_ajax_nopriv_' . $action, array( $this, 'request' ) );
        }

      }

      /**
       * Handles AJAX request
       *
       * @author peshkov@UD
       */
      public function request() {

        $response = array(
          'message' => '',
          'html' => '',
        );

        try{

          $action = $_REQUEST[ 'action' ];
          /** Determine if the current class has the method to handle request */
          if( is_callable( array( $this, 'action_'. $action ) ) ) {
            $response = call_user_func_array( array( $this, 'action_' . $action ), array( $_REQUEST ) );
          }
          /** Determine if external function exists to handle request */
          elseif ( is_callable( 'action_' . $action ) ) {
            $response = call_user_func_array( $action, array( $_REQUEST ) );
          }
          elseif ( is_callable( $action ) ) {
            $response = call_user_func_array( $action, array( $_REQUEST ) );
          }
          /** Oops! */
          else {
            throw new \Exception( __( 'Incorrect Request' ) );
          }

        } catch( \Exception $e ) {
          wp_send_json_error( $e->getMessage() );
        }

        wp_send_json_success( $response );

      }

      /**
       * Sends json.
       * Use it if custom response should be sent.
       *
       * @param array $response
       * @author peshkov@UD
       */
      public function send_json( $response ) {
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        echo wp_json_encode( $response );
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
          wp_die();
        } else {
          die;
        }
      }

      /**
       *  AJAX delete Event
       */
      public function action_pa_delete_note( $args ) {

        if( empty( $args[ 'comment_id' ] ) ) {
          throw new \Exception( 'Empty Activity ID' );
        }

        $r = API::delete( $args[ 'comment_id' ] );

        if( is_wp_error( $r ) ) {
          throw new \Exception( $r->get_error_message() );
        }

        return array(
          'deleted_comment_id' => $args[ 'comment_id' ],
        );

      }

      /**
       * AJAX add Event ( Note )
       */
        public function action_pa_add_note( $args ) {
        global $comment;

        /** Be sure it's the same person */
        if( empty( $args[ 'user_id' ] ) || $args[ 'user_id' ] != get_current_user_id() ) {
          throw new \Exception( 'Invalid User ID' );
        }

        if( empty( $args[ 'post_id' ] ) ) {
          throw new \Exception( 'Invalid Post ID' );
        }

        if( !current_user_can( 'edit_post', $args[ 'post_id' ] ) ) {
          throw new \Exception( 'You do not have enough permissions to add notes for current post.' );
        }

        if( empty( $args[ 'content' ] ) ) {
          throw new \Exception( 'The note must not be empty.' );
        }

        $id = API::add( array(
          'post_id' => $args[ 'post_id' ],
          'user_id' => get_current_user_id(),
          'content' => $args[ 'content' ],
        ) );

        if( !$id || is_wp_error( $id ) ) {
          throw new \Exception( 'Something went wrong on trying to add note.' );
        }

        // Custom meta
        if(!empty( $extra ) && is_array($extra)){
          foreach( $extra as $k => $v ) {
            if( !add_comment_meta( $id, $k, $v, true ) ) {
              throw new \Exception( 'Activity has been saved, but there was a failure on trying to save custom meta data.' );
            }
          }
        }

        // Can be used for custom logic.
        do_action( 'pa::after_add_note', $id, $args );

        $comment = get_comment( $id );

        return array(
          'comment_id' => $id,
          'html' => Utility::get_template( 'activity' ),
        );
      }

    }

  }

}
