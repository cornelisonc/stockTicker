<?php
/*
Plugin Name: Delta Stock Ticker
Plugin URI:
Description: This stock ticker doesn't suck.
Author: Ceili Cornelison, Delta Systems
Version:
Author URI:
*/

include_once("DeltaStockTicker.php");

$dst = new DeltaStockTicker();

add_action("widgets_init", array( $dst, 'register') );
add_action("widgets_init", array( $dst, 'load_scripts') );
register_activation_hook( __FILE__, array( $dst, 'activate'));
register_deactivation_hook( __FILE__, array( $dst, 'deactivate'));
