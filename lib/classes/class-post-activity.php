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
       * The list of screens where Activity meta box is being rendered.
       * @var array
       */
      var $screens = array();

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
       * Returns Singleton object
       * Maybe Initialize plugin
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
        if( !empty( $this->screens ) && is_array( $this->screens ) ) {
          foreach ( $this->screens as $screen => $args ) {
            add_meta_box( 'wp_post_activity',  __( 'Activity' ),  array( $this, 'render_meta_box' ),  $screen, 'normal' );
          }
        }
      }

      /**
       *
       */
      public function render_meta_box() {
        $screen = get_current_screen();
        $fields = !empty( $this->screens[ $screen->id ] ) ? $this->screens[ $screen->id ] : array();
        Utility::get_template( 'meta-box', array( 'fields' => $fields ), true );
      }

      /**
       *
       */
      public function add_screen( $screen, $fields = array() ) {
        if( did_action( 'add_meta_boxes' ) ) {
          _doing_it_wrong( __FUNCTION__, __( 'method must be called before \'add_meta_boxes\' action.' ), '1.0' );
        }
        if( !is_array( $this->screens ) ) {
          $this->screens = array();
        }
        if( !is_array( $fields ) ) {
          $fields = array();
        }

        foreach( $fields as $k => $v ) {
          $fields[$k] = wp_parse_args( $v, array(
            'name' => 'No Name',
            'type' => 'text',
            'options' => array(),
          ) );
        }
        $this->screens[$screen] = $fields;
      }

    }
  
  }
  
}