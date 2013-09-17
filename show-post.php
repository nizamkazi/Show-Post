<?php
/*
Plugin Name: Show Post Content
Description: Easiest way to show content of your Post or Page out of loop. This plugin also enable to display ID of your post or page at admin panel.
Version: 1.0
Author: Nizam Kazi | ArtLog DiGi
Author URI: http://www.artlogidigi.com/
License: GPL2
*/
include('show-post-using-id-functions.php');
class show_post_plugin extends WP_Widget {

// constructor
    function show_post_plugin() {
        parent::WP_Widget(false, $name = __('Show Post Using ID', 'show_post_plugin') );
    }
	
// widget form creation
function form($instance) {
	// Check values
	$my_id = isset ( $instance['my_id'] ) ? esc_attr( $instance['my_id'] ) : '';
	$show_title = isset ( $instance['show_title'] ) ? esc_attr( $instance['show_title'] ) : '';
	 ?>

	<p style="overflow: hidden;">
	<label for="<?php echo $this->get_field_id('my_id'); ?>"><?php _e('Post ID:', 'show_post_plugin'); ?></label>
	<select id="<?php echo $this->get_field_id('my_id'); ?>" name="<?php echo $this->get_field_name('my_id'); ?>" class="comment-select">
		<option value="">Select Post</option><?php
			global $post;
			$args = array( 'numberposts' => -1);
			$posts = get_posts($args);
			foreach( $posts as $post ) { setup_postdata( $post ); ?>
				<option <?php selected( $my_id, $post->ID ); ?> value="<? echo $post->ID; ?>"><?php echo $post->post_title; ?></option><?php
			} ?>
	</select>
    </p>
    <p style="overflow: hidden;">
        <label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title' ); ?>: </label>
        <input role="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" id="<?php echo $this->get_field_id( 'show_title' ); ?>" <?php checked( $show_title ); ?> />
    </p>
<?php
}
// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['my_id'] = strip_tags($new_instance['my_id']);
	  $instance['show_title'] = !empty( $new_instance['show_title'] ) ? 1 : 0;	  
	  
	  
     return $instance;
}
// display widget
function widget($args, $instance) {
   extract( $args );
   // these are the widget options
	$my_id = empty( $instance['my_id'] ) ? '' : apply_filters( 'widget_title', $instance['my_id'] );
	$show_title = isset( $instance['show_title'] ) ? $instance['show_title'] : true;

	echo $before_title . $title . $after_title;
   // Display the widget
   echo '<div class="show_post_plugin_box">';

   // Check if my_id is set
			$post_id = $my_id;
			$queried_post = get_post($post_id);
			$title = $queried_post->post_title;
			$thumbnail = get_the_post_thumbnail($my_id);
			$excerpt = get_excerpt_by_id($my_id);
			echo "<div class=\"post-thumbnail\">";
			echo "<a href=".get_permalink($my_id)."><div class=\"thumbnail\">".$thumbnail."</div></div></a>";
			echo "<div class=\"post-wrapper\">";
			if ( $show_title ) {
				echo "<h1 class=\"tab-post-title\"><a href=".get_permalink($my_id).">".$title."</a></h1>";
			}
			echo "<p>".$excerpt."</p>";
			echo "</div>";
			echo $ratings = ratings_list($my_id);
			echo "<div class=\"post-button\"><a class=\"effect\" target=\"_blank\" rel=\"nofollow\" href=".get_permalink($my_id)."><img class=\"image\" src=".get_stylesheet_directory_uri()."/images/button-top-review.jpg /><img class=\"hover\" src=".get_stylesheet_directory_uri()."/images/button-top-review-hover.jpg /></a></div>";
   echo '</div>';
   echo $after_widget;
}}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("show_post_plugin");'));
?>
