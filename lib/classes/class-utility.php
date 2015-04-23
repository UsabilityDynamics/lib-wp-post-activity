<?php
/**
 * Utility
 *
 * @since 1.0.0
 * @author peshkov@UD
 */
namespace UsabilityDynamics\PA {

  if( !class_exists( 'UsabilityDynamics\PA\Utility' ) ) {

    class Utility {

      /**
       * Renders template part.
       *
       */
      static public function get_template($name, $data = array(), $output = false) {
        if (is_array($data)) {
          extract($data);
        }
        $template = '';
        $path = apply_filters( 'wp_post_activity_template_path', dirname( dirname( __DIR__ ) ) . '/static/views', $name, $data ) . '/' . $name . '.php';
        if (file_exists($path)) {
          ob_start();
          include( $path );
          $template .= ob_get_clean();
        }
        if( $output ) {
          echo $template;
        } else {
          return $template;
        }
      }

    }

  }

}
