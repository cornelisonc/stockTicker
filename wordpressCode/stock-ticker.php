<?php
/*
Plugin Name: Stock Ticker
Plugin URI: www.donthaveauriyet.com
Description: New and improved Wordpress stock ticker plugin
Version: 0.1
Author: Ceili Cornelison
Author URI: deltasys.com
*/


class StockTicker extends WP_Widget {

  function StockTicker() {
    $widget_ops = array('classname' => 'StockTicker', 'description' => 'Improved Stock Ticker' );
    $this->WP_Widget('StockTicker', 'Improved Stock Ticker', $widget_ops);
  }
 
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];

	?>
  	<p>
  		<label for="<?php echo $this->get_field_id('title'); ?>">Title: 
  			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  		</label>
  	</p>
	<?php

  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    echo "<h1>This is my new widget!</h1>";
 
    echo $after_widget;
  }
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("StockTicker");') );
?>
