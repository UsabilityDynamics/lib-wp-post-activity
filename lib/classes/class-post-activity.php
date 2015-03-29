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
       * Renders template part.
       *
       */
      public function get_template($name, $data = array()) {
        if (is_array($data)) {
          extract($data);
        }
        $path = apply_filters( 'wp-post-activity-template-path', dirname( '__DIR__' ) . '/static/views', $name, $data ) . '/' . $name . '.php';
        if (file_exists($path)) {
          include( $path );
        }
      }

      /**
       * Register our Activity post type
       */
      public function register_post_type() {

        $labels = array(
          'name'               => _x( 'Activities', 'post type general name' ),
          'singular_name'      => _x( 'Activity', 'post type singular name' ),
          'menu_name'          => _x( 'Activities', 'admin menu' ),
          'name_admin_bar'     => _x( 'Activity', 'add new on admin bar' ),
          'add_new'            => _x( 'Add New', 'activity' ),
          'add_new_item'       => __( 'Add New Activity' ),
          'new_item'           => __( 'New Activity' ),
          'edit_item'          => __( 'Edit Activity' ),
          'view_item'          => __( 'View Activity' ),
          'all_items'          => __( 'All Activities' ),
          'search_items'       => __( 'Search Activities' ),
          'parent_item_colon'  => __( 'Parent Activities:' ),
          'not_found'          => __( 'No activities found.' ),
          'not_found_in_trash' => __( 'No activities found in Trash.' )
        );

        $args = array(
          'labels'             => $labels,
          'public'             => false,
          'publicly_queryable' => false,
          'show_ui'            => false,
          'show_in_menu'       => false,
          'query_var'          => false,
          'rewrite'            => array( 'slug' => 'post_activity' ),
          'capability_type'    => 'post',
          'has_archive'        => false,
          'hierarchical'       => true,
          'menu_position'      => null,
          'supports'           => array( 'title', 'editor', 'author', 'excerpt' )
        );

        register_post_type( 'post_activity', $args );

      }

      /**
       *
       */
      public function add_meta_boxes() {

        $posts = apply_filter( 'wp-post-activity-posts', array() );

        foreach ( $screens as $screen ) {
          add_meta_box( 'wp_post_activity',  __( 'Activity' ),  array( $this, 'render_meta_box' ),  $screen );
        }

      }

      /**
       *
       */
      public function render_meta_box() {
        $this->get_template( 'meta-box' );
      }

    }
  
  }
  
}