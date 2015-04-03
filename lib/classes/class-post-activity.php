<?php
/**
 * Post Activity class
 *
 * @namespace UsabilityDynamics
 * @author peshkov@UD
 */
namespace UsabilityDynamics\PA {

  if( !class_exists( 'UsabilityDynamics\PA\Post_Activity' ) ) {

    /**
     * Post_Activity
     *
     * @class Post_Activity
     * @author: peshkov@UD
     */
    class Post_Activity {

      /**
       * Constructor
       *
       * Protected constructor to prevent creating a new instance of the
       * *Singleton* via the `new` operator from outside of this class.
       *
       * @author peshkov@UD
       */
      protected function __construct() {

        /** Init AJAX handler */
        new Ajax();

        add_action( 'init', array( $this, 'register_post_type' ) );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

      }

      /**
       * Private clone method to prevent cloning of the instance of the
       * *Singleton* instance.
       *
       * @return void
       */
      private function __clone() {}

      /**
       * Private unserialize method to prevent unserializing of the *Singleton*
       * instance.
       *
       * @return void
       */
      private function __wakeup() {}

      /**
       * Returns the *Singleton* instance of this class.
       *
       * @staticvar Singleton $instance The *Singleton* instances of this class.
       *
       * @return Singleton The *Singleton* instance.
       */
      public static function get_instance() {
        static $instance = null;
        if (null === $instance) {
          $instance = new static();
        }
        return $instance;
      }

      /**
       *
       */
      public function add_meta_boxes() {
        $screens = apply_filters( 'wp_post_activity_posts', array() );
        foreach ( $screens as $screen ) {
          add_meta_box( 'wp_post_activity',  __( 'Activity' ),  array( $this, 'render_meta_box' ),  $screen, 'normal' );
        }
      }

      /**
       *
       */
      public function render_meta_box() {
        Utility::get_template( 'meta-box', array(), true );
      }

    }
  
  }
  
}