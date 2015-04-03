lib-wp-post-activity
================

## Description
Wordpress Post Activity library for managing custom activity ( notes, events ) for specific posts

##Examples

#### UI Initialization
```php

/**
 * Maybe inititialize Activity UI
 * Note: it should be called on or before 'init' action hook.
 */
\UsabilityDynamics\PA\Post_Activity::init();

/**
 * Add Activity meta box for specific 'post' and 'page' post types.
 *
 */
add_filter( 'wp_post_activity_posts', function( $posts ) {
  // Add 'Activity' Meta Box on Edit Post page
  $posts[] = 'post';
  // Add 'Activity' Meta Box on Edit Page page
  $posts[] = 'page';
  return $posts;
} );

```

#### API
```php

/**
 * Add Activity record.
 *
 * Note: there are two statuses: Event (event) and Note (note)
 * Record will be set as 'event' if user_id is not passed.
 * If user_id is passed, status will be set as 'note'
 *
 * @return bool|WP_Error
 */
UsabilityDynamics\PA\API::add( array(
  // Required. Post ID which activity Event belongs to
  'post_id' => false,
  // Required. Content of Event. Supports HTML.
  'content' => '',  
  // Optional. Author ( owner ) of event. 
  'user_id' => false,
) );

/**
 * Get the list of all Activity records for passed post_id_
 *
 * Uses: get_comments()
 * See: https://codex.wordpress.org/Function_Reference/get_comments
 *
 * @param int $args Required
 * @param array $args Optional. Equal to get_comments() arguments
 * @return array The list of objects.
 */
UsabilityDynamics\PA\API::get( $post_id, $args );

/**
 * Delete Activity record.
 *
 * Note: comment ID or comment object can be passed
 * Note: user must be owner of record or be administrator.
 *
 * @param integer|object $comment
 * @return bool|WP_Error
 */
UsabilityDynamics\PA\API::delete( $comment );

```

## License

(The MIT License)

Copyright (c) 2013 Usability Dynamics, Inc. &lt;info@usabilitydynamics.com&gt;

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.