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

add_action("widgets_init", array( 'DeltaStockTicker', 'register') );
add_action("widgets_init", array( 'DeltaStockTicker', 'load_scripts') );
register_activation_hook( __FILE__, array( 'DeltaStockTicker', 'activate'));
register_deactivation_hook( __FILE__, array( 'DeltaStockTicker', 'deactivate'));
