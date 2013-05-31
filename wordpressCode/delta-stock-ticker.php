<?php
/*
Plugin Name: Delta Stock Ticker
Plugin URI: http://deltasys.com/
Description: Easy and awesome stock ticker widget.
Version: 0.9
Author: Delta Systems
Author URI: http://deltasys.com
License: GPLv2 or later
*/

include_once("DeltaStockTicker.php");

$dst = new DeltaStockTicker();

add_action("widgets_init", array( $dst, 'register') );
add_action("widgets_init", array( $dst, 'load_scripts') );
register_activation_hook( __FILE__, array( $dst, 'activate'));
register_deactivation_hook( __FILE__, array( $dst, 'deactivate'));
